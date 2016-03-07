<?php
namespace Kr\MvcCli\Directive\Input;

use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;


class AnyKey extends AbstractTag implements TagInterface
{
    const TAGS = "any-key";
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $options = [
            "text" => "<Press any key to continue>",
        ];
        $this->setDefaultOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function loadDom(\DOMElement $dom, array $options = [])
    {
        if($dom->nodeValue) {
            $options['text'] = $dom->nodeValue;
        }
        return $options;
    }

    /**
     * @inheritdoc
     */
    public function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();
        $io->text($options['text']);
        readline();
    }
}