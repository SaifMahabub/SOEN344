<?php

namespace App;


use App\Data\Mappers\ReservationMapper;
use Go\Aop\Aspect;
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

    /**
     * Method that will be called before the login method in LoginController
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
}