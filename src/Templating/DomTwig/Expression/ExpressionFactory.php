<?php
namespace Kr\MvcCli\Templating\DomTwig\Expression;

use Kr\MvcCli\Templating\DomTwig\Expression\Type;

class ExpressionFactory
{
    /**
     * @param string $expression
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public static function create($expression)
    {
        $availableTypes = [
            Type\ArrayType::class,
            Type\BooleanType::class,
            Type\StringType::class,
        ];
        foreach($availableTypes as $type) {
            if($type::isMatch($expression)) {
                $value = $type::parse($expression);
                $object = new $type($value);
                return $object->getValue();
            }
        }
        throw new \Exception("Invalid expression");
    }
}