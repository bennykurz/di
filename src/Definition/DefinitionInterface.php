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

use N86io\Di\Injection\InjectionInterface;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
interface DefinitionInterface
{
    const SINGLETON = 0;

    const PROTOTYPE = 1;

    /**
     * Returns class-name of defined class.
     *
     * @return string
     */
    public function getClassName(): string;

    /**
     * Returns true, if class is a singleton. If is prototype, returns false.
     *
     * @return bool
     */
    public function isSingleton(): bool;

    /**
     * Returns true, if class has a constructor, otherwise false.
     *
     * @return bool
     */
    public function hasConstructor(): bool;

    /**
     * Set if class has a constructor.
     *
     * @param bool $constructor
     *
     * @return DefinitionInterface
     */
    public function setConstructor(bool $constructor): DefinitionInterface;

    /**
     * Returns all injections.
     *
     * @return InjectionInterface[]
     */
    public function getInjections(): array;

    /**
     * Add an injection.
     *
     * @param InjectionInterface $injection
     *
     * @return DefinitionInterface
     */
    public function addInjection(InjectionInterface $injection): DefinitionInterface;
}
