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

use N86io\Di\Exception\ClassResolverException;

/**
 * Class mapper for mapping given classes to configured target class.
 *
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class ClassResolver implements ClassResolverInterface
{
    /**
     * Class mappings.
     *
     * @var string[]
     */
    private $mappings = [];

    /**
     * @param string $sourceClass
     *
     * @return string
     */
    public function resolve(string $sourceClass): string
    {
        ClassInterfaceNameValidator::validate($sourceClass);
        $sourceClass = $this->resolveInterface($sourceClass);
        $targetClass = $this->map($sourceClass);

        return $targetClass;
    }

    /**
     * Add mapping.
     *
     * @param string $sourceClass Source class, from which class should map.
     * @param string $targetClass Target class, to which class should map.
     *
     * @return ClassResolver
     * @throws ClassResolverException
     */
    public function addMapping(string $sourceClass, string $targetClass): ClassResolver
    {
        ClassInterfaceNameValidator::validate($sourceClass);
        ClassInterfaceNameValidator::validate($targetClass);
        if (!is_subclass_of($targetClass, $sourceClass)) {
            throw new ClassResolverException(
                '"' . $targetClass . '" should be a subclass of "' . $sourceClass . '".',
                1482825204
            );
        }
        $this->mappings[$sourceClass] = $targetClass;

        return $this;
    }

    /**
     * Add array of mappings.
     *
     * @param string[] $mappings Key should be source class and value target class.
     *
     * @return ClassResolver
     * @see self::addMapping()
     */
    public function addMappings(array $mappings): ClassResolver
    {
        foreach ($mappings as $sourceClass => $targetClass) {
            $this->addMapping($sourceClass, $targetClass);
        }

        return $this;
    }

    /**
     * @param string $className
     *
     * @return string
     * @throws ClassResolverException
     */
    private function resolveInterface(string $className): string
    {
        if (interface_exists($className)) {
            $interfaceName = $className;
            if ($this->hasMap($interfaceName)) {
                return $interfaceName;
            }

            $className = substr($interfaceName, 0, strlen($interfaceName) - 9);
            if (class_exists($className) && is_subclass_of($className, $interfaceName)) {
                return $className;
            }

            throw new ClassResolverException('Can\'t resolve interface "' . $interfaceName . '".', 1482825227);
        }

        return $className;
    }

    /**
     * Map from a class to configured target class.
     *
     * @param string $sourceClass Source class, from which class should map.
     *
     * @return string
     */
    private function map(string $sourceClass): string
    {
        if ($this->hasMap($sourceClass)) {
            return $this->map($this->mappings[$sourceClass]);
        }

        return $sourceClass;
    }

    /**
     * Returns true, if a map for given class exist, otherwise false.
     *
     * @param string $sourceClass
     *
     * @return bool
     */
    private function hasMap(string $sourceClass): bool
    {
        return isset($this->mappings[$sourceClass]);
    }
}
