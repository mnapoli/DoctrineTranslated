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

    public function testGetRegion()
    {
        $this->assertEquals('US', TranslationUtils::getRegion('en_US'));
        $this->assertEquals('', TranslationUtils::getRegion('en'));
        $this->assertSame('US_US', TranslationUtils::getRegion('en_US_US'));
        $this->assertSame(null, TranslationUtils::getRegion('en_'));
        $this->assertEquals(null, TranslationUtils::getRegion(''));
        $this->assertSame(null, TranslationUtils::getRegion(null));
    }
}
