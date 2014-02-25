<?php

namespace Test\Mnapoli\Translated\Fixture;

use Mnapoli\Translated\TranslatedString;

/**
 * @Embeddable
 */
class MyTranslatedString extends TranslatedString
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
