<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationContext;
use Test\Mnapoli\Translated\Fixture\MyTranslatedString;

/**
 * @covers \Mnapoli\Translated\TranslatedString
 */
class TranslatedStringTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGet()
    {
        $str = new MyTranslatedString();

        $str->set('foo', 'en');
        $this->assertEquals('foo', $str->get('en'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no locale "foobar" defined
     */
    public function testSetUnknownLanguage()
    {
        $str = new MyTranslatedString(new TranslationContext('en'));

        $str->set('foo', 'foobar');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no locale "foobar" defined
     */
    public function testGetUnknownLanguage()
    {
        $str = new MyTranslatedString(new TranslationContext('en'));

        $str->get('foobar');
    }
}
