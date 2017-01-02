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

namespace N86io\Di\Injection;

use N86io\Reflection\ReflectionClass;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class PropertyInjection extends AbstractInjection
{
    /**
     * Name of class-property, in which the object should be inject.
     *
     * @var string
     */
    private $propertyName;

    /**
     * PropertyInjection constructor.
     *
     * @param string $propertyName The name of property, in which the object should injected.
     * @param string $type         Class-name to instantiate.
     */
    public function __construct(string $propertyName, string $type)
    {
        $this->propertyName = $propertyName;
        parent::__construct($type);
    }

    /**
     * @return string
     */
    public function getInjectionName(): string
    {
        return $this->propertyName;
    }

    /**
     * Inject given value in given object.
     *
     * @param object $object The object, in which should the value injected.
     * @param object $value  The value to inject in given object.
     */
    public function inject($object, $value)
    {
        $reflectionProperty = (new ReflectionClass($object))
            ->getProperty($this->propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }
}
