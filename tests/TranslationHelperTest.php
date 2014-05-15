<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationHelper;
use Mnapoli\Translated\Translator;
use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\TranslationHelper
 */
class TranslationHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $str = new TranslatedString();
        $str->set('foo', 'en');
        $str->set('fou', 'fr');

        $manager = new Translator('en');
        $helper = new TranslationHelper($manager);

        $this->assertEquals('foo', $helper->get($str));
    }

    public function testToStringWithFallback()
    {
        $str = new TranslatedString();
        $str->set('fou', 'fr');

        // No fallback
        $manager = new Translator('en');
        $helper = new TranslationHelper($manager);
        $this->assertNull($helper->get($str));

        // One fallback
        $manager = new Translator('en', ['en' => ['fr']]);
        $helper = new TranslationHelper($manager);
        $this->assertEquals('fou', $helper->get($str));

        // Two fallbacks
        $manager = new Translator('en', ['en' => ['de', 'fr']]);
        $helper = new TranslationHelper($manager);
        $this->assertEquals('fou', $helper->get($str));
    }

    public function testSet()
    {
        $str = new TranslatedString();

        $manager = new Translator('en');
        $helper = new TranslationHelper($manager);

        $returned = $helper->set($str, 'foo');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertNull($str->get('fr'));
        $this->assertSame($str, $returned);
    }

    public function testSetMany()
    {
        $str = new TranslatedString();

        $manager = new Translator('en');
        $helper = new TranslationHelper($manager);

        $returned = $helper->setMany($str, [
            'en' => 'foo',
            'fr' => 'fou',
        ]);

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('fou', $str->get('fr'));
        $this->assertSame($str, $returned);
    }
}
