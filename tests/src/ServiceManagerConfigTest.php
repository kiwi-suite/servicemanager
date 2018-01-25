<?php
/**
 * kiwi-suite/servicemanager (https://github.com/kiwi-suite/servicemanager)
 *
 * @package kiwi-suite/servicemanager
 * @see https://github.com/kiwi-suite/servicemanager
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\ServiceManager;

use KiwiSuite\ServiceManager\Exception\InvalidArgumentException;
use KiwiSuite\ServiceManager\Factory\AutowireFactory;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
use KiwiSuiteMisc\ServiceManager\CantCreateObjectFactory;
use KiwiSuiteMisc\ServiceManager\DateTimeFactory;
use KiwiSuiteMisc\ServiceManager\DelegatorFactory;
use KiwiSuiteMisc\ServiceManager\Initializer;
use KiwiSuiteMisc\ServiceManager\LazyLoadingObject;
use KiwiSuiteMisc\ServiceManager\SubManagerFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\Proxy\LazyServiceFactory;

class ServiceManagerConfigTest extends TestCase
{
    public function testGetFactories()
    {
        $factories = [];
        $serviceManagerConfig = new ServiceManagerConfig($factories);
        $this->assertEquals($factories, $serviceManagerConfig->getFactories());

        $factories = [
            'test' => DateTimeFactory::class,
        ];
        $serviceManagerConfig = new ServiceManagerConfig($factories);
        $this->assertEquals($factories, $serviceManagerConfig->getFactories());
    }

    public function testGetSubManagers()
    {
        $subManagers = [];
        $serviceManagerConfig = new ServiceManagerConfig([], $subManagers);
        $this->assertEquals($subManagers, $serviceManagerConfig->getSubManagers());

        $subManagers = [
            'test' => SubManagerFactory::class,
        ];
        $serviceManagerConfig = new ServiceManagerConfig([], $subManagers);
        $this->assertEquals($subManagers, $serviceManagerConfig->getSubManagers());
    }

    public function testDelegators()
    {
        $delegators = [];
        $serviceManagerConfig = new ServiceManagerConfig([], [], $delegators);
        $this->assertEquals($delegators, $serviceManagerConfig->getDelegators());

        $delegators = [
            'test' => [DelegatorFactory::class],
        ];
        $serviceManagerConfig = new ServiceManagerConfig([], [], $delegators);
        $this->assertEquals($delegators, $serviceManagerConfig->getDelegators());
    }

    public function testGetLazyServices()
    {
        $lazyServices = [];
        $serviceManagerConfig = new ServiceManagerConfig([], [], [], $lazyServices);
        $this->assertEquals($lazyServices, $serviceManagerConfig->getLazyServices());

        $lazyServices = [
            'test' => \DateTime::class,
        ];
        $serviceManagerConfig = new ServiceManagerConfig([], [], [], $lazyServices);
        $this->assertEquals($lazyServices, $serviceManagerConfig->getLazyServices());
    }

    public function testGetDisabledSharing()
    {
        $disabledSharing = [];
        $serviceManagerConfig = new ServiceManagerConfig([], [], [], [], $disabledSharing);
        $this->assertEquals($disabledSharing, $serviceManagerConfig->getDisabledSharing());

        $disabledSharing = [
            \DateTime::class,
        ];
        $serviceManagerConfig = new ServiceManagerConfig([], [], [], [], $disabledSharing);
        $this->assertEquals($disabledSharing, $serviceManagerConfig->getDisabledSharing());
    }

    public function testGetInitializers()
    {
        $initializers = [];
        $serviceManagerConfig = new ServiceManagerConfig([], [], [], [], [], $initializers);
        $this->assertEquals($initializers, $serviceManagerConfig->getInitializers());

        $initializers = [
            'test' => Initializer::class,
        ];
        $serviceManagerConfig = new ServiceManagerConfig([], [], [], [], [], $initializers);
        $this->assertEquals($initializers, $serviceManagerConfig->getInitializers());
    }

    public function testInvalidFactoryString()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => [],
        ];

        new ServiceManagerConfig($items);
    }

    public function testInvalidFactoryInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => "test",
        ];

        new ServiceManagerConfig($items);
    }

    public function testInvalidFactoryImplements()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => Initializer::class,
        ];

        new ServiceManagerConfig($items);
    }

    public function testInvalidSubManagersString()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => [],
        ];

        new ServiceManagerConfig([], $items);
    }

    public function testInvalidSubManagersInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => "test",
        ];

        new ServiceManagerConfig([], $items);
    }

    public function testInvalidSubManagersImplements()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => Initializer::class,
        ];

        new ServiceManagerConfig([], $items);
    }

    public function testInvalidInitializerString()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            [],
        ];

        new ServiceManagerConfig([], [], [], [], [], $items);
    }

    public function testInvalidInitializerInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            "test",
        ];

        new ServiceManagerConfig([], [], [], [], [], $items);
    }

    public function testInvalidInitializerImplements()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            SubManagerFactory::class,
        ];

        new ServiceManagerConfig([], [], [], [], [], $items);
    }

    public function testInvalidDelegatorFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => "test",
        ];
        new ServiceManagerConfig([], [], $items);
    }

    public function testInvalidDelegatorStringFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => [new \DateTime()],
        ];
        new ServiceManagerConfig([], [], $items);
    }

    public function testInvalidDelegatorCantLoadFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => ["test"],
        ];
        new ServiceManagerConfig([], [], $items);
    }

    public function testInvalidDelegatorImplementsFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $items = [
            'test' => [DateTimeFactory::class],
        ];
        new ServiceManagerConfig([], [], $items);
    }

    public function testSerialize()
    {
        $serviceManagerConfig = new ServiceManagerConfig([
            'test' => DateTimeFactory::class,
        ]);

        $this->assertEquals(\serialize([
            'factories' => [
                'test' => DateTimeFactory::class,
            ],
            'subManagers' => [],
            'delegators' => [],
            'lazyServices' => [],
            'disabledSharing' => [],
            'initializers' => [],
        ]), $serviceManagerConfig->serialize());
    }

    public function testUnserialize()
    {
        $items = [
            'factories' => [
                'test' => DateTimeFactory::class,
            ],
        ];

        $serviceManagerConfig = new ServiceManagerConfig();
        $serviceManagerConfig->unserialize(\serialize($items));
        $this->assertEquals($items['factories'], $serviceManagerConfig->getFactories());
    }

    public function testGetConfig()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addFactory(LazyLoadingObject::class);
        $serviceManagerConfigurator->addFactory('dateTime', DateTimeFactory::class);
        $serviceManagerConfigurator->addFactory('cantCreate', CantCreateObjectFactory::class);

        $serviceManagerConfigurator->addLazyService(LazyLoadingObject::class);

        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();

        $this->assertEquals([
            'factories' => [
                LazyLoadingObject::class => AutowireFactory::class,
                'dateTime' => DateTimeFactory::class,
                "cantCreate" => CantCreateObjectFactory::class,
            ],
            'delegators' => [
                LazyLoadingObject::class => [LazyServiceFactory::class],
            ],
            'shared' => [],
            'initializers' => [],
            'shared_by_default' => true,
        ], $serviceManagerConfig->getConfig());
    }
}
