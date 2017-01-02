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

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class MethodInjection extends AbstractInjection
{
    /**
     * Name of class method, which should be call and pass the object to inject.
     *
     * @var string
     */
    private $injectionMethodName;

    /**
     * @param string $injectionMethodName The method, which will be called for inject object.
     * @param string $type                Class-name to instantiate.
     */
    public function __construct(string $injectionMethodName, string $type)
    {
        $this->injectionMethodName = $injectionMethodName;
        parent::__construct($type);
    }

    /**
     * @return string
     */
    public function getInjectionName(): string
    {
        return $this->injectionMethodName;
    }

    /**
     * Inject given value in given object.
     *
     * @param object $object The object, in which should the value injected.
     * @param object $value  The value to inject in given object.
     */
    public function inject($object, $value)
    {
        call_user_func([$object, $this->injectionMethodName], $value);
    }
}
