<?php
namespace App\Http\Controllers;

use App\Data\Mappers\ReservationMapper as ReservationMapper;
use App\Data\Mappers\RoomMapper as RoomMapper;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Auth as Auth;
use Carbon\Carbon as Carbon;

class CalendarController extends CalendarController__AopProxied implements \Go\Aop\Proxy
{

    /**
     * Property was created automatically, do not change it manually
     */
    private static $__joinPoints = [];
    
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        return self::$__joinPoints['method:__construct']->__invoke($this);
    }
    
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewCalendar(\Illuminate\Http\Request $request)
    {
        return self::$__joinPoints['method:viewCalendar']->__invoke($this, [$request]);
    }
    
    
    public function authorize($ability, $arguments = array (
    ))
    {
        return self::$__joinPoints['method:authorize']->__invoke($this, \array_slice([$ability, $arguments], 0, \func_num_args()));
    }
    
    
    public function authorizeForUser($user, $ability, $arguments = array (
    ))
    {
        return self::$__joinPoints['method:authorizeForUser']->__invoke($this, \array_slice([$user, $ability, $arguments], 0, \func_num_args()));
    }
    
    
    public function authorizeResource($model, $parameter = NULL, array $options = array (
    ), $request = NULL)
    {
        return self::$__joinPoints['method:authorizeResource']->__invoke($this, \array_slice([$model, $parameter, $options, $request], 0, \func_num_args()));
    }
    
    
    public function dispatchNow($job)
    {
        return self::$__joinPoints['method:dispatchNow']->__invoke($this, [$job]);
    }
    
    
    public function validateWith($validator, \Illuminate\Http\Request $request = NULL)
    {
        return self::$__joinPoints['method:validateWith']->__invoke($this, \array_slice([$validator, $request], 0, \func_num_args()));
    }
    
    
    public function validate(\Illuminate\Http\Request $request, array $rules, array $messages = array (
    ), array $customAttributes = array (
    ))
    {
        return self::$__joinPoints['method:validate']->__invoke($this, \array_slice([$request, $rules, $messages, $customAttributes], 0, \func_num_args()));
    }
    
    
    public function validateWithBag($errorBag, \Illuminate\Http\Request $request, array $rules, array $messages = array (
    ), array $customAttributes = array (
    ))
    {
        return self::$__joinPoints['method:validateWithBag']->__invoke($this, \array_slice([$errorBag, $request, $rules, $messages, $customAttributes], 0, \func_num_args()));
    }
    
    
    public function middleware($middleware, array $options = array (
    ))
    {
        return self::$__joinPoints['method:middleware']->__invoke($this, \array_slice([$middleware, $options], 0, \func_num_args()));
    }
    
    
    public function getMiddleware()
    {
        return self::$__joinPoints['method:getMiddleware']->__invoke($this);
    }
    
    
    public function callAction($method, $parameters)
    {
        return self::$__joinPoints['method:callAction']->__invoke($this, [$method, $parameters]);
    }
    
    
    public function missingMethod($parameters = array (
    ))
    {
        return self::$__joinPoints['method:missingMethod']->__invoke($this, \array_slice([$parameters], 0, \func_num_args()));
    }
    
    
    public function __call($method, $parameters)
    {
        return self::$__joinPoints['method:__call']->__invoke($this, [$method, $parameters]);
    }
    
}
\Go\Proxy\ClassProxy::injectJoinPoints('App\Http\Controllers\CalendarController',array (
  'method' => 
  array (
    '__construct' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'viewCalendar' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
      1 => 'advisor.App\\Aspect\\CalendarAspect->beforeMethodExecution',
    ),
    'authorize' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'authorizeForUser' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'authorizeResource' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'dispatchNow' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'validateWith' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'validate' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'validateWithBag' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'middleware' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'getMiddleware' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'callAction' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    'missingMethod' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
    '__call' => 
    array (
      0 => 'advisor.App\\Aspect\\MonitorAspect->beforeMethodExecution',
    ),
  ),
));