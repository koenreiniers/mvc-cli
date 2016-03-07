<?php
namespace Kr\MvcCli\Templating\DomTwig\Filter;

use Kr\MvcCli\Controller\Scope;

interface FilterInterface
{
    /**
     * Priority of this filter.
     * The higher it is, the earlier it gets applied
     * @return int
     */
    public function getPriority();

    /**
     * Apply filter to dom + scope
     * @param \DOMElement $dom
     * @param Scope $scope
     */
    public function apply(\DOMElement $dom, Scope $scope);
}