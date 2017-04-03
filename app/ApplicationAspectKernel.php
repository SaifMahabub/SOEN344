<?php
// app/ApplicationAspectKernel.php
namespace App;

use App\Aspect\MonitorAspect;
use App\Aspect\EquipmentAspect;
use App\Aspect\LoggingAspect;
use App\Aspect\CalendarAspect;
use App\Aspect\ReservationAspect;
use Go\Core\AspectKernel;
use Go\Core\AspectContainer;


/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends AspectKernel
{

    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        $container->registerAspect(new MonitorAspect());
        $container->registerAspect(new LoggingAspect());
        $container->registerAspect(new EquipmentAspect());
        $container->registerAspect(new CalendarAspect());
        $container->registerAspect(new ReservationAspect());
    }
}