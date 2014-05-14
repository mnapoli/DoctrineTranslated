<?php

namespace Test\Mnapoli\Translated\Fixture;

use Mnapoli\Translated\AbstractTranslatedString;

/**
 * @Embeddable
 */
class TranslatedString extends  AbstractTranslatedString
{
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
