<?php declare(strict_types = 1);
/**
 * This file is part of N86io/Di.
 *
 * N86io/Di is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * N86io/Di is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with N86io/Di or see <http://www.gnu.org/licenses/>.
 */

namespace N86io\Di;

use Doctrine\Common\Cache\Cache;
use Doctrine\Instantiator\Instantiator;
use N86io\Di\Definition\DefinitionFactory;
use N86io\Di\Exception\ContainerException;
use N86io\Di\Injection\InjectionInterface;
use N86io\Di\Injection\InjectionOverride;
use N86io\Di\Injection\InjectionOverrideInterface;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class Container implements ContainerInterface
{
    /**
     * @var SingletonContainerInterface
     */
    private static $singletonContainer;

    private function __construct()
    {
    }

    /**
     * Returns the instance of Container.
     *
     * @return ContainerInterface
     * @throws ContainerException
     */
    public static function getInstance(): ContainerInterface
    {
        if (!self::$singletonContainer) {
            throw new ContainerException(
                'Can\'t create container instance, because it isn\'t initialized.',
                1482861649
            );
        }

        return self::$singletonContainer->get(self::class);
    }

    /**
     * Initialize Container.
     *
     * @param Cache $cache        An cache instance for ClassResolver.
     * @param array $classMapping Class-mapping for override classes while instantiating.
     *
     * @throws ContainerException
     */
    public static function initialize(Cache $cache, array $classMapping = [])
    {
        if (self::$singletonContainer) {
            throw new ContainerException('Container is already initialized.', 1482861671);
        }
        self::$singletonContainer = (new SingletonContainer)
            ->set(new DefinitionFactory($cache))
            ->set((new ClassResolver)->addMappings($classMapping))
            ->set(new self);
    }

    /**
     * Returns true if container is already initialized, otherwise false.
     *
     * @return bool
     */
    public static function isInitialized(): bool
    {
        if (self::$singletonContainer) {
            return true;
        }

        return false;
    }

    /**
     * Instantiate specified class. To override injections while object building, just use \N86io\Di\InjectionOverride
     * and pass it as last parameter.
     *
     * @param string $className The class-name to instantiate.
     * @param array  $params    Parameters for pass to the constructor. If last parameter is instance of
     *                          \N86io\Di\InjectionOverrideInterface, it will not be passed to constructor.
     *
     * @return object
     */
    public function get(string $className, ...$params)
    {
        ClassInterfaceNameValidator::validate($className);
        $className = self::$singletonContainer->get(ClassResolver::class)->resolve($className);
        if (self::$singletonContainer->has($className)) {
            return self::$singletonContainer->get($className);
        }

        $injectionOverride = new InjectionOverride;
        if (($lastParam = end($params)) instanceof InjectionOverrideInterface) {
            $injectionOverride = $lastParam;
            reset($params);
            $params = array_slice($params, 0, count($params) - 1);
        }
        $definition = self::$singletonContainer->get(DefinitionFactory::class)->get($className);

        $instance = (new Instantiator)->instantiate($className);

        if ($definition->isSingleton()) {
            self::$singletonContainer->set($instance);
        }

        $this->inject($definition->getInjections(), $injectionOverride, $instance);

        if ($definition->hasConstructor()) {
            $instance->__construct(...$params);
        }

        return $instance;
    }

    /**
     * @param InjectionInterface[]       $injections
     * @param InjectionOverrideInterface $injectionOverride
     * @param object                     $instance
     *
     * @throws ContainerException
     */
    private function inject(array $injections, InjectionOverrideInterface $injectionOverride, $instance)
    {
        foreach ($injections as $injection) {
            $injectionType = $injection->getType();
            $injectionName = $injection->getInjectionName();
            $injectionValue = null;
            if ($injectionOverride->has($injectionName)) {
                $injectionValue = $injectionOverride->get($injectionName);
                if (!$injectionValue instanceof $injectionType) {
                    throw new ContainerException(
                        'Override should be instance of injection-type (' . $injectionType . ').',
                        1482861693
                    );
                }
            }
            $injectionValue = $injectionValue ?: $this->get($injectionType);
            $injection->inject($instance, $injectionValue);
        }
    }
}
