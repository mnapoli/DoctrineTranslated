<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationUtils;

/**
 * @covers \Mnapoli\Translated\TranslationUtils
 */
class TranslationUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetLanguage()
    {
        $this->assertSame('en', TranslationUtils::getLanguage('en'));
        $this->assertSame('en', TranslationUtils::getLanguage('en_US'));
        $this->assertSame('en', TranslationUtils::getLanguage('en_US_US'));
        $this->assertSame('en', TranslationUtils::getLanguage('en_'));
        $this->assertSame('', TranslationUtils::getLanguage(''));
        $this->assertSame(null, TranslationUtils::getLanguage(null));
    }
}
