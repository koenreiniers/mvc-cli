<?php
namespace Kr\MvcCli\Templating\DomTwig\Expression\Type;

class StringType extends AbstractType
{
    const OPEN_TAG = "'";
    const CLOSE_TAG = "'";

    /**
     * @inheritdoc
     */
    public static function isMatch($expr)
    {
        return self::isCorrectlyEnclosed($expr);
    }

    /**
     * @inheritdoc
     */
    public static function trim($expr)
    {
        // First remove whitespace on the outside
        $expr = trim($expr);

        // Then remove tags
        return trim($expr, self::OPEN_TAG . self::CLOSE_TAG);
    }

    /**
     * @inheritdoc
     */
    public static function parse($expr)
    {
        return self::trim($expr);
    }

    /**
     * @inheritdoc
     */
    public function asString()
    {
        return self::OPEN_TAG . $this->getValue() . self::CLOSE_TAG;
    }

}