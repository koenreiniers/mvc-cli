<?php
namespace Kr\MvcCli\MiniCli;

use Kr\MvcCli\Directive\TagInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Kr\MvcCli\Style\MvcCliStyle;

abstract class Command
{
    protected $name;
    protected $description;
    protected $inputDefinition;

    abstract public function configure();

    /**
     * @param InputInterface $input
     * @param MvcCliStyle $io
     * @param TagInterface $tag
     *
     * @return boolean
     */
    abstract public function run(InputInterface $input, MvcCliStyle $io, TagInterface $tag);

    /**
     * Command constructor.
     */
    public function __construct()
    {
        $this->inputDefinition = new InputDefinition([new InputArgument("command", InputArgument::REQUIRED)]);
        $this->configure();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param $name
     * @param null $mode
     * @param string $description
     * @param null $default
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        $this->inputDefinition->addArgument(new InputArgument($name, $mode, $description, $default));
    }

    /**
     * @param $name
     * @param null $shortcut
     * @param null $mode
     * @param string $description
     * @param null $default
     */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        $this->inputDefinition->addOption(new InputOption($name, $shortcut, $mode, $description, $default));
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return $this->inputDefinition;
    }
}