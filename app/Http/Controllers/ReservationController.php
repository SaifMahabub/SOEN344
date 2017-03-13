<?php

namespace App\Http\Controllers;

use App\Data\Mappers\ReservationMapper;
use App\Data\Mappers\RoomMapper;
use App\Data\ReservationSession;
use App\Data\TDGs\ReservationSessionTDG;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Data\Mappers\EquipmentMapper;

class ReservationController extends Controller
{
    const MAX_PER_TIMESLOT = 5;
    const MAX_PER_USER_PER_WEEK = 3;
    const MAX_RECUR = 3;
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewReservationList(Request $request)
    {
        $reservationMapper = ReservationMapper::getInstance();
        $reservations = $reservationMapper->findPositionsForUser(Auth::id());

        return view('reservation.list', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function viewReservation(Request $request, $id)
    {
        // validate reservation exists and is owned by user
        $reservationMapper = ReservationMapper::getInstance();
        $reservation = $reservationMapper->find($id);

        if ($reservation === null || $reservation->getUserId() !== Auth::id()) {
            return abort(404);
        }

        // get a list of all the other reservations for the same room-timeslot
        $position = $reservationMapper->findPosition($reservation);

        return view('reservation.show', [
            'reservation' => $reservation,
            'position' => $position,
            'back' => $request->input('back', 'calendar')
        ]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function showModifyForm(Request $request, $id)
    {
        // validate reservation exists and is owned by user
        $reservationMapper = ReservationMapper::getInstance();
        $reservation = $reservationMapper->find($id);

        if ($reservation === null || $reservation->getUserId() !== Auth::id()) {
            return abort(404);
        }

        return view('reservation.modify', [
            'reservation' => $reservation,
            'back' => $request->input('back')
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function modifyReservation(Request $request, $id)
    {
        // validate reservation exists and is owned by user
        $reservationMapper = ReservationMapper::getInstance();
        $reservation = $reservationMapper->find($id);

        if ($reservation === null || $reservation->getUserId() !== Auth::id()) {
            return abort(404);
        }

        // update the description
        $reservationMapper->set($reservation->getId(), $request->input('description', ""));
        $reservationMapper->done();

        return redirect()
            ->route('reservation', ['id' => $reservation->getId(), 'back' => $request->input('back')])
            ->with('success', 'Successfully modified reservation!');
    }

    /**
     * @param Request $request
     * @param string $roomName
     * @param string $timeslot
     * @return \Illuminate\Http\Response
     */
    public function showRequestForm(Request $request, $roomName, $timeslot)
    {
        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $timeslot);

        // don't allow reserving in the past
        if ($timeslot->copy()->isPast()) {
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', 'You cannot reserve time slots in the past.');
        }

        // validate room exists
        $roomMapper = RoomMapper::getInstance();
        $room = $roomMapper->find($roomName);

        if ($room === null) {
            return abort(404);
        }
      
        $session = new ReservationSession(Auth::id(), $roomName, $timeslot);
        $sessionTDG = ReservationSessionTDG::getInstance();

        // Concurrency handling: lock the resource if another student is reserving.
        if($sessionTDG->checkLock($session)){
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', sprintf("Another student has a session underway. Please wait patiently
                or request for another room."));
        }

        // Concurrency handling: lock the other resources if the user is trying to access multiple
        // resources at the same time.
        if($sessionTDG->checkSessionInProgress($session)){
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', sprintf("You already have a session underway. Please complete
                it before you process to a new reservation."));
        }

        $sessionTDG->makeNewSession($session);

        $equipmentMapper = EquipmentMapper::getInstance();
        $reservationMapper = ReservationMapper::getInstance();
        $equipment = $equipmentMapper->findAll();

        if ($this->reachedWeeklyLimit($reservationMapper, $timeslot)) {
                return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                    ->with('error', sprintf("You've exceeded your reservation request limit (%d) for this week.", static::MAX_PER_USER_PER_WEEK));
        }

        // check if waiting list for timeslot is full
        $reservations = $reservationMapper->findForTimeslot($roomName, $timeslot);

        if (count($reservations) >= static::MAX_PER_TIMESLOT) {
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', 'The waiting list for that time slot is full.');
        }

        return view('reservation.request', [
            'room' => $room,
            'timeslot' => $timeslot,
            'equipment' => $equipment
        ]);
    }

    /**
     * @param Request $request
     * @param string $roomName
     * @param string $timeslot
     * @return \Illuminate\Http\Response
     */
    public function requestReservation(Request $request, $roomName, $timeslot)
    {
        $this->validate($request, [
            'description' => 'required',
            'recur' => 'required|integer|min:1|max:'.static::MAX_RECUR
        ]);

        $equipmentId = $request->input('equipment', null);
        $equipmentId = $equipmentId < 0 ? null : $equipmentId;

        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $timeslot);

        // don't allow reserving in the past
        if ($timeslot->copy()->isPast()) {
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', 'You cannot reserve time slots in the past.');
        }

        // validate room exists
        $roomMapper = RoomMapper::getInstance();
        $room = $roomMapper->find($roomName);

        if ($room === null) {
            return abort(404);
        }

        // generate a UUID for this reservation session, which will link recurring reservations together
        $uuid = \Uuid::generate();
        $reservations = [];

        $reservationMapper = ReservationMapper::getInstance();
        $recur = intval($request->input('recur', 1));

        // status message arrays
        $successful = [];
        $waitlisted = [];
        $errored = [];
        $pendingEquipment = [];

        $response = redirect()
            ->route('calendar', ['date' => $timeslot->toDateString()]);

        //TODO: extract make error, success, & warning for reservation
        if (!$this->ensureNotMaxRecur($reservationMapper, $roomName, $timeslot, $recur)) {
            $errored[] = [$timeslot->copy(), sprintf("You've exceeded your recurring reservation limit of %d.", static::MAX_RECUR)];
            return $response->with('error', sprintf('The following requests were unsuccessful for %s at %s:<ul class="mb-0">%s</ul>', $room->getName(), $timeslot->format('g a'), implode("\n", array_map(function ($m) {
                return sprintf("<li><strong>%s</strong>: %s</li>", $m[0]->format('l, F jS, Y'), $m[1]);
            }, $errored))));
        }

        // loop over every recurring week and independently request the reservation for that week
        for ($t = $timeslot->copy(), $i = 0; $i < $recur; $t->addWeek(), ++$i) {

            /*
             * Pre-insert checks
             */

            // check if user exceeded maximum amount of reservations
            if ($this->reachedWeeklyLimit($reservationMapper, $t)) {
                $errored[] = [$t->copy(), sprintf("You've exceeded your weekly reservation request limit of %d.", static::MAX_PER_USER_PER_WEEK)];
                continue;
            }

            // check if waiting list for timeslot is full
            $fullList = $reservationMapper->findForTimeslot($roomName, $t);
            if (count($fullList) >= static::MAX_PER_TIMESLOT) {
                $errored[] = [$t->copy(), 'The waiting list is full.'];
                continue;
            }

            $isWaitlisted = false;
            //if someone already has it reserved, you'll be added to the waiting list.
            if (count($fullList) != 0 && !$fullList[0]->getWaitlisted()) {
                $isWaitlisted = true;
            } else if ($equipmentId != null){
                //if equipment not available for that time slot, on the waiting list you go.
                if (!$this->checkEquipmentAvailable($equipmentId, $t)){
                    $isWaitlisted = true;
                    $equipName = EquipmentMapper::getInstance()->find($equipmentId)->getName();
                    $pendingEquipment[] = [$t->copy(), $equipName];
                }
            }

            /*
             * Insert
             */
            $reservations[] = $reservationMapper->create(intval(Auth::id()), $room->getName(), $t->copy(), $request->input('description', ''), $uuid, $isWaitlisted, $equipmentId);
        }

        // run the reservation operations now, as we need to process the results
        $reservationMapper->done();

        /*
         * Post-insert checks
         */

        foreach ($reservations as $reservation) {
            $t = $reservation->getTimeslot();

            // check if there was an error inserting the reservation, ie. duplicate reservation
            if ($reservation->getId() === null) {
                $errored[] = [$t, 'You already have a reservation for this time slot.'];
                continue;
            }

            // find the new reservation's position #
            $position = $reservationMapper->findPosition($reservation);

            if ($position > static::MAX_PER_TIMESLOT) {
                // this request has exceeded the limit, delete it
                $reservationMapper->delete($reservation->getId());
                $errored[] = [$t, 'The waiting list is full.'];
            } else if ($position === 0) {
                // the reservation is active
                $successful[] = $t;
            } else {
                // user has been put on a waiting list
                $waitlisted[] = [$t, $position];
            }
        }

        // commit one last time, to finalize any deletes we had to do
        $reservationMapper->done();

        /*
         * Format the status messages
         */

        if (count($successful)) {
            $response = $response->with('success', sprintf('The following reservations have been successfully created for %s at %s:<ul class="mb-0">%s</ul>', $room->getName(), $timeslot->format('g a'), implode("\n", array_map(function ($m) {
                return sprintf("<li><strong>%s</strong></li>", $m->format('l, F jS, Y'));
            }, $successful))));
        }

        if (count($pendingEquipment)) {
            $response = $response->with('pendingEquipWarning', sprintf('The equipment that you requested is not available at this time: %s at %s:<ul class="mb-0">%s</ul>', $room->getName(), $timeslot->format('g a')
                , implode("\n", array_map(function ($m) {
                return sprintf("<li><strong>%s</strong>: %s</li>", $m[0]->format('l, F jS, Y'), $m[1]);
            }, $pendingEquipment))
            ));
        }

        if (count($waitlisted)) {
            $response = $response->with('warning', sprintf('You have been put on a waiting list for the following reservations for %s at %s:<ul class="mb-0">%s</ul>', $room->getName(), $timeslot->format('g a'), implode("\n", array_map(function ($m) {
                return sprintf("<li><strong>%s</strong>: Position #%d</li>", $m[0]->format('l, F jS, Y'), $m[1]);
            }, $waitlisted))));
        }

        if (count($errored)) {
            $response = $response->with('error', sprintf('The following requests were unsuccessful for %s at %s:<ul class="mb-0">%s</ul>', $room->getName(), $timeslot->format('g a'), implode("\n", array_map(function ($m) {
                return sprintf("<li><strong>%s</strong>: %s</li>", $m[0]->format('l, F jS, Y'), $m[1]);
            }, $errored))));
        }

        $session = new ReservationSession(Auth::id(), $roomName, $timeslot);
        $sessionTDG = ReservationSessionTDG::getInstance();
        $sessionTDG->endSession($session);

        return $response;
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function cancelReservation(Request $request, $id)
    {
        // validate reservation exists and is owned by user
        $reservationMapper = ReservationMapper::getInstance();
        $reservation = $reservationMapper->find($id);

        if ($reservation === null || $reservation->getUserId() !== Auth::id()) {
            return abort(404);
        }

        $cancelledEquip = $reservation->getEquipmentId();
        $resTimeslot = $reservation->getTimeslot();

        //if reservation was active then need to replace it with next in waiting list
        if (!$reservation->getWaitlisted()) {
            $reservations = $reservationMapper->findForTimeslot($reservation->getRoomName(), $reservation->getTimeslot());
            $resSize = count($reservations);
            //make them active only if the equipment they need is available!
            //make the next person on the list not waitlisted anymore only if the equipment they need is also available.
            $equipId = null;
            //start at 1 because first value is the current active one to be cancelled.
            for ($i=1; $i < $resSize; $i++) {
                $equipId = $reservations[$i]->getEquipmentId();
                //if equipId not available, move to the next
                if ($equipId != null && $equipId != $cancelledEquip && !$this->checkEquipmentAvailable($equipId, $reservations[$i]->getTimeslot())) {
                    continue;
                } else {
                    //else make them the active reservation
                    $reservationMapper->setWaitlisted($reservations[$i]->getId(), false);
                    break;
                }
            }

            //now check if an equipment is being freed up, and if someone in another room can be promoted to active due to this availability.
            //if not null and wasn't replaced by the same equipment, then need to check if others are ready to promote.
            if ($cancelledEquip != null && $cancelledEquip != $equipId) {
                //get all ready to promote reservations for this timeslot, equipmentId = $cancelledEquip.
                $readyToBeActive = $reservationMapper->findReadyToBeActiveForTimeWithEquipment($resTimeslot, $cancelledEquip);
                //Promote first on list.
                if (count($readyToBeActive) > 0) {
                    $reservationMapper->setWaitlisted($readyToBeActive[0]->getId(), false);
                }
            }
        }

        // delete the reservation
        $reservationMapper->delete($reservation->getId());
        $reservationMapper->done();

        $response = redirect();

        // redirect to appropriate back page
        if ($request->input('back') === 'list') {
            $response = $response->route('reservationList');
        } else {
            $response = $response->route('calendar', ['date' => $reservation->getTimeslot()->toDateString()]);

        }

        return $response->with('success', 'Successfully cancelled reservation!');
    }

    /**
     * Checks whether or not a piece of equipment is available for a given timeslot.
     *
     * @param int $id the equipment id
     * @param $timeslot
     * @return bool
     */
    public function checkEquipmentAvailable($id, $timeslot)
    {
        if ($id < 0) return true;
        $amount = EquipmentMapper::getInstance()->find($id)->getAmount();

        $reservationMapper = ReservationMapper::getInstance();
        $totalUsing = $reservationMapper->findActiveForTimeWithEquipment($id, $timeslot);

        //if none available return false
        if ($totalUsing >= $amount) return false;
        else return true;
    }

    /**
     * Checks if max_per_week reached.
     * @params reservationMapper instance
     * @params Carbon type date/time
     * @return bool
     */
    public function reachedWeeklyLimit(ReservationMapper $reservationMapper, $timeslot) {
        $reservationCount = $reservationMapper->countInRange(Auth::id(), $timeslot->copy()->startOfWeek(), $timeslot->copy()->startOfWeek()->addWeek());

        if ($reservationCount >= static::MAX_PER_USER_PER_WEEK) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Makes sure that a new reservation isn't a recurring one over the max limit.
     * @param ReservationMapper $reservationMapper
     * @param $roomName
     * @param Carbon $timeslot
     * @param int $recurrence
     * @return bool
     */
    public function ensureNotMaxRecur(ReservationMapper $reservationMapper, $roomName, Carbon $timeslot, $recurrence) {
        $recur = 0; //the previous occurrence
        $tempTime = $timeslot ->copy();

        //TODO: duplicate codes in checkPast and future
        //check in the past.
        for ($countdown = static::MAX_RECUR; $countdown > 0; $countdown--) {
            $increasedRecur = false;
            $tempTime = $tempTime->copy()->subWeek();
            //get reservations for that week.
            $reservations = $reservationMapper->findForTimeslot($roomName, $tempTime);

            foreach($reservations as $reservation) {
                if ($reservation->getUserId() == Auth::id()) {
                    $recur++;
                    $increasedRecur = true;
                }
            }

            //if a week isn't recurring, then no need to keep going.
            if (!$increasedRecur) break;
        }

        $tempTime = $timeslot ->copy();

        for ($countdown = static::MAX_RECUR; $countdown > 0; $countdown--) {
            $increasedRecur = false;
            $tempTime = $tempTime->copy()->addWeek();
            //get reservations for that week.
            $reservations = $reservationMapper->findForTimeslot($roomName, $tempTime);

            foreach($reservations as $reservation) {
                if ($reservation->getUserId() == Auth::id()) {
                    $recur++;
                    $increasedRecur = true;
                }
            }

            //if a week isn't recurring, then no need to keep going,
            if (!$increasedRecur) break;
        }

        return $recur + $recurrence <= static::MAX_RECUR ? true : false;
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function cancelReservationSession(Request $request)
    {
        $session = new ReservationSession(Auth::id(), null, null);
        $sessionTDG = ReservationSessionTDG::getInstance();
        $sessionTDG->endSession($session);

        $response = redirect();
        $response = $response->route('calendar', ['date' => date('Y-m-d')]);
        return $response;
    }
}
