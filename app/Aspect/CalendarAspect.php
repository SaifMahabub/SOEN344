<?php
// Aspect/MonitorAspect.php
namespace App\Aspect;


use Carbon\Carbon;
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
class CalendarAspect implements Aspect
{

    /**
     * Method that will be called before the viewCalendar method in CalendarController
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public App\Http\Controllers\CalendarController->viewCalendar(*))")
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
//        $arguments = $invocation->getArguments();
//        $date = $arguments[0]->input('date');
//        if ($date === null) {
//            // default to today
//            $date = Carbon::today();
//        } else {
//            $date = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
//            if ($date->copy()->isPast()) {
//                return redirect()->route('calendar',['date' => $date->toDateString()])
//                    ->with('error', sprintf("You cannot view past reservations."));
//            }
//        }
//
//        return null;
    }
}