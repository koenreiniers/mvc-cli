<?php
namespace Kr\MvcCli\Templating\DomTwig\Filter;

use Kr\MvcCli\Controller\Scope;
use Kr\MvcCli\Templating\DomTwig\Expression\ExpressionParser;

/**
 * <loop for="key" in="['a','b','c']">
 * Class VerbatimFilter
 * @package Kr\MvcCli\Templating\DomTwig\Filter
 */
class LoopFilter implements FilterInterface
{
    const TAG = "loop";

    /** @var ApplyScopeFilter */
    private $applyScopeFilter;

    /** @var ExpressionParser */
    private $expressionParser;

    /**
     * LoopFilter constructor.
     * @param ApplyScopeFilter $applyScopeFilter
     * @param ExpressionParser $expressionParser
     */
    public function __construct(ApplyScopeFilter $applyScopeFilter, ExpressionParser $expressionParser)
    {
        $this->applyScopeFilter = $applyScopeFilter;
        $this->expressionParser = $expressionParser;
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 75;
    }

    /**
     * @inheritdoc
     */
    public function apply(\DOMElement $dom, Scope $scope)
    {
        if($dom->nodeName === self::TAG) {
            $loopTags = [$dom];
        } else {
            $loopTags = $dom->getElementsByTagName(self::TAG);
        }

        foreach($loopTags as $loopTag)
        {
            $key = $loopTag->getAttribute("for");
            $in = $loopTag->getAttribute("in");

            $values = $this->expressionParser->parse($in);

            $newElements = [];

            $i = 0;
            foreach($values as $value)
            {
                foreach($loopTag->childNodes as $childNode)
                {
                    $newElement = clone $childNode;

                    $this->applyScopeFilter->doApply($newElement, [$key => $value, "loop.index" => $i]);
                    $newElements[] = $newElement;
                }
                $i++;
            }
            // Remove old child nodes
            foreach($loopTag->childNodes as $childNode)
            {
                $childNode->parentNode->removeChild($childNode);
            }

            // Append new elements as children
            foreach($newElements as $newElement)
            {
                $loopTag->appendChild($newElement);
            }
        }
    }
}