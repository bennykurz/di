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

use Doctrine\Common\Cache\ArrayCache;
use N86io\Di\Container;
use N86io\Di\Exception\ContainerException;
use N86io\Di\Injection\InjectionOverride;
use N86io\Di\Tests\Stuff\TestClass;
use N86io\Di\Tests\Stuff\TestClass2;
use N86io\Di\Tests\Stuff\TestClass3;
use N86io\Di\Tests\Stuff\TestClass4;
use N86io\Di\Tests\Stuff\TestClass8;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class ContainerTest extends TestCase
{
    public function test()
    {
        Container::initialize(new ArrayCache);
        $container = Container::getInstance();

        $testClass = $container->get(TestClass::class);
        $this->assertInstanceOf(TestClass2::class, $testClass->test2);
        $this->assertInstanceOf(TestClass3::class, $testClass->test3);
        $this->assertInstanceOf(TestClass4::class, $testClass->test4);

        $testClass2 = new TestClass2;
        $testClass2->value = 'hallo';
        $injectionOverride = new InjectionOverride;
        $injectionOverride->add('test2', $testClass2);
        $testClass = $container->get(TestClass::class, $injectionOverride);
        $this->assertEquals('hallo', $testClass->test2->value);

        $this->assertInstanceOf(TestClass8::class, $container->get(TestClass8::class));
        $this->assertInstanceOf(TestClass8::class, $container->get(TestClass8::class));
    }

    public function testIsInitialized()
    {
        $this->assertFalse(Container::isInitialized());
        Container::initialize(new ArrayCache);
        $this->assertTrue(Container::isInitialized());
    }

    public function testException1()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionCode(1482861649);
        Container::getInstance();
    }

    public function testException2()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionCode(1482861671);
        Container::initialize(new ArrayCache);
        Container::initialize(new ArrayCache);
    }

    public function testException3()
    {
        Container::initialize(new ArrayCache);
        $this->expectException(ContainerException::class);
        $this->expectExceptionCode(1482861693);
        $testClass2 = new TestClass2;
        $testClass2->value = 'hallo';
        $injectionOverride = new InjectionOverride;
        $injectionOverride->add('setTest3', $testClass2);
        Container::getInstance()->get(TestClass::class, $injectionOverride);
    }
}
