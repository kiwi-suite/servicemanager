<?php
/**
 * kiwi-suite/servicemanager (https://github.com/kiwi-suite/servicemanager)
 *
 * @package kiwi-suite/servicemanager
 * @see https://github.com/kiwi-suite/servicemanager
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteMisc\ServiceManager;

class ComplexObject
{
    public function __construct(
        string $value1,
        ResolverTestObject $resolverTestObject,
        ResolverTestObjectScalar $value2,
        OwnDateTime $dateTime,
        \DateTimeInterface $value3,
        string $value4
    ) {
    }
}
