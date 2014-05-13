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
    private $fallbacks = [];

    /**
     * @var TranslationContext|null
     */
    private $currentContext;

    /**
     * Example of fallbacks:
     *
     *     [
     *         'en' => ['de', 'fr'],
     *         'fr' => ['en'],
     *         'de' => ['en'],
     *     ]
     *
     * @param string $defaultLocale The default locale, to create the default context.
     * @param array  $fallbacks
     */
    public function __construct($defaultLocale, array $fallbacks = [])
    {
        $this->fallbacks = $fallbacks;

        $this->setCurrentContext($defaultLocale);
    }

    /**
     * Creates a new TranslationContext based on the given locale and sets it
     * as the current context.
     *
     * @param string $locale
     *
     * @return TranslationContext
     */
    public function setCurrentContext($locale)
    {
        $fallback = $this->getFallback($locale);

        $this->currentContext = new TranslationContext($locale, $fallback);

        return $this->currentContext;
    }

    /**
     * @return TranslationContext
     */
    public function getCurrentContext()
    {
        return $this->currentContext;
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
