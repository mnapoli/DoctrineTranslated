<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationHelper;
use Mnapoli\Translated\TranslationContext;
use Test\Mnapoli\Translated\Fixture\MyTranslatedString;

/**
 * @covers \Mnapoli\Translated\TranslationHelper
 */
class TranslationHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $str = new MyTranslatedString();
        $str->set('foo', 'en');
        $str->set('fou', 'fr');

        $helper = new TranslationHelper(new TranslationContext('en'));

        $this->assertEquals('foo', $helper->toString($str));
    }

    public function testToStringWithFallback()
    {
        $str = new MyTranslatedString();
        $str->set('fou', 'fr');

        $helper = new TranslationHelper(new TranslationContext('en'));
        $this->assertNull($helper->toString($str));

        $helper = new TranslationHelper(new TranslationContext('en', ['fr']));
        $this->assertEquals('fou', $helper->toString($str));

        $helper = new TranslationHelper(new TranslationContext('en', ['de', 'fr']));
        $this->assertEquals('fou', $helper->toString($str));
    }

    public function testSet()
    {
        $str = new MyTranslatedString();

        $helper = new TranslationHelper(new TranslationContext('en'));
        $returned = $helper->set($str, 'foo');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertNull($str->get('fr'));
        $this->assertSame($str, $returned);
    }

    public function testSetMany()
    {
        $str = new MyTranslatedString();

        $helper = new TranslationHelper(new TranslationContext('en'));
        $returned = $helper->setMany($str, [
            'en' => 'foo',
            'fr' => 'fou',
        ]);

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('fou', $str->get('fr'));
        $this->assertSame($str, $returned);
    }
}
