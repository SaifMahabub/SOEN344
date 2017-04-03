<?php

namespace App\Aspect;


use App\Data\Mappers\EquipmentMapper;
use App\Data\Mappers\ReservationMapper;
use App\Data\ReservationSession;
use App\Data\TDGs\ReservationSessionTDG;
use Auth;
use Go\Aop\Aspect;
use Carbon\Carbon;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;

/**
 * Reservation aspect
 */
class ReservationAspect implements Aspect
{

    const MAX_PER_USER_PER_WEEK = 3;
    const MAX_PER_TIMESLOT = 5;

    /**
     * Method that will be called before the login method in ReservationController
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public App\Http\Controllers\ReservationController->modifyReservation(*))")
     * @return null|abort(404)
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
        // validate reservation exists and is owned by user
        $arguments = $invocation->getArguments();
        $id = $arguments[0]->input('id');

        $reservationMapper = ReservationMapper::getInstance();
        $reservation = $reservationMapper->find($id);

        if ($reservation === null || $reservation->getUserId() !== Auth::id()) {
            return abort(404);
        }

        return null;
    }

    /**
     * Method that will be called before the login method in ReservationController
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("execution(public App\Http\Controllers\ReservationController->showRequestForm(*))")
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function beforeMethodExecutionSelfSession(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $roomName = $arguments[1];
        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $arguments[2]);

        $session = new ReservationSession(Auth::id(), $roomName, $timeslot);
        $sessionTDG = ReservationSessionTDG::getInstance();

        // Concurrency handling: lock the other resources if the user is trying to access multiple
        // resources at the same time.
        if($sessionTDG->checkSessionInProgress($session)){
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', sprintf("You already have a session underway. Please complete
                it before you process to a new reservation."));
        }

        return $invocation->proceed();
    }

    /**
     * Method that will be called before the login method in ReservationController
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("execution(public App\Http\Controllers\ReservationController->showRequestForm(*))")
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function beforeMethodExecutionCheckLock(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $roomName = $arguments[1];
        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $arguments[2]);

        $session = new ReservationSession(Auth::id(), $roomName, $timeslot);
        $sessionTDG = ReservationSessionTDG::getInstance();

        // Concurrency handling: lock the resource if another student is reserving.
        if($sessionTDG->checkLock($session)){
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', sprintf("Another student has a session underway. Please wait patiently
                or request for another room."));
        }

        return $invocation->proceed();
    }

    /**
     * Method that will be called before the login method in ReservationController
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("execution(public App\Http\Controllers\ReservationController->showRequestForm(*))")
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function beforeMethodExecutionWeeklyLimit(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $arguments[2]);

        $reservationMapper = ReservationMapper::getInstance();

        if ($this->reachedWeeklyLimit($reservationMapper, $timeslot)) {
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', sprintf("You've exceeded your reservation request limit (%d) for this week.", static::MAX_PER_USER_PER_WEEK));
        }

        return $invocation->proceed();
    }

    /**
     * Method that will be called before the login method in ReservationController
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("execution(public App\Http\Controllers\ReservationController->showRequestForm(*))")
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function beforeMethodExecutionWaitlistFull(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $roomName = $arguments[1];
        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $arguments[2]);

        $reservationMapper = ReservationMapper::getInstance();

        // check if waiting list for timeslot is full
        $reservations = $reservationMapper->findForTimeslot($roomName, $timeslot);

        if (count($reservations) >= static::MAX_PER_TIMESLOT) {
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', 'The waiting list for that time slot is full.');
        }

        return $invocation->proceed();
    }

    /**
     * Method that will be called before the login method in ReservationController
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("execution(public App\Http\Controllers\ReservationController->showRequestForm(*))")
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function beforeMethodExecutionIsPast(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $timeslot = Carbon::createFromFormat('Y-m-d\TH', $arguments[2]);

        // don't allow reserving in the past
        if ($timeslot->copy()->isPast()) {
            return redirect()->route('calendar', ['date' => $timeslot->toDateString()])
                ->with('error', 'You cannot reserve time slots in the past.');
        }

        return $invocation->proceed();
    }


    /**
     * Checks if max_per_week reached.
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


}