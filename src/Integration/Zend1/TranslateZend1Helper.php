<?php

namespace Mnapoli\Translated\Integration\Zend1;

use Mnapoli\Translated\AbstractTranslatedString;
use Mnapoli\Translated\Translator;

class TranslateZend1Helper
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Translates a string into the current locale.
     *
     * @param AbstractTranslatedString $string
     *
     * @return string
     */
    public function translate(AbstractTranslatedString $string)
    {
        return $this->translator->get($string);
    }
}
