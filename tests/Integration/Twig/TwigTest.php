<?php

namespace Test\Mnapoli\Translated\Integration\Twig;

use Mnapoli\Translated\Integration\Twig\TranslatedTwigExtension;
use Mnapoli\Translated\Translator;
use Test\Mnapoli\Translated\Fixture\TranslatedString;
use Twig_Environment;
use Twig_Loader_String;

/**
 * @covers \Mnapoli\Translated\Integration\Twig\TranslatedTwigExtension
 */
class TwigTest extends \PHPUnit_Framework_TestCase
{
    public function testFunctional()
    {
        $translator = new Translator('fr');

        $twig = new Twig_Environment(new Twig_Loader_String());
        $twig->addExtension(new TranslatedTwigExtension($translator));

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

        $helper = new TranslatedTwigExtension($translator);

        $this->assertEquals('foo', $helper->translate(new TranslatedString()));
    }
}
