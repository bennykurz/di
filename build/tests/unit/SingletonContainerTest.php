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

namespace N86io\Di\Tests\Unit\Singleton;

use N86io\Di\Exception\SingletonContainerException;
use N86io\Di\SingletonContainer;
use N86io\Di\SingletonContainerInterface;
use N86io\Di\Tests\Unit\ClassResolverTest;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class SingletonContainerTest extends TestCase
{
    /**
     * @var SingletonContainerInterface
     */
    protected $singletonContainer;

    public function setUp()
    {
        $this->singletonContainer = new SingletonContainer;
    }

    public function test()
    {
        $this->singletonContainer->set(new self);
        $this->assertInstanceOf(self::class, $this->singletonContainer->get(self::class));
        $this->assertTrue($this->singletonContainer->has(self::class));
        $this->assertFalse($this->singletonContainer->has(ClassResolverTest::class));
    }

    public function testException1()
    {
        $this->expectException(SingletonContainerException::class);
        $this->singletonContainer->get(self::class);
    }

    public function testException2()
    {
        $this->expectException(SingletonContainerException::class);
        $this->singletonContainer->set(new self);
        $this->singletonContainer->set(new self);
    }
}
