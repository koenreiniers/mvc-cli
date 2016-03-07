<?php
namespace Kr\MvcCli\Templating;

use Kr\MvcCli\Controller\Scope;
use Kr\MvcCli\Directive\TagRegistry;

interface EngineInterface
{
    /**
     * The file extension for which to use this engine
     *
     * @return string
     */
    public function getFileExtension();

    /**
     * Render a view
     *
     * @param string $filePath
     *
     * @param Scope $scope
     */
    public function render($filePath, Scope $scope);

    /**
     * Set the tag registry
     *
     * @param TagRegistry $tagRegistry
     *
     * @return $this
     */
    public function setTagRegistry(TagRegistry $tagRegistry);
}