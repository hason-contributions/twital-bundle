<?php

namespace Goetas\TwitalBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
class GoetasTwitalExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('twital.xml');

        $loaderDefinition = $container->getDefinition('twital.loader');
        foreach ($config['source_adapters'] as $id => $regs) {
            foreach ($regs['pattern'] as $reg) {
                $loaderDefinition->addMethodCall('addSourceAdapter', array($reg, new Reference($id)));
            }
        }

        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['AsseticBundle'])) {
            $loader->load('assetic.xml');
        }

        if (isset($bundles['JMSTranslationBundle'])) {
            $loader->load('jms-translation-bundle.xml');
        }
    }
}
