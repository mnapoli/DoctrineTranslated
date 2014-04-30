<?php

namespace Test\Mnapoli\Translated\Fixture;

use Mnapoli\Translated\TranslatedStringInterface;
use Mnapoli\Translated\TranslatedStringTrait;

/**
 * @Embeddable
 */
class TranslatedString implements TranslatedStringInterface
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
