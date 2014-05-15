<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\Translator;
use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\Translator
 */
class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCurrentContext()
    {
        $manager = new Translator('en', [
            'fr' => ['en'],
        ]);

        $contextEn = $manager->setCurrentContext('en');
        $this->assertEquals('en', $contextEn->getLocale());
        $this->assertEquals([], $contextEn->getFallback());
        $this->assertSame($contextEn, $manager->getCurrentContext());

        $contextFr = $manager->setCurrentContext('fr');
        $this->assertEquals('fr', $contextFr->getLocale());
        $this->assertEquals(['en'], $contextFr->getFallback());
        $this->assertSame($contextFr, $manager->getCurrentContext());
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
