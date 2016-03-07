<?php
namespace Kr\MvcCli\Directive\Input;

use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Style\MvcCliStyle;

/**
 * Class Choice
 * @package Kr\MvcCli\Directive\Input
 */
class Choice extends AbstractTag
{
    const TAGS = "choice";

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDefaultOptions([
            "returnKey"  => null,
            "question"  => null,
            "choices"   => [],
            "default"   => null,
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function loadDom(\DOMElement $dom, array $options = [])
    {
        $options = [
            "question"  => null,
            "returnKey" => $dom->getAttribute("name"),
            "choices"   => [],
            "default"   => null,
        ];

        $options['question'] = $dom->getElementsByTagName("question")->item(0)->nodeValue;

        $optionElms = $dom->getElementsByTagName("option");
        foreach($optionElms as $elm)
        {
            $key = $elm->getAttribute("value");
            $value = $elm->nodeValue;
            if($elm->getAttribute("default")) {
                $options['default'] = $value;
            }
            $options['choices'][$key] = $value;
        }
/*
        foreach($dom->childNodes as $child) {
            if($child->nodeName == "question")
            {
                $options['question'] = $child->nodeValue;
            }
            else if($child->nodeName == "option")
            {
                $key = $child->getAttribute("value");
                $value = $child->nodeValue;
                if($child->getAttribute("default")) {
                    $options['default'] = $value;
                }
                $options['choices'][$key] = $value;
            }
        }*/
        return $options;
    }

    /**
     * @inheritdoc
     */
    protected function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();

        $choice = $io->choice($options['question'], $options['choices'], $options['default']);

        return [
            $options['returnKey'] => $choice,
        ];
    }
}