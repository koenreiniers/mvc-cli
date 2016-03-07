<?php
namespace Kr\MvcCli\Templating\DomTwig\Expression;

use Kr\MvcCli\Templating\DomTwig\Expression\ExpressionFactory;
use Kr\MvcCli\Templating\DomTwig\Expression\Type\AbstractType;

class ExpressionParser
{
    /**
     * Parses an expression
     *
     * @param string $expression
     *
     * @return AbstractType
     */
    public function parse($expression)
    {
        $type = ExpressionFactory::create($expression);
        return $type;
    }
}