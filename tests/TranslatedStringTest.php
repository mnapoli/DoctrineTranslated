<?php

namespace Test\Mnapoli\Translated;

use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\AbstractTranslatedString
 */
class TranslatedStringTest extends \PHPUnit_Framework_TestCase
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

    public function testGetWithFallback()
    {
        $str = new TranslatedString('fou', 'fr');

        // No fallback
        $this->assertNull($str->get('en'));

        // One fallback
        $this->assertEquals('fou', $str->get('en', ['fr']));

        // Two fallbacks
        $this->assertEquals('fou', $str->get('en', ['de', 'fr']));

        // Two fallbacks, no result
        $str = new TranslatedString();
        $this->assertNull($str->get('en', ['de', 'fr']));
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

    public function testFromArray()
    {
        $str = TranslatedString::fromArray([
            'en' => 'Hello',
            'fr' => 'Bonjour',
        ]);

        $this->assertEquals('Hello', $str->get('en'));
        $this->assertEquals('Bonjour', $str->get('fr'));
        $this->assertNull($str->get('de'));
    }
}
