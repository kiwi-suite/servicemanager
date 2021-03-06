<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\ServiceManager;

use Ixocreate\ServiceManager\FactoryInterface;

class ResolverTestObjectNoDep
{
    /**
     * ResolverTestObject constructor.
     *
     * @param \DateTime $dateTime
     * @param \DateTimeInterface $test1
     */
    public function __construct(\DateTime $dateTime, FactoryInterface $test_doesnt_exist)
    {
    }
}
