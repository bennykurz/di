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

namespace N86io\Di\Tests\Unit\Definition;

use N86io\Di\Definition\Definition;
use N86io\Di\Definition\DefinitionInterface;
use N86io\Di\Injection\InjectionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class DefinitionTest extends TestCase
{
    public function test()
    {
        $definition = new Definition(self::class, DefinitionInterface::PROTOTYPE);
        $definition->setConstructor(false);
        /** @var InjectionInterface $injection */
        $injection = \Mockery::mock(InjectionInterface::class);
        $definition->addInjection($injection);

        $this->assertEquals(self::class, $definition->getClassName());
        $this->assertFalse($definition->isSingleton());
        $this->assertFalse($definition->hasConstructor());
        $this->assertEquals([$injection], $definition->getInjections());


        $definition = new Definition(self::class, DefinitionInterface::SINGLETON);
        $definition->setConstructor(true);

        $this->assertEquals(self::class, $definition->getClassName());
        $this->assertTrue($definition->isSingleton());
        $this->assertTrue($definition->hasConstructor());
        $this->assertEquals([], $definition->getInjections());


        $definition = new Definition(self::class, DefinitionInterface::SINGLETON);

        $this->assertEquals(self::class, $definition->getClassName());
        $this->assertTrue($definition->isSingleton());
        $this->assertFalse($definition->hasConstructor());
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
