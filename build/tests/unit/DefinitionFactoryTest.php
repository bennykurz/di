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
use N86io\Di\Definition\DefinitionFactory;
use N86io\Di\Exception\DefinitionFactoryException;
use N86io\Di\Injection\MethodInjection;
use N86io\Di\Injection\PropertyInjection;
use N86io\Di\Tests\Stuff\TestClass;
use N86io\Di\Tests\Stuff\TestClass2;
use N86io\Di\Tests\Stuff\TestClassError;
use N86io\Di\Tests\Stuff\TestClassError2;
use N86io\Di\Tests\Stuff\TestClassError3;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class DefinitionFactoryTest extends TestCase
{
    /**
     * @var DefinitionFactory
     */
    private $definitionFactory;

    public function setUp()
    {
        $cache = new ArrayCache;
        $this->definitionFactory = new DefinitionFactory($cache);
    }

    public function test()
    {
        $testClass = $this->definitionFactory->get(TestClass::class);
        $testClass2_1 = $this->definitionFactory->get(TestClass2::class);
        $testClass2_2 = $this->definitionFactory->get(TestClass2::class);

        $this->assertEquals(TestClass::class, $testClass->getClassName());
        $this->assertFalse($testClass->isSingleton());
        $this->assertFalse($testClass->hasConstructor());
        $this->assertInstanceOf(PropertyInjection::class, $testClass->getInjections()[0]);
        $this->assertInstanceOf(MethodInjection::class, $testClass->getInjections()[1]);
        $this->assertInstanceOf(MethodInjection::class, $testClass->getInjections()[2]);

        $this->assertEquals(TestClass2::class, $testClass2_1->getClassName());
        $this->assertEquals(TestClass2::class, $testClass2_2->getClassName());
        $this->assertTrue($testClass2_1->isSingleton());
        $this->assertTrue($testClass2_1->hasConstructor());
        $this->assertEquals([], $testClass2_1->getInjections());
    }

    public function testException1()
    {
        $this->expectException(DefinitionFactoryException::class);
        $this->expectExceptionCode(1482512265);
        $this->definitionFactory->get(TestClassError::class);
    }

    public function testException2()
    {
        $this->expectException(DefinitionFactoryException::class);
        $this->expectExceptionCode(1482512242);
        $this->definitionFactory->get(TestClassError2::class);
    }

    public function testException3()
    {
        $this->expectException(DefinitionFactoryException::class);
        $this->expectExceptionCode(1482512248);
        $this->definitionFactory->get(TestClassError3::class);
    }
}
