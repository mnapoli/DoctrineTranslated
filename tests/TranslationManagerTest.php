<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\Translator;

/**
 * @covers \Mnapoli\Translated\TranslationManager
 */
class TranslationManagerTest extends \PHPUnit_Framework_TestCase
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
}
