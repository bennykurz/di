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
class TestClass implements TestClassInterface
{
    /**
     * @inject
     * @var TestClass2
     */
    public $test2;

    /**
     * @var TestClass3
     */
    public $test3;

    /**
     * @var TestClass4
     */
    public $test4;

    /**
     * @return TestClass2
     */
    public function getTest2()
    {
        return $this->test2;
    }

    /**
     * @return TestClass3
     */
    public function getTest3()
    {
        return $this->test3;
    }

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

    /**
     * @param TestClass4 $testClass4
     */
    private function injectTest5($testClass4)
    {
        $this->test4 = $testClass4;
    }
}
