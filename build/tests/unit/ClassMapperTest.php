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

namespace N86io\Di\Tests\Unit;

use N86io\Di\ClassMapper;
use N86io\Di\Instantiator;
use N86io\Di\ObjectFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 */
class ClassMapperTest extends TestCase
{
    public function test()
    {
        $classMapper = new ClassMapper;

        $classMapper->addMappings([
            self::class        => ClassMapper::class,
            ClassMapper::class => Instantiator::class
        ]);
        $this->assertEquals(Instantiator::class, $classMapper->map(self::class));
        $this->assertEquals(Instantiator::class, $classMapper->map(ClassMapper::class));

        $classMapper->addMapping(Instantiator::class, ObjectFactory::class);
        $this->assertEquals(ObjectFactory::class, $classMapper->map(self::class));
        $this->assertEquals(ObjectFactory::class, $classMapper->map(ClassMapper::class));
        $this->assertEquals(ObjectFactory::class, $classMapper->map(Instantiator::class));
    }
}
