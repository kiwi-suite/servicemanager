<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\ServiceManager\Autowire;

use Laminas\Di\Resolver\ValueInjection;

final class FactoryCode
{
    /**
     * @var string
     */
    private $template = <<<'EOD'
<?php
namespace Ixocreate\GeneratedFactory;

use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

final class %s implements FactoryInterface
{
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        %s
        return new \%s(%s);
    }
}

EOD;

    public function generateFactoryCode(string $instanceName, array $resolution): string
    {
        $factoryName = $this->generateFactoryName($instanceName);

        $checkParams = [];
        $constructParams = [];
        foreach ($resolution as $name => $injection) {
            if ($injection instanceof ContainerInjection) {
                $string = '$container->get(\'';
                if ($injection->getContainer() !== null) {
                    $string .= $injection->getContainer() . '\')->get(\'';
                }

                $string .= $injection->getType() . '\')';
                $constructParams[] = $string;
                continue;
            }

            if ($injection instanceof DefaultValueInjection) {
                $constructParams[] = \sprintf(
                    '(\is_array($options) && \array_key_exists(\'%s\', $options)) ? $options[\'%s\'] : %s',
                    $name,
                    $name,
                    $injection->export()
                );

                continue;
            }

            if (!($injection instanceof ValueInjection)) {
                //TODO Exception
            }

            $ifCheck = <<<'EOD'
if (!\is_array($options) || !\array_key_exists('%s', $options)) {
    throw new \Ixocreate\ServiceManager\Exception\InvalidArgumentException('Invalid option for %s');
}
EOD;
            $checkParams[] = \sprintf($ifCheck, $name, $name);
            $constructParams[] = \sprintf('$options[\'%s\']', $name);
        }

        return \sprintf(
            $this->template,
            $factoryName,
            \implode("\n", $checkParams),
            $instanceName,
            \implode(',', $constructParams)
        );
    }

    public function generateFactoryName(string $instanceName): string
    {
        return 'Factory' . \md5($instanceName);
    }

    public function generateFactoryFullQualifiedName(string $instanceName): string
    {
        return '\\Ixocreate\\GeneratedFactory\\' . $this->generateFactoryName($instanceName);
    }
}
