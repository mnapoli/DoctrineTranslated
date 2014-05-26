<?php

namespace Test\Mnapoli\Translated\Helper\Twig;

use Mnapoli\Translated\Helper\Twig\TranslateTwigHelper;
use Mnapoli\Translated\Translator;
use Test\Mnapoli\Translated\Fixture\TranslatedString;
use Twig_Environment;
use Twig_Loader_String;

/**
 * @covers \Mnapoli\Translated\Helper\Twig\TranslateTwigHelper
 */
class TranslateTwigHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testFunctional()
    {
        $translator = new Translator('fr');

        $twig = new Twig_Environment(new Twig_Loader_String());
        $twig->addExtension(new TranslateTwigHelper($translator));

        $string = new TranslatedString('foo', 'en');
        $string->fr = 'bar';
        $result = $twig->render('Hello {{ name|translate }}!', ['name' => $string]);

        $this->assertEquals('Hello bar!', $result);
    }

    public function testTranslate()
    {
        $translator = $this->getMockBuilder('Mnapoli\Translated\Translator')
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects($this->once())
            ->method('get')
            ->will($this->returnValue('foo'));

        $helper = new TranslateTwigHelper($translator);

        $this->assertEquals('foo', $helper->translate(new TranslatedString()));
    }
}
