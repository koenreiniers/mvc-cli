<?php
namespace Kr\MvcCli\Templating\DomTwig\Filter;

use Kr\MvcCli\Controller\Scope;

/**
 * Escape html inbetween <verbatim></verbatim>
 * Class VerbatimFilter
 * @package Kr\MvcCli\Templating\DomTwig\Filter
 */
class VerbatimFilter implements FilterInterface
{
    const TAG = "verbatim";

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 100;
    }

    /**
     * @inheritdoc
     */
    public function apply(\DOMElement $dom, Scope $scope)
    {
        // Find <verbatim> elements
        $elements = $dom->getElementsByTagName(self::TAG);
        foreach($elements as $element)
        {
            $content = "";
            foreach($element->childNodes as $childNode)
            {
                $content .= $childNode->ownerDocument->saveHTML($childNode);
            }
            $element->nodeValue = htmlspecialchars($content);

        }
    }
}