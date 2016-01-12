<?php
namespace Gambit\LocaleManager\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Gambit\LocaleManager\LocaleManager;

class LocaleManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $localeManager = new LocaleManager();

        // Locale from Translator if available
        $locale = null;
        if ($serviceLocator->has('Translator')) {
            $translator = $serviceLocator->get('Translator');
            if ($translator instanceof \Zend\Mvc\I18n\Translator) {
                $translator = $translator->getTranslator();
            }

            if (method_exists($translator, 'getLocale')) {
                $localeManager->setLocale( $translator->getLocale() );
            }
        }

        return $localeManager;
    }
}