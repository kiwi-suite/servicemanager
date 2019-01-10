<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\ServiceManager;

final class OriginalServiceManager extends \Zend\ServiceManager\ServiceManager
{
    /**
     * OriginalServiceManager constructor.
     * @param ServiceManager $serviceManager
     * @param array $config
     */
    public function __construct(ServiceManager $serviceManager, array $config = [])
    {
        parent::__construct($config);
        $this->creationContext = $serviceManager;
    }
}
