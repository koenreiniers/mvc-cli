<?php
namespace Kr\MvcCli\Directive;

use Kr\MvcCli\Style\MvcCliStyle;

abstract class AbstractTag implements TagInterface
{
    /** @var \DOMElement */
    protected $dom;

    /** @var array */
    public $options;

    /** @var array */
    protected $originalOptions;

    /** @var array */
    protected $defaultOptions;

    /** @var string */
    protected $name;

    /**
     * Renders the element
     *
     * @param MvcCliStyle $io
     */
    abstract protected function render(MvcCliStyle $io);

    /**
     * Configure the tag
     */
    abstract protected function configure();

    /**
     * TODO: Decouple from DOM
     * Load options from dom element
     *
     * @param \DOMElement $dom  - The DOM element from which to configure
     * @param array $options    - Contains the default options for this tag
     *
     * @return array
     */
    abstract protected function loadDom(\DOMElement $dom, array $options = []);

    /**
     * @inheritdoc
     */
    public static function getTags()
    {
        return array_map("strtolower", explode("|", static::TAGS));
    }

    /**
     * AbstractTag constructor.
     * @param \DOMElement $dom
     */
    public function __construct(\DOMElement $dom)
    {
        $this->dom = $dom;
    }

    /**
     * Sets the default options
     *
     * @param array $options
     *
     * @return $this
     */
    protected function setDefaultOptions(array $options = [])
    {
        $this->defaultOptions = $options;
        return $this;
    }

    /**
     * Return array of default options for this tag
     *
     * @return mixed
     */
    protected function getDefaultOptions()
    {
        return $this->defaultOptions;
    }

    /**
     * @inheritdoc
     */
    public static function create(\DOMElement $dom)
    {
        return new static($dom);
    }

    /**
     * Load full configuration based on view element and default options
     */
    protected function mergeOptions()
    {
        $this->configure();

        $options = $this->loadDom($this->getDom(), $this->getDefaultOptions());
        $this->setOptions($options);
        $this->setOriginalOptions($options);
    }

    /**
     * Return the corresponding dom element
     * @return \DOMElement
     */
    protected function getDom()
    {
        return $this->dom;
    }

    /**
     * @inheritdoc
     */
    public function execute(MvcCliStyle $io)
    {
        $this->mergeOptions();
        return $this->render($io);
    }

    /**
     * Sets the original options of this tag (default + dom)
     *
     * @param array $options
     *
     * @return $this
     */
    protected function setOriginalOptions(array $options = [])
    {
        $this->originalOptions = $options;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOriginalOptions()
    {
        return $this->originalOptions;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        $this->setOptions($this->getOriginalOptions());
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    protected function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}