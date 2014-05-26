<?php

namespace Test\Mnapoli\Translated\Fixture;

use Mnapoli\Translated\AbstractTranslatedString;

/**
 * @Embeddable
 */
class TranslatedString extends AbstractTranslatedString
{
    /**
     * @Column(type = "string", nullable=true)
     */
    public $en;

    /**
     * @Column(type = "string", nullable=true)
     */
    public $fr;

    /**
     * @Column(type = "string", nullable=true)
     */
    public $de;
}
