<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationContext;
use Test\Mnapoli\Translated\Fixture\MyTranslatedString;

/**
 * @covers \Mnapoli\Translated\TranslatedString
 */
class TranslatedStringTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorNoParameters()
    {
        $str = new MyTranslatedString();
        $this->assertNull($str->get('en'));
    }

    public function testConstructorParameters()
    {
        $str = new MyTranslatedString('foo', 'en');
        $this->assertEquals('foo', $str->get('en'));
    }

    public function testSetGet()
    {
        $str = new MyTranslatedString();

        $str->set('foo', 'en');
        $this->assertEquals('foo', $str->get('en'));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage If you provide a translation, you must provide a language
     */
    public function testConstructorBadParameters()
    {
        new MyTranslatedString('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no language "foobar" defined
     */
    public function testConstructorUnknownLanguage()
    {
        new MyTranslatedString('foo', 'foobar');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no language "foobar" defined
     */
    public function testSetUnknownLanguage()
    {
        $str = new MyTranslatedString();

        $str->set('foo', 'foobar');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no language "foobar" defined
     */
    public function testGetUnknownLanguage()
    {
        $str = new MyTranslatedString();

        $str->get('foobar');
    }
}
