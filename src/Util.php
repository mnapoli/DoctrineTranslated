<?php

namespace Mnapoli\Translated;

/**
 * Utilities.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Util
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
}
