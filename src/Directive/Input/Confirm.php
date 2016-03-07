<?php
namespace Kr\MvcCli\Directive\Input;

use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\OldTagInterface;

class Confirm implements OldTagInterface
{
    /**
     * @inheritdoc
     */
    public static function render(MvcCliStyle $io, \DOMElement $dom)
    {
        $options = [
            "returnKey" => null,
            "question"  => null,
            "default"   => true,
        ];

        $options['question'] = $dom->nodeValue;
        $options['returnKey'] = $dom->getAttribute("name");

        if($dom->getAttribute("default"))
        {
            $falsy = ["no", "false", "0", 0];
            if(in_array($dom->getAttribute("default"), $falsy)) {
                $options['default'] = false;
            }
        }


        $confirm = $io->confirm($options['question'], $options['default']);

        return [
            $options['returnKey'] => $confirm,
        ];
    }
}