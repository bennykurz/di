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
namespace N86io\Di\Definition;

use Doctrine\Common\Cache\Cache;
use N86io\Di\Injection\InjectionFactory;
use N86io\Di\Singleton;
use N86io\Reflection\ReflectionClass;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class DefinitionFactory implements Singleton
{
    /**
     * Cache stores definitions.
     *
     * @var Cache
     */
    private $cache;

    /**
     * @var DefinitionInterface[]
     */
    private $arrayStorage = [];

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Override cache from DefinitionFactory. All cache-entries from old cache will not be transferred to new cache.
     *
     * @param Cache $cache
     */
    public function overrideCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Returns class definition. At first time it will be initially created and saved in cache.
     *
     * @param string $className
     *
     * @return DefinitionInterface
     */
    public function get(string $className): DefinitionInterface
    {
        if (isset($this->arrayStorage[$className])) {
            return $this->arrayStorage[$className];
        }

        if ($this->cache->contains($className)) {
            $definition = $this->cache->fetch($className);
            $this->arrayStorage[$className] = $definition;

            return $definition;
        }

        $definition = $this->buildDefinition($className);
        $this->arrayStorage[$className] = $definition;
        $this->cache->save($className, $definition);

        return $definition;
    }

    /**
     * @param string $className
     *
     * @return DefinitionInterface
     */
    private function buildDefinition(string $className): DefinitionInterface
    {
        $reflectionClass = new ReflectionClass($className);

        $interfaces = $reflectionClass->getInterfaceNames();
        $isSingleton = array_search(Singleton::class, $interfaces) !== false;
        $definition = new Definition(
            $className,
            $isSingleton ? Definition::SINGLETON : Definition::PROTOTYPE
        );

        $this->addPropertyInjections($definition, $reflectionClass);
        $this->addMethodInjections($definition, $reflectionClass);

        if ($reflectionClass->hasMethod('__construct')) {
            $definition->setConstructor(true);
        }

        return $definition;
    }

    /**
     * Add injections to definition defined in class-methods.
     *
     * @param DefinitionInterface $definition
     * @param ReflectionClass     $reflectionClass
     */
    private function addMethodInjections(DefinitionInterface $definition, ReflectionClass $reflectionClass)
    {
        $methodInjections = InjectionFactory::createMethodInjections($reflectionClass->getMethods());

        foreach ($methodInjections as $methodInjection) {
            $definition->addInjection($methodInjection);
        }
    }

    /**
     * Add injections to definition defined in class-properties.
     *
     * @param DefinitionInterface $definition
     * @param ReflectionClass     $reflectionClass
     */
    private function addPropertyInjections(DefinitionInterface $definition, ReflectionClass $reflectionClass)
    {
        $propertyInjections = InjectionFactory::createPropertyInjections($reflectionClass->getProperties());

        foreach ($propertyInjections as $propertyInjection) {
            $definition->addInjection($propertyInjection);
        }
    }
}
