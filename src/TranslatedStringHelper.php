<?php

namespace Mnapoli\Translated;

/**
 * Helper to display and edit translated string.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class TranslatedStringHelper
{
    /**
     * @var TranslationContext
     */
    private $context;

    public function __construct(TranslationContext $context)
    {
        $this->context = $context;
    }

    /**
     * Convert a TranslatedString to a string using the given locale.
     *
     * If no locale is given, the current locale of the context will be used.
     *
     * You can provide a list of fallback locales if no translation was set for the given locale.
     * The first translation not null that was found from the given locales will be returned.
     *
     * @param TranslatedString $string
     * @param string|null      $locale   If null, will use the current locale.
     * @param string[]         $fallback List of locales to use as fallback.
     *
     * @return null|string Returns the string, or null if no translation was set for this locale.
     */
    public function toString(TranslatedString $string, $locale = null, array $fallback = [])
    {
        if ($locale === null) {
            $locale = $this->context->getLocale();
        }

        $value = $string->get($locale);

        if ($value) {
            return $value;
        }

        foreach ($fallback as $fallbackLocale) {
            $value = $string->get($fallbackLocale);
            if ($value) {
                return $value;
            }
        }

        return null;
    }
}
