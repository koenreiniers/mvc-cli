<?php
namespace Kr\MvcCli\Directive\Control;

use Kr\MvcCli\Controller\ControllerInterface;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;
use Kr\MvcCli\Controller\ControllerAwareTrait;
use Kr\MvcCli\Controller\ControllerAwareInterface;
use Kr\MvcCli\Directive\AbstractTag;


class Nav extends AbstractTag implements TagInterface, ControllerAwareInterface
{
    const TAGS = "nav";
    use ControllerAwareTrait;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDefaultOptions([
            "title"     => "Go to:",
            "default"   => null,
            "choices"   => [],
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function loadDom(\DOMElement $dom, array $options = [])
    {
        $anchors = [];

        foreach($dom->childNodes as $child) {
            if(!$child instanceof \DOMElement) {
                continue;
            }
            $anchors[] = [
                "href" => $child->getAttribute("href"),
                "label" => $child->nodeValue
            ];
        }

        foreach($anchors as $anchor)
        {
            $options['choices'][] = ["route" => $anchor['href'], "label" => $anchor['label']];
        }

        return $options;
    }

    /**
     * @inheritdoc
     */
    protected function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();
        $title = $options['title'];


        $choices = [];
        $i = 1;
        foreach($options['choices'] as $choice)
        {
            $choices[$i] = $choice['label'];
            $i++;
        }


        // ..wut?
        $label = $io->choice($title, $choices);

        foreach($options['choices'] as $choice) {
            if($choice['label'] == $label) {
                $route = $choice['route'];
            }
        }

        $arguments = explode(":", $route);
        $routeName = array_shift($arguments);

        $this->controller->redirectTo($routeName, $arguments);

    }
}