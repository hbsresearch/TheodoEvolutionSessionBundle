<?php

namespace Theodo\Evolution\Bundle\SessionBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Theodo\Evolution\Bundle\SessionBundle\DependencyInjection\TheodoEvolutionSessionExtension;

class TheodoEvolutionSessionExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return array(
            new TheodoEvolutionSessionExtension()
        );
    }

    /**
     * @dataProvider getConfiguration
     */
    public function testConfiguration($config)
    {
        $this->load($config);

        $this->assertContainerBuilderHasService('theodo_evolution.session.bag_manager');
    }

    public function getConfiguration()
    {
        return [
            'default' => [
                [
                    'bag_manager' => ['class' => 'TestClass', 'configuration_class' => 'TestConfigurationClass'],
                    'bag_manager_service' => 'foo',
                    'bag_configuration_service' => 'bar'
                ]
            ]
        ];
    }

    /**
     * @dataProvider getInvalidConfiguration
     */
    public function testInvalidConfiguration($config)
    {
        $this->expectException(\InvalidArgumentException::class);
        $parser = new Parser();
        $config = $parser->parse($config);

        $builder = new ContainerBuilder();
        $extension = new TheodoEvolutionSessionExtension();
        $extension->load(array($config), $builder);
    }

    public function getInvalidConfiguration()
    {
        return array(
            array(""),
            array(<<<YML
bag_manager_service:
bag_configuration_service:
bag_manager:
    class:
    configuration_class:

YML
            )
        );
    }
}
