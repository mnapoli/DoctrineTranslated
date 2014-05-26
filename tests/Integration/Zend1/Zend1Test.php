<?php

namespace Test\Mnapoli\Translated\Integration\Zend1;

use Mnapoli\Translated\Integration\Zend1\TranslateZend1Helper;
use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\Integration\Zend1\TranslateZend1Helper
 */
class Zend1Test extends \PHPUnit_Framework_TestCase
{
    public function testTranslate()
    {
        $translator = $this->getMockBuilder('Mnapoli\Translated\Translator')
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects($this->once())
            ->method('get')
            ->will($this->returnValue('foo'));

        $helper = new TranslateZend1Helper($translator);

        $this->assertEquals('foo', $helper->translate(new TranslatedString()));
    }
}
