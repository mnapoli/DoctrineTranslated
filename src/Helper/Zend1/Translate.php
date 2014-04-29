<?php

namespace Mnapoli\Translated\Helper\Zend1;

use Mnapoli\Translated\TranslatedString;
use Mnapoli\Translated\TranslationHelper;

class Translate
{
    /**
     * @var TranslationHelper
     */
    private $translationHelper;

    public function __construct(TranslationHelper $translationHelper)
    {
        $this->translationHelper = $translationHelper;
    }

    /**
     * Translates a string into the current locale.
     * @param TranslatedString $string
     * @return string
     */
    public function translate(TranslatedString $string)
    {
        return $this->translationHelper->toString($string);
    }
}
