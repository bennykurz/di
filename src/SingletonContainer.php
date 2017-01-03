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

use N86io\Di\Exception\SingletonContainerException;
use Webmozart\Assert\Assert;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class SingletonContainer implements SingletonContainerInterface
{
    /**
     * @var object[]
     */
    private $singletons;

    /**
     * Get an instance of class with given class-name.
     *
     * @param string $className
     *
     * @return object
     * @throws SingletonContainerException
     */
    public function get(string $className)
    {
        if (!$this->has($className)) {
            throw new SingletonContainerException('Singleton instance of "' . $className . '" doesn\'t exist.');
        }

        return $this->singletons[$className];
    }

    /**
     * Returns true, if container can return an instance of given class-name. Otherwise false.
     *
     * @param string $className
     *
     * @return bool
     */
    public function has(string $className): bool
    {
        Assert::classExists($className);
        ClassInterfaceNameValidator::validate($className);

        return !empty($this->singletons[$className]);
    }

    /**
     * Add the object to the container.
     *
     * @param object $object
     *
     * @return SingletonContainerInterface
     * @throws SingletonContainerException
     */
    public function set($object): SingletonContainerInterface
    {
        Assert::object($object);
        $className = get_class($object);
        if ($this->has($className)) {
            throw  new SingletonContainerException('Can\'t override singleton instance of "' . $className . '".');
        }
        $this->singletons[$className] = $object;

        return $this;
    }
}
