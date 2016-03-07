<?php
namespace Kr\MvcCli\Templating\DomTwig\Expression\Type;

use Kr\MvcCli\Templating\DomTwig\Expression\ExpressionFactory;

class ArrayType extends AbstractType
{
    const OPEN_TAG = "[";
    const CLOSE_TAG = "]";
    const SEPARATOR = ",";

    /**
     * @param string $string
     *
     * @return boolean
     */
    public static function isMatch($string)
    {
        return self::isCorrectlyEnclosed($string);
    }

    /**
     * Retrieves individual expressions for each element of the array
     *
     * @param string $expr - Valid array expression
     *
     * @return array    - Array of expressions
     */
    public static function parseToExpressions($expr)
    {
        $string = self::trim($expr);
        if($string == "") {
            return [];
        }
        $expressions = [];
        $tail = explode(",", $string);
        $head = array_shift($tail);
        while($head != null && strpos($head, self::OPEN_TAG) === false)
        {
            $head = rtrim($head, self::CLOSE_TAG);
            $expressions[] = $head;
            $head = array_shift($tail);
        }
        if(count($tail) == 0) {
            return $expressions;
        }
        // We've encountered an array within the array, so we need to find the corresponding closing tag
        array_unshift($tail, $head);
        $string = implode(",", $tail);
        $org = $string;

        $pos = 0;

        $depth = 1;
        while($depth > 0) {
            $a = strpos($string, self::OPEN_TAG);
            $b = strpos($string, self::CLOSE_TAG);

            // We encounter an opening tag
            if($a !== false && $a < $b) {
                $depth++;
                $string = substr($string, $a + 1);
                $pos += $a;
                // We encounter a closing tag
            } else if($b !== false) {
                $depth--;
                $string = substr($string, $b + 1);
                $pos += $b;
            } else {
                $depth--;
            }
        }

        $tail = $string;

        // Our sub-array
        $expressions[] = substr($org, 0, strlen($org) - strlen($tail));

        // Recursively solve it
        return array_merge($expressions, self::parseToExpressions($tail));
    }

    /**
     * Retrieves individual expressions for each element of the array
     * And parse them as well
     *
     * @param $expr     - Valid array expression
     *
     * @return array    - Array of
     */
    public static function parse($expr)
    {
        $expressions = self::parseToExpressions($expr);
        $elements = [];
        foreach($expressions as $expression) {
            $elements[] = ExpressionFactory::create($expression);
        }
        return $elements;
    }

    /**
     * Trims array expression down to elm,elm,elm
     *
     * @param string $expr
     *
     * @return string
     */
    public static function trim($expr)
    {
        $expr = trim($expr, " " . self::OPEN_TAG . self::CLOSE_TAG . self::SEPARATOR);
        return $expr;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Converts array type to string:
     * [elm,elm,elm]
     *
     * @return string
     */
    public function asString()
    {
        $elements = $this->getValue();

        $str = self::OPEN_TAG;

        $elmStr = [];
        foreach($elements as $element) {
            $elmStr[] = $element->asString();
        }

        $str .= implode(self::SEPARATOR, $elmStr);

        $str .= self::CLOSE_TAG;

        return $str;
    }
}