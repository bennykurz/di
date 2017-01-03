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

namespace N86io\Di\Injection;

use N86io\Di\Exception\InjectionFactoryException;
use N86io\Reflection\DocComment;
use N86io\Reflection\ReflectionMethod;
use N86io\Reflection\ReflectionProperty;
use Webmozart\Assert\Assert;

/**
 * @author Viktor Firus <v@n86.io>
 * @since  1.0.0
 */
class InjectionFactory
{
    /**
     * Create injections from reflection object ReflectionProperty and returns.
     *
     * @param ReflectionProperty[] $properties
     *
     * @return PropertyInjection[]
     */
    public static function createPropertyInjections(array $properties): array
    {
        $result = [];
        foreach ($properties as $property) {
            $docComment = $property->getParsedDocComment();
            if ($docComment->hasTag('inject')) {
                $injection = new PropertyInjection(
                    $property->getName(),
                    self::removeBackslashAtFirstPos($docComment->getTagsByName('var')[0])
                );
                $result[] = $injection;
            }
        }

        return $result;
    }

    /**
     * Create injections from reflection object ReflectionMethod and returns.
     *
     * @param ReflectionMethod[] $methods
     *
     * @return MethodInjection[]
     */
    public static function createMethodInjections(array $methods): array
    {
        $result = [];
        foreach ($methods as $method) {
            Assert::isInstanceOf($method, ReflectionMethod::class);
            $docComment = $method->getParsedDocComment();
            if (self::isInjectionMethod($method)) {
                $type = self::getTypeFromParam($method);
                if ($type !== '') {
                    $injection = new MethodInjection($method->getName(), $type);
                    $result[] = $injection;
                    continue;
                }
                $type = self::getTypeFromDocComment($docComment, $method->getParameters()[0]->getName());
                $injection = new MethodInjection($method->getName(), $type);
                $result[] = $injection;
            }
        }

        return $result;
    }

    /**
     * Returns true, if method is an injection-method.
     *
     * @param ReflectionMethod $method
     *
     * @return bool
     */
    private static function isInjectionMethod(ReflectionMethod $method)
    {
        $docComment = $method->getParsedDocComment();

        return (
                ($docComment->hasTag('inject') && substr($method->getName(), 0, 3) === 'set') ||
                substr($method->getName(), 0, 6) === 'inject'
            ) &&
            $method->isPublic();
    }

    /**
     * Get object type for injection from method parameter.
     *
     * @param ReflectionMethod $method
     *
     * @return string
     * @throws InjectionFactoryException
     */
    private static function getTypeFromParam(ReflectionMethod $method): string
    {
        if ($method->getNumberOfParameters() < 1 || $method->getNumberOfParameters() > 1) {
            throw new InjectionFactoryException(
                'Invalid count of parameter from injection method, it should have only 1 parameter.',
                1482512265
            );
        }

        return (string)$method->getParameters()[0]->getType();
    }

    /**
     * Get object type for injection from doc-comment.
     *
     * @param DocComment $docComment
     * @param string     $parameterName
     *
     * @return string
     * @throws InjectionFactoryException
     */
    private static function getTypeFromDocComment(DocComment $docComment, string $parameterName): string
    {
        $paramTag = $docComment->getTagsByName('param');
        if (count($paramTag) < 1 || count($paramTag) > 1) {
            throw new InjectionFactoryException(
                'Invalid count of param definitions in doc-comment.',
                1482512242
            );
        }
        $paramTagValue = ' ' . $paramTag[0] . ' ';
        if (strpos($paramTagValue, ' $' . $parameterName . ' ') === false) {
            throw new InjectionFactoryException(
                'Can\'t found type-definition for $' . $parameterName . ' in doc-comment',
                1482512248
            );
        }

        return self::removeBackslashAtFirstPos(explode(' ', trim($paramTagValue))[0]);
    }

    /**
     * Remove slash at first position, if available.
     *
     * @param string $string
     *
     * @return string
     */
    private static function removeBackslashAtFirstPos(string $string)
    {
        return substr($string, 0, 1) === '\\' ? substr($string, 1) : $string;
    }
}
