<?php

namespace Mnapoli\Translated\Helper\Twig;

use Mnapoli\Translated\AbstractTranslatedString;
use Mnapoli\Translated\Translator;

class TranslateTwigHelper extends \Twig_Extension
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('translate', [$this, 'translate']),
        ];
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

    public function getName()
    {
        return 'translated';
    }
}
