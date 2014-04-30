<?php

namespace Mnapoli\Translated\Helper\Zend1;

use Mnapoli\Translated\TranslatedStringInterface;
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
     * @param TranslatedStringInterface $string
     * @return string
     */
    public function translate(TranslatedStringInterface $string)
    {
        return $this->translationHelper->toString($string);
    }
}
