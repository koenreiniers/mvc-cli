<?php
namespace Kr\MvcCli\Directive\Output;

use Kr\MvcCli\Directive\OldTagInterface;
use Kr\MvcCli\Style\MvcCliStyle;

class Listing implements OldTagInterface
{
    /**
     * @inheritdoc
     */
    public static function render(MvcCliStyle $io, \DOMElement $list)
    {
        $elements = [];

        foreach($list->childNodes as $li)
        {
            $elements[] = $li->nodeValue;
        }

        $io->listing($elements);
    }
}