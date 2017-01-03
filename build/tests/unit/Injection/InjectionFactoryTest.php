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

namespace N86io\Di\Tests\Unit\Injection;

use N86io\Di\Exception\InjectionFactoryException;
use N86io\Di\Injection\InjectionFactory;
use N86io\Di\Injection\MethodInjection;
use N86io\Di\Injection\PropertyInjection;
use N86io\Di\Tests\Stuff\TestClass;
use N86io\Di\Tests\Stuff\TestClassError;
use N86io\Di\Tests\Stuff\TestClassError2;
use N86io\Di\Tests\Stuff\TestClassError3;
use N86io\Reflection\ReflectionClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class InjectionFactoryTest extends TestCase
{
    public function test()
    {
        $reflection = new ReflectionClass(TestClass::class);

        $methodInjections = InjectionFactory::createMethodInjections($reflection->getMethods());
        $this->assertInstanceOf(MethodInjection::class, $methodInjections[0]);
        $this->assertInstanceOf(MethodInjection::class, $methodInjections[1]);
        $this->assertArrayNotHasKey(2, $methodInjections);

        $propertyInjections = InjectionFactory::createPropertyInjections($reflection->getProperties());
        $this->assertInstanceOf(PropertyInjection::class, $propertyInjections[0]);
    }

    public function testException1()
    {
        $reflection = new ReflectionClass(TestClassError::class);

        $this->expectException(InjectionFactoryException::class);
        $this->expectExceptionCode(1482512265);
        InjectionFactory::createMethodInjections($reflection->getMethods());
    }

    public function testException2()
    {
        $reflection = new ReflectionClass(TestClassError2::class);

        $this->expectException(InjectionFactoryException::class);
        $this->expectExceptionCode(1482512242);
        InjectionFactory::createMethodInjections($reflection->getMethods());
    }

    public function testException3()
    {
        $reflection = new ReflectionClass(TestClassError3::class);

        $this->expectException(InjectionFactoryException::class);
        $this->expectExceptionCode(1482512248);
        InjectionFactory::createMethodInjections($reflection->getMethods());
    }
}
