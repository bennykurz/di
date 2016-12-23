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
use N86io\Di\Exception\DefinitionFactoryException;
use N86io\Di\Injection\MethodInjection;
use N86io\Di\Injection\PropertyInjection;
use N86io\Di\Singleton;
use N86io\Reflection\DocComment;
use N86io\Reflection\ReflectionClass;
use N86io\Reflection\ReflectionMethod;

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
    protected $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Returns class definition. At first time it will be initially created and saved in cache.
     *
     * @param string $className
     *
     * @return DefinitionInterface
     * @throws DefinitionFactoryException
     */
    public function get($className): DefinitionInterface
    {
        if (!$this->cache->contains($className)) {
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

            $this->cache->save($className, $definition);

            return $definition;
        }

        return $this->cache->fetch($className);
    }

    /**
     * Add injections to definition defined in class-methods.
     *
     * @param DefinitionInterface $definition
     * @param ReflectionClass     $reflectionClass
     */
    private function addMethodInjections(DefinitionInterface $definition, ReflectionClass $reflectionClass)
    {
        $methods = $reflectionClass->getMethods();
        foreach ($methods as $method) {
            $docComment = $method->getParsedDocComment();
            if (($docComment->hasTag('inject') && substr($method->getName(), 0, 3) === 'set') ||
                substr($method->getName(), 0, 6) === 'inject'
            ) {
                $type = $this->getTypeFromParam($method);
                if ($type !== '') {
                    $injection = new MethodInjection($method->getName(), $type);
                    $definition->addInjection($injection);
                    continue;
                }
                $type = $this->getTypeFromDocComment($docComment, $method->getParameters()[0]->getName());
                $injection = new MethodInjection($method->getName(), $type);
                $definition->addInjection($injection);
            }
        }
    }

    /**
     * Get object type for injection from method parameter.
     *
     * @param ReflectionMethod $method
     *
     * @return string
     * @throws DefinitionFactoryException
     */
    private function getTypeFromParam(ReflectionMethod $method): string
    {
        if ($method->getNumberOfParameters() < 1 || $method->getNumberOfParameters() > 1) {
            throw new DefinitionFactoryException(
                'Invalid count of parameter from injection method, it should have only 1 parameter.',
                1482512265
            );
        }

        return (string)$method->getParameters()[0]->getType();
    }

    /**
     * Get object type for injection from doc-comment.
     *
     * @param DocComment $docComment
     * @param string     $parameterName
     *
     * @return string
     * @throws DefinitionFactoryException
     */
    private function getTypeFromDocComment(DocComment $docComment, string $parameterName): string
    {
        $paramTag = $docComment->getTagsByName('param');
        if (count($paramTag) < 1 || count($paramTag) > 1) {
            throw new DefinitionFactoryException(
                'Invalid count of param definitions in doc-comment.',
                1482512242
            );
        }
        $paramTagValue = ' ' . $paramTag[0] . ' ';
        if (strpos($paramTagValue, ' $' . $parameterName . ' ') === false) {
            throw new DefinitionFactoryException(
                'Can\'t found type-definition for $' . $parameterName . ' in doc-comment',
                1482512248
            );
        }

        return explode(' ', trim($paramTagValue))[0];
    }

    /**
     * Add injections to definition defined in class-properties.
     *
     * @param DefinitionInterface $definition
     * @param ReflectionClass     $reflectionClass
     */
    private function addPropertyInjections(DefinitionInterface $definition, ReflectionClass $reflectionClass)
    {
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $docComment = $property->getParsedDocComment();
            if ($docComment->hasTag('inject')) {
                $injection = new PropertyInjection($property->getName(), $docComment->getTagsByName('var')[0]);
                $definition->addInjection($injection);
            }
        }
    }
}
