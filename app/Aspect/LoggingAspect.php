<?php

namespace App\Aspect;


use Go\Aop\Aspect;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;

/**
 * Monitor aspect
 */
class LoggingAspect implements Aspect
{
    /**
     * Method that will be called BEFORE reservationController methods
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public|protected App\Http\Controllers\ReservationController->*(*))")
     */
    public function beforeReservationMethod(MethodInvocation $invocation)
    {
        if (app()->env != 'production') {
            $obj = $invocation->getThis();
            app('debugbar')->info(
                'Calling Before Interceptor for method: ',
                is_object($obj) ? get_class($obj) : $obj,
                $invocation->getMethod()->isStatic() ? '::' : '->',
                $invocation->getMethod()->getName(),
                '()',
                ' with arguments: ',
                json_encode($invocation->getArguments())
            );
        }
    }
}