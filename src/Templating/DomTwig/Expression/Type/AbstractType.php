<?php
namespace Kr\MvcCli\Templating\DomTwig\Expression\Type;

class AbstractType
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function asString()
    {
        return (string)$this->getValue();
    }


    /**
     * Trims most expressions to their correct form: "True " becomes "true"
     *
     * @param string $expr
     *
     * @return string
     */
    public static function trim($expr)
    {
        return strtolower(trim($expr));
    }

    protected static function isCorrectlyEnclosed($string)
    {
        $string = trim($string);
        return self::startsWith($string, static::OPEN_TAG) && self::endsWith($string, static::CLOSE_TAG);
    }

    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
}