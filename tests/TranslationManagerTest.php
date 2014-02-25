<?php

namespace Test\Mnapoli\Translated;

use Mnapoli\Translated\TranslationManager;

/**
 * @covers \Mnapoli\Translated\TranslationManager
 */
class TranslationManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateContext()
    {
        $manager = new TranslationManager();

        $manager->setFallbacks([
            'fr' => ['en'],
        ]);

        $contextEn = $manager->createContext('en');
        $this->assertEquals('en', $contextEn->getLocale());
        $this->assertEquals([], $contextEn->getFallback());

        $contextFr = $manager->createContext('fr');
        $this->assertEquals('fr', $contextFr->getLocale());
        $this->assertEquals(['en'], $contextFr->getFallback());
    }
}
