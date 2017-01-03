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

use N86io\Di\ClassResolver;
use N86io\Di\Container;
use N86io\Di\Exception\ClassResolverException;
use N86io\Di\Tests\Stuff\TestClass;
use N86io\Di\Tests\Stuff\TestClass2;
use N86io\Di\Tests\Stuff\TestClass2Interface;
use N86io\Di\Tests\Stuff\TestClass3;
use N86io\Di\Tests\Stuff\TestClass4;
use N86io\Di\Tests\Stuff\TestClass8;
use N86io\Di\Tests\Stuff\TestClass9Interface;
use N86io\Di\Tests\Stuff\TestClassInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 */
class ClassResolverTest extends TestCase
{
    /**
     * @var ClassResolver
     */
    protected $classResolver;

    public function setUp()
    {
        $this->classResolver = new ClassResolver;
        $this->classResolver->addMappings([
            TestClass2::class => TestClass3::class
        ]);
    }

    public function test()
    {
        $this->assertEquals(TestClass3::class, $this->classResolver->resolve(TestClass2::class));
        $this->assertEquals(TestClass3::class, $this->classResolver->resolve(TestClass3::class));

        $this->assertEquals(TestClass::class, $this->classResolver->resolve(TestClass::class));
        $this->assertEquals(TestClass::class, $this->classResolver->resolve(TestClassInterface::class));

        $this->assertEquals(TestClass3::class, $this->classResolver->resolve(TestClass2Interface::class));
        $this->classResolver->addMapping(TestClass3::class, TestClass4::class);
        $this->assertEquals(TestClass4::class, $this->classResolver->resolve(TestClass2::class));
        $this->assertEquals(TestClass4::class, $this->classResolver->resolve(TestClass2Interface::class));
    }

    public function testWithMappingForInterface()
    {
        $this->classResolver->addMapping(TestClass9Interface::class, TestClass8::class);
        $this->assertEquals(TestClass8::class, $this->classResolver->resolve(TestClass9Interface::class));
    }

    public function testException1()
    {
        $this->expectException(ClassResolverException::class);
        $this->expectExceptionCode(1482825204);

        $this->classResolver->addMappings([
            Container::class => TestClass::class
        ]);
    }

    public function testException2()
    {
        $this->expectException(ClassResolverException::class);
        $this->expectExceptionCode(1482825227);

        $this->classResolver->resolve(TestClass9Interface::class);
    }
}
