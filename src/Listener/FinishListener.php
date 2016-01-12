<?php
namespace Gambit\LocaleManager\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as HttpResponse;

class FinishListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
    }

    public function onFinish(MvcEvent $event)
    {
        $response = $event->getResponse();
        if (!$response instanceof HttpResponse) {
            return;
        }

        $services = $event->getApplication()->getServiceManager();
        $locales  = $services->get('LocaleManager');
        $locale   = $locales->getLocale();
        $response->getHeaders()->addHeaderLine('Content-Language', preg_replace('/\_/', '-', $locale) );
    }
}