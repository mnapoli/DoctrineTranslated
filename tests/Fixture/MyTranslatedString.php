<?php

namespace Test\Mnapoli\Translated\Fixture;

use Mnapoli\Translated\TranslatedString;
use Mnapoli\Translated\TranslatedStringTrait;

/**
 * @Embeddable
 */
class MyTranslatedString implements TranslatedString
{
    use TranslatedStringTrait;

    /**
     * @Column(type = "string", nullable=true)
     */
    protected $en;

    /**
     * @Column(type = "string", nullable=true)
     */
    protected $fr;

    /**
     * @Column(type = "string", nullable=true)
     */
    protected $de;
}
