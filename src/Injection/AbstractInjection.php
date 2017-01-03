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

use N86io\Di\ClassInterfaceNameValidator;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
abstract class AbstractInjection implements InjectionInterface
{
    /**
     * Class-name of object to inject.
     *
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        ClassInterfaceNameValidator::validate($type);
        $this->type = $type;
    }

    /**
     * Returns class-name of injection.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
