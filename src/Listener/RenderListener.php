<?php
namespace Gambit\LocaleManager\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as HttpResponse;
use Zend\View\HelperPluginManager;

class RenderListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'));
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'));
    }

    
    public function onRender(MvcEvent $event)
    {
        $response = $event->getResponse();
        if (!$response instanceof HttpResponse) {
            return;
        }

        $this->onRenderCommon($event);
    }

    public function onRenderCommon(MvcEvent $event)
    {
        $response = $event->getResponse();
        if (!$response instanceof HttpResponse) {
            return;
        }

        $services = $event->getApplication()->getServiceManager();

        $locales  = $services->get('LocaleManager');
        $locale   = $locales->getLocale();

        // HTML TAG view helper
        if ($services->has('ViewHelperManager')) {
            $viewHelperManager = $services->get('ViewHelperManager');
            if ($viewHelperManager instanceof HelperPluginManager) {
                // HtmlTag
                if ( $viewHelperManager->has('htmlTag') ) {
                    $htmlTag = $viewHelperManager->get('htmlTag');
                    $htmlTag->setAttribute('lang', \Locale::getPrimaryLanguage($locale));
                }
            }
        }
    }

    public function onRenderError(MvcEvent $event)
    {
        $response = $event->getResponse();
        if (!$response instanceof HttpResponse) {
            return;
        }
    
        $this->onRenderCommon($event);
    }
}