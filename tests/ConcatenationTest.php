<?php

namespace Test\Mnapoli\Translated;

use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\AbstractTranslatedString
 * @covers \Mnapoli\Translated\StringConcatenation
 */
class ConcatenationTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleConcat()
    {
        $str = new TranslatedString('foo', 'en');
        $str->set('abc', 'de');

        $result = $str->concat(
            ' - ',
            new TranslatedString('bar', 'en'),
            new TranslatedString('baz', 'fr'),
            null
        );

        $this->assertInstanceOf('Mnapoli\Translated\AbstractTranslatedString', $result);
        $this->assertEquals('foo - bar', $result->get('en'));
        $this->assertEquals(' - baz', $result->get('fr'));
        $this->assertEquals('abc - ', $result->get('de'));
    }

    /**
     * @test
     */
    public function concatenationShouldHappenWithOriginalValues()
    {
        $str = new TranslatedString('foo', 'en');
        $concat = $str->concat(' bar');

        // If we modify the original translated string
        $str->set('Hello', 'en');

        // Then the concatenation shouldn't be affected
        $this->assertEquals('foo bar', $concat->get('en'));
    }

    public function testConcatenationChaining()
    {
        $str1 = new TranslatedString('foo', 'en');
        $str2 = $str1->concat(' bar');
        $str3 = $str2->concat(' baz');

        $this->assertEquals('foo bar baz', $str3->get('en'));
        $this->assertEquals(' bar baz', $str3->get('fr'));
    }

    public function testOverrideValue()
    {
        $str1 = new TranslatedString('foo', 'en');
        $str2 = $str1->concat(' bar');

        $str2->set('bonjour', 'fr');

        $this->assertEquals('foo bar', $str2->get('en'));
        $this->assertEquals('bonjour', $str2->get('fr'));
    }

    public function testJoin()
    {
        $str = TranslatedString::join(['foo', 'bar']);

        $this->assertEquals('foobar', $str->get('en'));
    }

    public function testImplode()
    {
        $str = TranslatedString::implode(' ', []);
        $this->assertEquals('', $str->get('en'));

        $str = TranslatedString::implode(' ', ['foo']);
        $this->assertEquals('foo', $str->get('en'));

        $str = TranslatedString::implode(' ', ['foo', 'bar']);
        $this->assertEquals('foo bar', $str->get('en'));
    }
}
