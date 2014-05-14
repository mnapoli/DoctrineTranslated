<?php

namespace Mnapoli\Translated\Helper\Zend1;

use Mnapoli\Translated\AbstractTranslatedString;
use Mnapoli\Translated\TranslationHelper;

class TranslateZend1Helper
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
     * @param AbstractTranslatedString $string
     * @return string
     */
    public function translate(AbstractTranslatedString $string)
    {
        return $this->translationHelper->toString($string);
    }
}
