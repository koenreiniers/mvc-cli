<?php
namespace Kr\MvcCli\Templating\DomTwig\Expression\Type;

class BooleanType extends AbstractType
{
    const OPEN_TAG = "";
    const CLOSE_TAG = "";

    /**
     * List of expressions that will correctly match
     * @return array
     */
    public static function getMatches()
    {
        return [
            "true" => true,
            "false" => false,
        ];
    }

    /**
     * @param string $expr
     *
     * @return mixed
     */
    public static function parse($expr)
    {
        $expr = self::trim($expr);

        $matches = self::getMatches();
        return $matches[$expr];
    }

    /**
     * @param string $expr
     *
     * @return boolean
     */
    public static function isMatch($expr)
    {
        $expr = self::trim($expr);
        $matches = self::getMatches();
        return isset($matches[$expr]);
    }

    /**
     * @inheritdoc
     */
    public function asString()
    {
        return $this->getValue() ? "true" : "false";
    }
}