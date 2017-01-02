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

use N86io\Di\Exception\InjectionOverrideException;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class InjectionOverride implements InjectionOverrideInterface
{
    /**
     * @var object[]
     */
    private $overrides;

    /**
     * Returns the requested injection override.
     *
     * @param string $injectionName
     *
     * @return object
     * @throws InjectionOverrideException
     */
    public function get(string $injectionName)
    {
        if (!$this->has($injectionName)) {
            throw new InjectionOverrideException(
                'Can\'t found injection-override called "' . $injectionName . '".',
                1482834853
            );
        }

        return $this->overrides[$injectionName];
    }

    /**
     * Returns true, if an injection-override with given name exist, otherwise false.
     *
     * @param string $injectionName
     *
     * @return bool
     */
    public function has(string $injectionName): bool
    {
        return isset($this->overrides[$injectionName]);
    }

    /**
     * Add an injection-override for a class building.
     *
     * @param string $injectionName
     * @param object $object
     *
     * @return InjectionOverrideInterface
     */
    public function add(string $injectionName, $object)
    {
        $this->isObject($object);
        $this->overrides[$injectionName] = $object;

        return $this;
    }

    /**
     * @param $object
     *
     * @throws InjectionOverrideException
     */
    private function isObject($object)
    {
        if (!is_object($object)) {
            throw new InjectionOverrideException('Injection value should be an object.', 1482827005);
        }
    }
}
