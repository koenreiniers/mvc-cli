<?php
namespace Kr\MvcCli\Directive\Output;

use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;


class Heading extends AbstractTag implements TagInterface
{
    const TAGS = "title|h1|h2";

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $options = [
            "text"  => null,
            "level"  => 1,
        ];
        $this->setDefaultOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function loadDom(\DOMElement $dom, array $options = [])
    {
        $tag = $dom->nodeName;
        $mappings = [
            "title" => 1,
            "h1"    => 1,
            "h2"    => 2,
            "h3"    => 2,
            "h4"    => 2,
            "h5"    => 2,
        ];
        $options['level'] = $mappings[$tag];

        $options['text'] = $dom->nodeValue;
        return $options;
    }

    /**
     * @inheritdoc
     */
    public function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();
        $level = $options['level'];
        $text = $options['text'];

        if($level == 1) {
            $io->title($text);
        } else {
            $io->section($text);
        }
    }
}