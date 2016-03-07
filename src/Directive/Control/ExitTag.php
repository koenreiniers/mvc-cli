<?php
namespace Kr\MvcCli\Directive\Control;

use Kr\MvcCli\Controller\ControllerAwareInterface;
use Kr\MvcCli\Controller\ControllerAwareTrait;
use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Directive\ControlTagInterface;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;


/**
 * Class ExitTag
 * @package Kr\MvcCli\Directive\Control
 */
class ExitTag extends AbstractTag implements TagInterface, ControllerAwareInterface
{
    const TAGS = "exit";

    use ControllerAwareTrait;

    protected function configure()
    {
        // Nothing to see here
    }

    protected function loadDom(\DOMElement $dom, array $options = [])
    {
        // Nothing to see here
    }

    /**
     * @inheritdoc
     */
    protected function render(MvcCliStyle $io)
    {
        $this->controller->getApp()->terminate();
    }
}