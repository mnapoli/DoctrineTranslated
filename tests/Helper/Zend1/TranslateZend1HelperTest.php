<?php

namespace Test\Mnapoli\Translated\Helper\Zend1;

use Mnapoli\Translated\Helper\Zend1\TranslateZend1Helper;
use Test\Mnapoli\Translated\Fixture\TranslatedString;

/**
 * @covers \Mnapoli\Translated\Helper\Zend1\TranslateZend1Helper
 */
class TranslateZend1HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorNoParameters()
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
