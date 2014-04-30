<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationContext;
use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\TranslatedStringTrait
 */
class TranslatedStringTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorNoParameters()
    {
        $str = new TranslatedString();
        $this->assertNull($str->get('en'));
    }

    public function testConstructorParameters()
    {
        $str = new TranslatedString('foo', 'en');
        $this->assertEquals('foo', $str->get('en'));
    }

    public function testSetGet()
    {
        $str = new TranslatedString();

        $str->set('foo', 'en');
        $this->assertEquals('foo', $str->get('en'));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage If you provide a translation, you must provide a language
     */
    public function testConstructorBadParameters()
    {
        new TranslatedString('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no language "foobar" defined
     */
    public function testConstructorUnknownLanguage()
    {
        new TranslatedString('foo', 'foobar');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no language "foobar" defined
     */
    public function testSetUnknownLanguage()
    {
        $str = new TranslatedString();

        $str->set('foo', 'foobar');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is no language "foobar" defined
     */
    public function testGetUnknownLanguage()
    {
        $str = new TranslatedString();

        $str->get('foobar');
    }
}
