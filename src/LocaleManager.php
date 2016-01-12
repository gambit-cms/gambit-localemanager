<?php
namespace Gambit\LocaleManager;

use Locale;
use Gambit\LocaleManager\Exception\LocaleNotAvailableException;

class LocaleManager implements LocaleManagerInterface
{
    protected $locale;

    /**
     * Get the current locale.
     * 
     * @return string
     */
    public function getLocale()
    {
        if ($this->locale === null) {
            $this->setLocale( Locale::getDefault() );
        }
        return $this->locale;
    }

    /**
     * Set the current locale.
     * 
     * @param string $locale The locale
     * @return LocaleManager
     */
    public function setLocale($locale) 
    {
        // Always use caninicalized locales
        $locale = Locale::canonicalize($locale);

        // If the given locale matches the current locale, nothing to do.
        if (strcmp($locale, $this->locale) === 0) {
            return $this;
        }

        // Set locale information
        $localeVariants= [
            $locale . '.UTF-8',
            $locale,
            \Locale::getDisplayLanguage($locale, 'en')
        ];
        if ( false === setlocale(LC_ALL, $localeVariants) ) {
            throw new LocaleNotAvailableException(sprintf(
                'The locale "%s" is not available on the system.',
                $locale
            ));
        }

        // Store the locale
        $this->locale = Locale::canonicalize($locale);
        return $this;
    }
}