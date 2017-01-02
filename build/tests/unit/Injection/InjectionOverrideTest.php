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

use N86io\Di\Exception\InjectionOverrideException;
use N86io\Di\Injection\InjectionOverride;
use N86io\Di\Injection\InjectionOverrideInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class InjectionOverrideTest extends TestCase
{
    /**
     * @var InjectionOverrideInterface
     */
    private $override;

    public function setUp()
    {
        $this->override = new InjectionOverride;
    }

    public function test()
    {
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();
        $this->override->add('ov1', $obj1);
        $this->override->add('ov2', $obj2);

        $this->assertTrue($this->override->has('ov1'));
        $this->assertFalse($this->override->has('ov3'));

        $this->assertSame($obj1, $this->override->get('ov1'));
    }

    public function testException1()
    {
        $this->expectException(InjectionOverrideException::class);
        $this->expectExceptionCode(1482834853);
        $this->override->get('ov3');
    }

    public function testException2()
    {
        $this->expectException(InjectionOverrideException::class);
        $this->expectExceptionCode(1482827005);
        $this->override->add('something', 'not object');
    }
}
