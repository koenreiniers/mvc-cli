<?php
namespace Kr\MvcCli\Directive\Control;

use Kr\MvcCli\Controller\ControllerAwareInterface;
use Kr\MvcCli\Controller\ControllerAwareTrait;
use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;


class Redirect extends AbstractTag implements TagInterface, ControllerAwareInterface
{
    const TAGS = "redirect";

    use ControllerAwareTrait;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDefaultOptions([
            "route" => null,
            "parameters" => [],
        ]);
        // $this->setDefaultOptions($options);
    }

    /**
     * @inheritdoc
     */
    protected function loadDom(\DOMElement $dom, array $options = [])
    {
        $options['route'] = $dom->getAttribute("to");

        $parameters = [];
        foreach($dom->childNodes as $child) {
            if($child->nodeName == "inject") {
                $key = $child->nodeValue;
                $parameters[$key] = $this->controller->getScope()->get($key);
            }
        }

        $options['parameters'] = $parameters;

        return $options;
    }

    /**
     * @inheritdoc
     */
    protected function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();
        $route = $options['route'];
        $parameters = $options['parameters'];

        $this->controller->redirectTo($route, $parameters);
    }
}