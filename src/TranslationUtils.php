<?php

namespace Mnapoli\Translated;

/**
 * Utilities.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslationUtils
{
    /**
     * Returns the language of the given locale.
     *
     * @param string $locale Locale (e.g. "en_US")
     *
     * @return string Language (e.g. "en")
     */
    public static function getLanguage($locale)
    {
        if (strpos($locale, '_') === false) {
            return $locale;
        }

        return substr($locale, 0, strpos($locale, '_'));
    }

    /**
     * Returns the region of the given locale.
     *
     * @param string $locale Locale (e.g. "en_US")
     *
     * @return string Language (e.g. "US")
     */
    public static function getRegion($locale)
    {
        if (strpos($locale, '_') === false) {
            return null;
        }

        $region = substr($locale, strpos($locale, '_') + 1);

        return ($region !== false) ? $region : null;
    }
}
