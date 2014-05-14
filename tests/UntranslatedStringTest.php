<?php

namespace Test\Mnapoli\Translated;

use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\AbstractTranslatedString
 */
class UntranslatedStringTest extends \PHPUnit_Framework_TestCase
{
    public function testUntranslatedString()
    {
        $str = TranslatedString::untranslated('foo');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('foo', $str->get('fr'));

        $str->set('bar', 'fr');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('bar', $str->get('fr'));
    }

    public function testConcat()
    {
        $str = TranslatedString::untranslated('foo');

        $result = $str->concat('bar');

        $this->assertInstanceOf('Mnapoli\Translated\AbstractTranslatedString', $result);
        $this->assertEquals('foobar', $result->get('en'));
    }
}
