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

namespace N86io\Di;

use N86io\Di\Exception\ClassMapperException;
use Webmozart\Assert\Assert;

/**
 * Class mapper for mapping given classes to configured target class.
 *
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class ClassMapper
{
    /**
     * Class mappings.
     *
     * @var string[]
     */
    private $mappings = [];

    /**
     * Add mapping.
     *
     * @param string $sourceClass Source class, from which class should map.
     * @param string $targetClass Target class, to which class should map.
     *
     * @return ClassMapper
     */
    public function addMapping(string $sourceClass, string $targetClass): ClassMapper
    {
        Assert::allClassExists([$sourceClass, $targetClass]);
        $this->mappings[$sourceClass] = $targetClass;
        $this->checkForLoop($sourceClass);

        return $this;
    }

    /**
     * Add array of mappings.
     *
     * @param string[] $mappings Key should be source class and value target class.
     *
     * @return ClassMapper
     *
     * @see self::addMapping()
     */
    public function addMappings(array $mappings): ClassMapper
    {
        foreach ($mappings as $sourceClass => $targetClass) {
            $this->addMapping($sourceClass, $targetClass);
        }

        return $this;
    }

    /**
     * Map from a class to configured target class.
     *
     * @param string $sourceClass Source class, from which class should map.
     *
     * @return string
     */
    public function map(string $sourceClass): string
    {
        Assert::classExists($sourceClass);
        if (empty($this->mappings[$sourceClass])) {
            return $sourceClass;
        }

        return $this->map($this->mappings[$sourceClass]);
    }

    /**
     * @param string $className
     * @param array  $loopTemp
     *
     * @throws ClassMapperException
     */
    protected function checkForLoop(string $className, array $loopTemp = [])
    {
        if (array_search($className, $loopTemp) !== false) {
            throw new ClassMapperException('Invalid class-mapping. There is a loop in it.');
        }
        $loopTemp[] = $className;
        if (isset($this->mappings[$className])) {
            $this->checkForLoop($this->mappings[$className], $loopTemp);
        }
    }
}
