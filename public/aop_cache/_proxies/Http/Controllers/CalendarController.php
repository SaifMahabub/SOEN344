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
    
    /**
     * Authorize a given action for the current user.
     *
     * @param  mixed  $ability
     * @param  mixed|array  $arguments
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorize($ability, $arguments = array (
    ))
    {
        return self::$__joinPoints['method:authorize']->__invoke($this, \array_slice([$ability, $arguments], 0, \func_num_args()));
    }
    
    /**
     * Authorize a given action for a user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|mixed  $user
     * @param  mixed  $ability
     * @param  mixed|array  $arguments
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeForUser($user, $ability, $arguments = array (
    ))
    {
        return self::$__joinPoints['method:authorizeForUser']->__invoke($this, \array_slice([$user, $ability, $arguments], 0, \func_num_args()));
    }
    
    /**
     * Authorize a resource action based on the incoming request.
     *
     * @param  string  $model
     * @param  string|null  $parameter
     * @param  array  $options
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function authorizeResource($model, $parameter = NULL, array $options = array (
    ), $request = NULL)
    {
        return self::$__joinPoints['method:authorizeResource']->__invoke($this, \array_slice([$model, $parameter, $options, $request], 0, \func_num_args()));
    }
    
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @return mixed
     */
    public function dispatchNow($job)
    {
        return self::$__joinPoints['method:dispatchNow']->__invoke($this, [$job]);
    }
    
    /**
     * Run the validation routine against the given validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator|array  $validator
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function validateWith($validator, \Illuminate\Http\Request $request = NULL)
    {
        return self::$__joinPoints['method:validateWith']->__invoke($this, \array_slice([$validator, $request], 0, \func_num_args()));
    }
    
    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function validate(\Illuminate\Http\Request $request, array $rules, array $messages = array (
    ), array $customAttributes = array (
    ))
    {
        return self::$__joinPoints['method:validate']->__invoke($this, \array_slice([$request, $rules, $messages, $customAttributes], 0, \func_num_args()));
    }
    
    /**
     * Validate the given request with the given rules.
     *
     * @param  string  $errorBag
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateWithBag($errorBag, \Illuminate\Http\Request $request, array $rules, array $messages = array (
    ), array $customAttributes = array (
    ))
    {
        return self::$__joinPoints['method:validateWithBag']->__invoke($this, \array_slice([$errorBag, $request, $rules, $messages, $customAttributes], 0, \func_num_args()));
    }
    
    /**
     * Register middleware on the controller.
     *
     * @param  array|string|\Closure  $middleware
     * @param  array   $options
     * @return \Illuminate\Routing\ControllerMiddlewareOptions
     */
    public function middleware($middleware, array $options = array (
    ))
    {
        return self::$__joinPoints['method:middleware']->__invoke($this, \array_slice([$middleware, $options], 0, \func_num_args()));
    }
    
    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware()
    {
        return self::$__joinPoints['method:getMiddleware']->__invoke($this);
    }
    
    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        return self::$__joinPoints['method:callAction']->__invoke($this, [$method, $parameters]);
    }
    
    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function missingMethod($parameters = array (
    ))
    {
        return self::$__joinPoints['method:missingMethod']->__invoke($this, \array_slice([$parameters], 0, \func_num_args()));
    }
    
    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
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
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'viewCalendar' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'authorize' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'authorizeForUser' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'authorizeResource' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'dispatchNow' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'validateWith' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'validate' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'validateWithBag' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'middleware' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'getMiddleware' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'callAction' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    'missingMethod' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
    '__call' => 
    array (
      0 => 'advisor.App\\MonitorAspect->beforeMethodExecution',
    ),
  ),
));