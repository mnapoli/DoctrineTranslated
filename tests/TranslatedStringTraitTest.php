<?php

namespace Test\Mnapoli\Translated;

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

    public function testToArray()
    {
        $str = new TranslatedString('foo', 'en');

        $this->assertEquals([
            'en' => 'foo',
            'fr' => null,
            'de' => null,
        ], $str->toArray());
    }

    public function testConcat()
    {
        $str = new TranslatedString('foo', 'en');
        $str->set('abc', 'de');

        $result = $str->concat(
            ' - ',
            new TranslatedString('bar', 'en'),
            new TranslatedString('baz', 'fr'),
            null
        );

        $this->assertInstanceOf('Mnapoli\Translated\TranslatedStringInterface', $result);
        $this->assertEquals([
            'en' => 'foo - bar',
            'fr' => ' - baz',
            'de' => 'abc - ',
        ], $result->toArray());
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
