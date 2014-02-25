<?php

namespace Mnapoli\Translated;

/**
 * Represents a context for translations.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslationContext
{
    private $locale;

    /**
     * @param string $locale Current locale, for example from the request, or from the logged in user.
     */
    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
