<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\Translator;
use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\Translator
 */
class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCurrentLocale()
    {
        $translator = new Translator('en', [
            'fr' => ['en'],
        ]);

        $this->assertEquals('en', $translator->getLanguage());

        $translator->setLanguage('fr');
        $this->assertEquals('fr', $translator->getLanguage());

        $translator->setLanguage('en_US');
        $this->assertEquals('en', $translator->getLanguage());
    }

    public function testGetFallbacks()
    {
        $translator = new Translator('en', [
            'fr' => ['en'],
        ]);

        $this->assertEquals([], $translator->getFallbacks('en'));
        $this->assertEquals(['en'], $translator->getFallbacks('fr'));
    }

    public function testGet()
    {
        $str = new TranslatedString();
        $str->set('foo', 'en');
        $str->set('fou', 'fr');

        $translator = new Translator('en');

        $this->assertEquals('foo', $translator->get($str));
    }

    public function testGetWithFallback()
    {
        $str = new TranslatedString();
        $str->set('fou', 'fr');

        // No fallback
        $translator = new Translator('en');
        $this->assertNull($translator->get($str));

        // One fallback
        $translator = new Translator('en', ['en' => ['fr']]);
        $this->assertEquals('fou', $translator->get($str));

        // Two fallbacks
        $translator = new Translator('en', ['en' => ['de', 'fr']]);
        $this->assertEquals('fou', $translator->get($str));
    }

    public function testSet()
    {
        $str = new TranslatedString();

        $translator = new Translator('en');

        $returned = $translator->set($str, 'foo');

        $this->assertEquals('foo', $str->get('en'));
        $this->assertNull($str->get('fr'));
        $this->assertSame($str, $returned);
    }

    public function testSetMany()
    {
        $str = new TranslatedString();

        $translator = new Translator('en');

        $returned = $translator->setMany($str, [
            'en' => 'foo',
            'fr' => 'fou',
        ]);

        $this->assertEquals('foo', $str->get('en'));
        $this->assertEquals('fou', $str->get('fr'));
        $this->assertSame($str, $returned);
    }
}
