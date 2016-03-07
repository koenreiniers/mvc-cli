<?php
namespace Kr\MvcCli\Templating\DomTwig\Filter;

use Kr\MvcCli\Controller\Scope;

/**
 * Apply scope to DOM
 * Class ApplyScopeFilter
 * @package Kr\MvcCli\Templating\DomTwig\Filter
 */
class ApplyScopeFilter implements FilterInterface
{
    const TAG = "v";

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 50;
    }

    /**
     * @param \DOMElement $dom
     * @param array $values
     */
    public function doApply(\DOMElement $dom, array $values)
    {
        $hits = [];

        // Find elements in the form of <v:key/>
        /* TODO
        foreach($values as $key => $value)
        {
            $elements = $dom->getElementsByTagName($key);
            foreach($elements as $element)
            {
                var_dump($element->prefix);
                readline();
            }
        }
        */

        // Find elements in the form of <v key="key"/> and <v>key</v>
        $elements = $dom->getElementsByTagName(self::TAG);
        foreach($elements as $element)
        {
            if($element->hasAttribute("key")) {
                $key = $element->getAttribute("key");
            } else {
                $key = $element->nodeValue;
            }
            $hits[] = ["key" => $key, "element" => $element];
        }

        // Actually replace them
        foreach($hits as $hit)
        {
            $key = $hit['key'];
            if(!isset($values[$key])) {
                continue;
            }

            $value = $values[$key];

            if(is_bool($value)) {
                $value = $value ? "true" : "false";
            }

            $hit['element']->parentNode->replaceChild(new \DOMText($value), $hit['element']);
        }
    }

    /**
     * @inheritdoc
     */
    public function apply(\DOMElement $dom, Scope $scope)
    {
        $this->doApply($dom, $scope->getData());
    }
}