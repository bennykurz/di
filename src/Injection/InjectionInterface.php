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
interface InjectionInterface
{
    const METHOD_INJECTION = 1;

    const PROPERTY_INJECTION = 2;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getInjectionName(): string;

    /**
     * @param object $object
     * @param object $value
     */
    public function inject($object, $value);
}
