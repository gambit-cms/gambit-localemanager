<?php
namespace Gambit\LocaleManager;

interface LocaleManagerInterface
{
    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Set the current locale.
     *
     * @param string $locale The locale
     * @return LocaleManagerInterface
     */
    public function setLocale($locale);
}