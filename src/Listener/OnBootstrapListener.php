<?php
namespace Gambit\LocaleManager\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class OnBootstrapListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, array($this, 'onPostBootstrap'), -1);
    }

    public function onPostBootstrap(MvcEvent $event)
    {
        $request  = $event->getRequest();

        // This only applies to HTTP requests
        if (!$request instanceof \Zend\Http\Request) {
            return;
        }

        $services = $event->getApplication()->getServiceManager();
        $events   = $event->getApplication()->getEventManager();
        
        // The locale manager
        if (!$services->has('LocaleManager')) {
            $services->setFactory('LocaleManager', 'Gambit\LocaleManager\Service\LocaleManagerFactory');
        }
        
        if (!$services->has('Gambit\LocaleManager\Listener\Finish')) {
            $services->setInvokableClass('Gambit\LocaleManager\Listener\Finish', 'Gambit\LocaleManager\Listener\FinishListener');
        }
        if (!$services->has('Gambit\LocaleManager\Listener\Render')) {
            $services->setInvokableClass('Gambit\LocaleManager\Listener\Render', 'Gambit\LocaleManager\Listener\RenderListener');
        }

        // Attach the listeners for the locale manager
        $events->attach( $services->get('Gambit\LocaleManager\Listener\Finish'));
        $events->attach( $services->get('Gambit\LocaleManager\Listener\Render'));        
    }
}