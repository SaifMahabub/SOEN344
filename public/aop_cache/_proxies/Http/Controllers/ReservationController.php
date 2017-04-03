<?php
namespace App\Http\Controllers;

use App\Data\Mappers\ReservationMapper as ReservationMapper;
use App\Data\Mappers\RoomMapper as RoomMapper;
use App\Data\ReservationSession as ReservationSession;
use App\Data\TDGs\ReservationSessionTDG as ReservationSessionTDG;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Auth as Auth;
use App\Data\Mappers\EquipmentMapper as EquipmentMapper;

class ReservationController extends ReservationController__AopProxied implements \Go\Aop\Proxy
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewReservationList(\Illuminate\Http\Request $request)
    {
        return self::$__joinPoints['method:viewReservationList']->__invoke($this, [$request]);
    }
    
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function viewReservation(\Illuminate\Http\Request $request, $id)
    {
        return self::$__joinPoints['method:viewReservation']->__invoke($this, [$request, $id]);
    }
    
    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function showModifyForm(\Illuminate\Http\Request $request, $id)
    {
        return self::$__joinPoints['method:showModifyForm']->__invoke($this, [$request, $id]);
    }
    
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function modifyReservation(\Illuminate\Http\Request $request, $id)
    {
        return self::$__joinPoints['method:modifyReservation']->__invoke($this, [$request, $id]);
    }
    
    /**
     * @param Request $request
     * @param string $roomName
     * @param string $timeslot
     * @return \Illuminate\Http\Response
     */
    public function showRequestForm(\Illuminate\Http\Request $request, $roomName, $timeslot)
    {
        return self::$__joinPoints['method:showRequestForm']->__invoke($this, [$request, $roomName, $timeslot]);
    }
    
    /**
     * @param Request $request
     * @param string $roomName
     * @param string $timeslot
     * @return \Illuminate\Http\Response
     */
    public function requestReservation(\Illuminate\Http\Request $request, $roomName, $timeslot)
    {
        return self::$__joinPoints['method:requestReservation']->__invoke($this, [$request, $roomName, $timeslot]);
    }
    
    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function cancelReservation(\Illuminate\Http\Request $request, $id)
    {
        return self::$__joinPoints['method:cancelReservation']->__invoke($this, [$request, $id]);
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
        return self::$__joinPoints['method:checkEquipmentAvailable']->__invoke($this, [$id, $timeslot]);
    }
    
    /**
     * Checks if max_per_week reached.
     * @params reservationMapper instance
     * @params Carbon type date/time
     * @return bool
     */
    public function reachedWeeklyLimit(\App\Data\Mappers\ReservationMapper $reservationMapper, $timeslot)
    {
        return self::$__joinPoints['method:reachedWeeklyLimit']->__invoke($this, [$reservationMapper, $timeslot]);
    }
    
    /**
     * Makes sure that a new reservation isn't a recurring one over the max limit.
     * @param ReservationMapper $reservationMapper
     * @param $roomName
     * @param Carbon $timeslot
     * @param int $recurrence
     * @return bool
     */
    public function ensureNotMaxRecur(\App\Data\Mappers\ReservationMapper $reservationMapper, $roomName, \Carbon\Carbon $timeslot, $recurrence)
    {
        return self::$__joinPoints['method:ensureNotMaxRecur']->__invoke($this, [$reservationMapper, $roomName, $timeslot, $recurrence]);
    }
    
    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function cancelReservationSession(\Illuminate\Http\Request $request)
    {
        return self::$__joinPoints['method:cancelReservationSession']->__invoke($this, [$request]);
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
    
    
    protected function parseAbilityAndArguments($ability, $arguments)
    {
        return self::$__joinPoints['method:parseAbilityAndArguments']->__invoke($this, [$ability, $arguments]);
    }
    
    
    protected function normalizeGuessedAbilityName($ability)
    {
        return self::$__joinPoints['method:normalizeGuessedAbilityName']->__invoke($this, [$ability]);
    }
    
    
    public function authorizeResource($model, $parameter = NULL, array $options = array (
    ), $request = NULL)
    {
        return self::$__joinPoints['method:authorizeResource']->__invoke($this, \array_slice([$model, $parameter, $options, $request], 0, \func_num_args()));
    }
    
    
    protected function resourceAbilityMap()
    {
        return self::$__joinPoints['method:resourceAbilityMap']->__invoke($this);
    }
    
    
    protected function dispatch($job)
    {
        return self::$__joinPoints['method:dispatch']->__invoke($this, [$job]);
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
    
    
    protected function throwValidationException(\Illuminate\Http\Request $request, $validator)
    {
        return self::$__joinPoints['method:throwValidationException']->__invoke($this, [$request, $validator]);
    }
    
    
    protected function buildFailedValidationResponse(\Illuminate\Http\Request $request, array $errors)
    {
        return self::$__joinPoints['method:buildFailedValidationResponse']->__invoke($this, [$request, $errors]);
    }
    
    
    protected function formatValidationErrors(\Illuminate\Contracts\Validation\Validator $validator)
    {
        return self::$__joinPoints['method:formatValidationErrors']->__invoke($this, [$validator]);
    }
    
    
    protected function getRedirectUrl()
    {
        return self::$__joinPoints['method:getRedirectUrl']->__invoke($this);
    }
    
    
    protected function getValidationFactory()
    {
        return self::$__joinPoints['method:getValidationFactory']->__invoke($this);
    }
    
    
    protected function withErrorBag($errorBag, callable $callback)
    {
        return self::$__joinPoints['method:withErrorBag']->__invoke($this, [$errorBag, $callback]);
    }
    
    
    protected function errorBag()
    {
        return self::$__joinPoints['method:errorBag']->__invoke($this);
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
\Go\Proxy\ClassProxy::injectJoinPoints('App\Http\Controllers\ReservationController',array (
  'method' => 
  array (
    '__construct' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'viewReservationList' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'viewReservation' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'showModifyForm' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'modifyReservation' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
      1 => 'advisor.App\\Aspect\\ReservationAspect->beforeMethodExecution',
    ),
    'showRequestForm' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
      1 => 'advisor.App\\Aspect\\ReservationAspect->beforeMethodExecutionSelfSession',
      2 => 'advisor.App\\Aspect\\ReservationAspect->beforeMethodExecutionCheckLock',
      3 => 'advisor.App\\Aspect\\ReservationAspect->beforeMethodExecutionWeeklyLimit',
      4 => 'advisor.App\\Aspect\\ReservationAspect->beforeMethodExecutionWaitlistFull',
      5 => 'advisor.App\\Aspect\\ReservationAspect->beforeMethodExecutionIsPast',
    ),
    'requestReservation' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'cancelReservation' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
      1 => 'advisor.App\\Aspect\\EquipmentAspect->beforeCancellation',
      2 => 'advisor.App\\Aspect\\EquipmentAspect->afterCancelReservation',
    ),
    'checkEquipmentAvailable' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'reachedWeeklyLimit' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'ensureNotMaxRecur' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'cancelReservationSession' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'authorize' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'authorizeForUser' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'parseAbilityAndArguments' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'normalizeGuessedAbilityName' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'authorizeResource' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'resourceAbilityMap' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'dispatch' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'dispatchNow' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'validateWith' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'validate' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'validateWithBag' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'throwValidationException' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'buildFailedValidationResponse' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'formatValidationErrors' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'getRedirectUrl' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'getValidationFactory' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'withErrorBag' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'errorBag' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'middleware' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'getMiddleware' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'callAction' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    'missingMethod' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
    '__call' => 
    array (
      0 => 'advisor.App\\Aspect\\LoggingAspect->beforeReservationMethod',
    ),
  ),
));