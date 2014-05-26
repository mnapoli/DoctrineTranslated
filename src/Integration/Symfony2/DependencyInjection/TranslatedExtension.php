<?php

namespace Mnapoli\Translated\Integration\Symfony2\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TranslatedExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = [];
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (!isset($config['default_locale'])) {
            throw new \InvalidArgumentException('The "translated.default_locale" configuration option must be defined');
        }

        $container->setParameter('translated.default_locale', $config['default_locale']);
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }
}
