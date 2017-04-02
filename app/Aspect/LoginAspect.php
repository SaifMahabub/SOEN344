<?php
// Aspect/LoginAspect.php
namespace App;


use Go\Aop\Aspect;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;

/**
 * Login aspect
 */
class LoginAspect implements Aspect
{

    /**
     * Method that will be called before the login method in LoginController
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public App\Http\Controllers\LoginController->login(*))")
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
         $arguments = $invocation->getArguments();
         $id = $arguments[0]->input('id');
         $password = $arguments[0]->input('password');

         if($id==null||$password==null||strlen($id)!=8){
              redirect()->back()
            ->withInput($arguments[0]->only('id', 'remember'))
            ->withErrors([
                'id' => Lang::get('auth.failed'),
            ]);            
         }

         return null;
    }
}