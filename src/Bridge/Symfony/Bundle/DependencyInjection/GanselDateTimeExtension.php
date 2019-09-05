<?php

declare(strict_types=1);

namespace Gansel\DateTime\Bridge\Symfony\Bundle\DependencyInjection;

use Gansel\DateTime\Bridge\Doctrine\DBAL\Types\DateTimeImmutableUtcType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GanselDateTimeExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $config = [];
        $config['dbal']['types'][DateTimeImmutableUtcType::NAME]['class'] = DateTimeImmutableUtcType::class;

        $container->prependExtensionConfig('doctrine', $config);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
