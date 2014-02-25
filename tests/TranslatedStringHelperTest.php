<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslatedStringHelper;
use Mnapoli\Translated\TranslationContext;
use Test\Mnapoli\Translated\Fixture\MyTranslatedString;

/**
 * @covers \Mnapoli\Translated\TranslatedStringHelper
 */
class TranslatedStringHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $str = new MyTranslatedString();
        $str->set('foo', 'en');
        $str->set('fou', 'fr');

        $helper = new TranslatedStringHelper(new TranslationContext('en'));

        $this->assertEquals('foo', $helper->toString($str));
        $this->assertEquals('foo', $helper->toString($str, 'en'));
        $this->assertEquals('fou', $helper->toString($str, 'fr'));
    }

    public function testToStringFallback()
    {
        $str = new MyTranslatedString();
        $str->set('fou', 'fr');

        $helper = new TranslatedStringHelper(new TranslationContext('en'));

        $this->assertNull($helper->toString($str));
        $this->assertNull($helper->toString($str, 'en'));

        $this->assertEquals('fou', $helper->toString($str, null, ['fr']));
        $this->assertEquals('fou', $helper->toString($str, 'en', ['fr']));

        $this->assertEquals('fou', $helper->toString($str, null, ['de', 'fr']));
        $this->assertEquals('fou', $helper->toString($str, 'en', ['de', 'fr']));
    }

    public function testSet()
    {
        $str = new MyTranslatedString();

        $helper = new TranslatedStringHelper(new TranslationContext('en'));
        $helper->set($str, 'foo', 'en');
        $helper->set($str, 'fou', 'fr');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('fou', $str->get('fr'));
    }

    public function testSetMany()
    {
        $str = new MyTranslatedString();

        $helper = new TranslatedStringHelper(new TranslationContext('en'));
        $helper->setMany($str, [
            'en' => 'foo',
            'fr' => 'fou',
        ]);

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('fou', $str->get('fr'));
    }
}
