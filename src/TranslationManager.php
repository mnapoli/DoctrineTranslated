<?php

namespace Mnapoli\Translated;

/**
 * Manages everything related to the translations.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslationManager
{
    /**
     * @var array
     */
    private $fallbacks;

    /**
     * Defines a list of fallback locales for each locale.
     *
     *     ->setFallbacks([
     *         'en' => ['de', 'fr'],
     *         'fr' => ['en'],
     *         'de' => ['en'],
     *     ])
     *
     * @param array $fallbacks
     */
    public function setFallbacks(array $fallbacks)
    {
        $this->fallbacks = $fallbacks;
    }

    /**
     * Returns a new context.
     *
     * @param string $locale
     *
     * @return TranslationContext
     */
    public function createContext($locale)
    {
        $fallback = $this->getFallback($locale);

        return new TranslationContext($locale, $fallback);
    }

    /**
     * Returns a list of fallback locales configured for the given locale.
     *
     * @param string $locale
     *
     * @return string[]
     */
    protected function getFallback($locale)
    {
        if (! array_key_exists($locale, $this->fallbacks)) {
            return [];
        }

        return (array) $this->fallbacks[$locale];
    }
}
