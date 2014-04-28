<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationManager;

/**
 * @covers \Mnapoli\Translated\TranslationManager
 */
class TranslationManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCurrentContext()
    {
        $manager = new TranslationManager();

        $manager->setFallbacks([
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
