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

use N86io\Di\Exception\ClassInterfaceNameException;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class ClassInterfaceNameValidator
{
    /**
     * @param string $classInterfaceName
     *
     * @throws ClassInterfaceNameException
     */
    public static function validate(string $classInterfaceName)
    {
        if (!class_exists($classInterfaceName) && !interface_exists($classInterfaceName)) {
            throw new ClassInterfaceNameException(
                'Class or interface "' . $classInterfaceName . '" doesn\'t exist.',
                1483459227
            );
        }

        if (substr($classInterfaceName, 0, 1) === '\\') {
            throw new ClassInterfaceNameException(
                'Class or interface name must not begin with "\\". Got: ' . $classInterfaceName,
                1483459213
            );
        }
    }
}
