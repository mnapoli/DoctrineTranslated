<?php

namespace Test\Mnapoli\Translated\Integration\Symfony2;

use Mnapoli\Translated\Integration\Symfony2\DependencyInjection\TranslatedExtension;
use Mnapoli\Translated\Integration\Twig\TranslatedTwigExtension;
use Mnapoli\Translated\Translator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @covers \Mnapoli\Translated\Integration\Symfony2\TranslatedBundle
 * @covers \Mnapoli\Translated\Integration\Symfony2\DependencyInjection\TranslatedExtension
 */
class Symfony2Test extends \PHPUnit_Framework_TestCase
{
    public function testConfigDefaultLocale()
    {
        $container = $this->getContainer();
        $extension = new TranslatedExtension();

        $container->registerExtension($extension);

        $config = [
            [
                'default_locale' => 'fr',
            ],
        ];
        $extension->load($config, $container);

        $this->assertEquals('fr', $container->getParameter('translated.default_locale'));
        $this->assertEquals('fr', $container->get('translated.translator')->getLanguage());
    }

    public function testTranslator()
    {
        $container = $this->getContainer();
        $extension = new TranslatedExtension();

        $container->registerExtension($extension);

        $config = [
            [
                'default_locale' => 'fr',
            ],
        ];
        $extension->load($config, $container);

        $this->assertTrue($container->get('translated.translator') instanceof Translator);
        $this->assertEquals('fr', $container->get('translated.translator')->getLanguage());
    }

    public function testTwigExtension()
    {
        $container = $this->getContainer();
        $extension = new TranslatedExtension();

        $container->registerExtension($extension);

        $config = [
            [
                'default_locale' => 'fr',
            ],
        ];
        $extension->load($config, $container);

        $this->assertTrue($container->get('translated.twig_extension') instanceof TranslatedTwigExtension);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The "translated.default_locale" configuration option must be defined
     */
    public function testMandatoryDefaultLocale()
    {
        $container = $this->getContainer();
        $extension = new TranslatedExtension();

        $container->registerExtension($extension);

        $config = [
            [],
        ];
        $extension->load($config, $container);
    }

    private function getContainer()
    {
        return new ContainerBuilder(new ParameterBag(array(
            'kernel.debug'       => false,
            'kernel.bundles'     => [],
            'kernel.cache_dir'   => sys_get_temp_dir(),
            'kernel.environment' => 'test',
            'kernel.root_dir'    => __DIR__
        )));
    }
}
