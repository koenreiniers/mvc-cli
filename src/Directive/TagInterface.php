<?php
namespace Kr\MvcCli\Directive;

use Kr\MvcCli\Style\MvcCliStyle;

/**
 * Interface TagInterface
 * @package Kr\MvcCli\Tag
 */
interface TagInterface
{
    /**
     * Return list of tags to which this directive binds
     * @return array
     */
    public static function getTags();

    /**
     * TODO: Decouple from DOM
     * Create new instance
     *
     * @param \DOMElement $dom
     *
     * @return TagInterface
     */
    public static function create(\DOMElement $dom);

    /**
     * Called on initialization
     *
     * @param MvcCliStyle $io
     */
    public function execute(MvcCliStyle $io);

    /**
     * Reset configuration to original
     *
     * @return $this
     */
    public function reset();
}