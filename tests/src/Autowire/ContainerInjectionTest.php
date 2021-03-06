<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\ServiceManager\Autowire;

use Ixocreate\ServiceManager\Autowire\ContainerInjection;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerInjectionTest extends TestCase
{
    public function testContainerInjection()
    {
        $injection = new ContainerInjection('type', 'container');

        $container = $this->createMock(ContainerInterface::class);

        $this->assertSame('type', $injection->getType());
        $this->assertSame('container', $injection->getContainer());
        $this->assertSame('type', $injection->toValue($container));
        $this->assertFalse($injection->isExportable());
        $this->assertSame('', $injection->export());
    }
}
