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

use N86io\Di\Injection\MethodInjection;
use N86io\Di\Tests\Stuff\TestClass;
use N86io\Di\Tests\Stuff\TestClass3;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 */
class MethodInjectionTest extends TestCase
{
    public function test()
    {
        $injection = new MethodInjection('setTest3', PropertyInjectionTest::class);
        $this->assertEquals('setTest3', $injection->getInjectionName());
        $this->assertEquals(PropertyInjectionTest::class, $injection->getType());

        $testClass = new TestClass;
        $testClass3 = new TestClass3;

        $this->assertNull($testClass->getTest3());
        $injection->inject($testClass, $testClass3);
        $this->assertInstanceOf(TestClass3::class, $testClass->getTest3());
    }
}
