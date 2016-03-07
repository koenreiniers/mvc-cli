<?php
namespace Kr\MvcCli\Directive;

use Kr\MvcCli\Style\MvcCliStyle;

/**
 * Interface OldTagInterface
 * @package Kr\MvcCli\Tag
 * @deprecated
 */
interface OldTagInterface
{
    static function render(MvcCliStyle $io, \DOMElement $dom);
}