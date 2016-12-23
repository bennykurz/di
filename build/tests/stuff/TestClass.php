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

namespace N86io\Di\Tests\Stuff;

/**
 * @author Viktor Firus <v@n86.io>
 */
class TestClass
{
    /**
     * @inject
     * @var TestClass2
     */
    protected $test2;

    /**
     * @var TestClass3
     */
    protected $test3;

    /**
     * @var TestClass4
     */
    protected $test4;

    /**
     * @inject
     *
     * @param $testClass3
     */
    public function setTest3(TestClass3 $testClass3)
    {
        $this->test3 = $testClass3;
    }

    /**
     * @param TestClass4 $testClass4
     */
    public function injectTest4($testClass4)
    {
        $this->test4 = $testClass4;
    }
}
