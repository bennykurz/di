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

namespace N86io\Di\Definition;

use N86io\Di\Injection\InjectionInterface;
use Webmozart\Assert\Assert;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class Definition implements DefinitionInterface
{
    /**
     * Class-name of defined class.
     *
     * @var string
     */
    private $className;

    /**
     * Type of class, singleton or prototype.
     *
     * @var int
     */
    private $type = DefinitionInterface::PROTOTYPE;

    /**
     * Has class constructor (true), or not (false).
     *
     * @var bool
     */
    private $constructor = false;

    /**
     * Injections for class.
     *
     * @var InjectionInterface[]
     */
    private $injections = [];

    /**
     * @param string $className
     * @param int    $type
     */
    public function __construct($className, int $type)
    {
        $this->className = $className;
        Assert::oneOf(
            $type,
            [DefinitionInterface::SINGLETON, DefinitionInterface::PROTOTYPE],
            'Wrong definition type.'
        );
        $this->type = $type;
    }

    /**
     * Returns class-name of defined class.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Returns true, if class is a singleton. If is prototype, returns false.
     *
     * @return bool
     */
    public function isSingleton(): bool
    {
        return $this->type === DefinitionInterface::SINGLETON;
    }

    /**
     * Returns true, if class has a constructor, otherwise false.
     *
     * @return bool
     */
    public function hasConstructor(): bool
    {
        return $this->constructor;
    }

    /**
     * Set if class has a constructor.
     *
     * @param bool $constructor
     *
     * @return DefinitionInterface
     */
    public function setConstructor(bool $constructor): DefinitionInterface
    {
        $this->constructor = $constructor;

        return $this;
    }

    /**
     * Returns all injections.
     *
     * @return InjectionInterface[]
     */
    public function getInjections(): array
    {
        return $this->injections;
    }

    /**
     * Add an injection.
     *
     * @param InjectionInterface $injection
     *
     * @return DefinitionInterface
     */
    public function addInjection(InjectionInterface $injection): DefinitionInterface
    {
        $this->injections[] = $injection;

        return $this;
    }
}
