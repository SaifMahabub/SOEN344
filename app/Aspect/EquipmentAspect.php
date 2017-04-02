<?php

namespace App\Aspect;


use Go\Aop\Aspect;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;
use App\Data\Mappers\ReservationMapper;
use App\Data\Mappers\EquipmentMapper;

/**
 * Monitor aspect
 */
class EquipmentAspect implements Aspect
{
    private static $currentRes = [];

    /**
     * Method that will be called before cancelReservation.
     * Reservation object stored for manipulation post-cancellation.
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public App\Http\Controllers\ReservationController->cancelReservation(*))")
     */
    public function beforeCancellation(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $resId = $arguments[1];
        $reservationMapper = ReservationMapper::getInstance();
        $reservation = $reservationMapper->find($resId);
        static::$currentRes[$resId] = $reservation;
    }

    /**
     * Promote other reservations that are dependent on this one, if this one was active!
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Http\Controllers\ReservationController->cancelReservation(*))")
     */
    public function afterCancelReservation(MethodInvocation $invocation)
    {
        $arguments = $invocation->getArguments();
        $resId = $arguments[1];
        $reservation = static::$currentRes[$resId];
        $reservationMapper = ReservationMapper::getInstance();

        $cancelledEquip = $reservation->getEquipmentId();
        $resTimeslot = $reservation->getTimeslot();

        /**
         * if reservation was active then need to replace it with next in waiting list,
         * and check if someone else needs to be promoted.
         */
        if (!$reservation->getWaitlisted()) {
            $reservations = $reservationMapper->findForTimeslot($reservation->getRoomName(), $reservation->getTimeslot());
            $resSize = count($reservations);
            //make them active only if the equipment they need is available!
            //make the next person on the list not waitlisted anymore only if the equipment they need is also available.
            $equipId = null;
            //start at 1 because first value is the current active one to be cancelled.
            for ($i=0; $i < $resSize; $i++) {
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

        $reservationMapper->done();

        unset(static::$currentRes[$resId]);
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
}