<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\UntranslatedString;

/**
 * @covers \Mnapoli\Translated\UntranslatedString
 */
class UntranslatedStringTest extends \PHPUnit_Framework_TestCase
{
    public function testUntranslatedString()
    {
        $str = new UntranslatedString('foo');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('foo', $str->get('fr'));

        $str->set('bar', 'whatever');

        $this->assertEquals('bar', $str->get('en'));
        $this->assertEquals('bar', $str->get('fr'));
    }

    public function testConcat()
    {
        $str = new UntranslatedString('foo');

        $result = $str->concat('bar');

        $this->assertInstanceOf('Mnapoli\Translated\TranslatedStringInterface', $result);
        $this->assertEquals('foobar', $result->get('en'));
    }
}
