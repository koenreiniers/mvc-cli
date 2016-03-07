<?php
namespace Kr\MvcCli\Templating\
DomTwig;

use Kr\MvcCli\Controller\Scope;
use Kr\MvcCli\Directive\OldTagInterface;
use Kr\MvcCli\Controller\ControllerAwareInterface;

use Kr\MvcCli\Directive\TagRegistry;
use Kr\MvcCli\Templating\DomTwig\Expression\ExpressionParser;
use Kr\MvcCli\Templating\EngineInterface;


class Engine implements EngineInterface
{
    const TAG_VIEW = "view";
    const TAG_SECTION = "section";

    private $twig;
    private $tagRegistry;
    private $filters;

    public function __construct()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_String());

        $applyScopeFilter = new Filter\ApplyScopeFilter();

        $expressionParser = new ExpressionParser();

        $this->filters = [
            new Filter\VerbatimFilter(),
            new Filter\LoopFilter($applyScopeFilter, $expressionParser),
            $applyScopeFilter,
         ];
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function addFilter()
    {

    }

    /**
     * @inheritdoc
     */
    public function getFileExtension()
    {
        return "html.twig";
    }

    /**
     * @inheritdoc
     */
    public function setTagRegistry(TagRegistry $tagRegistry)
    {
        $this->tagRegistry = $tagRegistry;
        return $this;
    }

    /**
     * @inheritdoc
     * TODO: Find out if there isn't a cleaner way to do this
     */
    public function render($filePath, Scope $scope)
    {
        $io = $scope->getController()->getApp()->io();
        libxml_use_internal_errors(true);
        $html = file_get_contents($filePath);

        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadHtml($html);


        $wrapper = $dom->getElementsByTagName(self::TAG_VIEW)->item(0);

        foreach($wrapper->childNodes as $childNode)
        {
            if(!$childNode instanceof \DOMElement) {
                //$io->debug("Invalid element");
                continue;
            }
            $tagName = $childNode->nodeName;

            $directiveTwig = $childNode->ownerDocument->saveHTML($childNode);
            $directiveHtml = $this->twig->render($directiveTwig, $scope->getData());

            $directive = new \DOMDocument('1.0', 'utf-8');
            $directive->loadHtml($directiveHtml);

            $elm = $directive->getElementsByTagName($tagName)->item(0);

            $this->renderElement($elm, $scope);

        }
        libxml_clear_errors();
    }

    /**
     * @return TagRegistry
     */
    public function getTagRegistry()
    {
        return $this->tagRegistry;
    }

    /**
     * Loads the view content through the view identifier
     *
     * @param string $view
     *
     * @return string
     */
    protected function loadView($view)
    {
        return file_get_contents($view);
    }

    /**
     * Render a single DOM element with the provided scope
     *
     * @param \DOMElement $dom
     * @param Scope $scope
     *
     * @return array|mixed
     *
     * @throws \Exception
     */
    protected function renderElement(\DOMElement $dom, Scope $scope)
    {
        $io = $scope->getController()->getApp()->io();
        $results = [];
        $tagName = $dom->nodeName;

        foreach($this->getFilters() as $filter)
        {
            $filter->apply($dom, $scope);
        }

        // Render children of <section>
        if($tagName == self::TAG_SECTION || $tagName == Filter\LoopFilter::TAG)
        {
            foreach($dom->childNodes as $child) {
                $this->renderElement($child, $scope);
            }
            return;
        }

        // TODO: Move to another service
        $tag = $this->getTagRegistry()->get($tagName);
        if($tag instanceof OldTagInterface)
        {
            $result = $tag::render($io, $dom); // @deprecated
        }
        else
        {
            $instance = $tag::create($dom);
            // Inject controller if necessary
            if($instance instanceof ControllerAwareInterface) {
                $instance->setController($scope->getController());
            }
            // Execute it
            $result = $instance->execute($io);
        }

        if($result) {
            $results = array_merge($results, $result);
        }

        // Update scope
        $scope->apply($results);
    }
}