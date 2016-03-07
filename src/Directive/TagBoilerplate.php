<?php
namespace Kr\MvcCli\Directive;

use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;


class TagBoilerPlate extends AbstractTag implements TagInterface
{
    const TAGS = "a|b|c";

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $options = [

        ];
        $this->setDefaultOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function loadDom(\DOMElement $dom, array $options = [])
    {

        return $options;
    }

    /**
     * @inheritdoc
     */
    public function render(MvcCliStyle $io)
    {
    }
}