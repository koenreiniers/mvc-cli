<?php
namespace Kr\MvcCli\Command;

use Kr\MvcCli\MiniCli\Command;
use Kr\MvcCli\Directive\TagInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Kr\MvcCli\Style\MvcCliStyle;

class ResetCommand extends Command
{
    public function configure()
    {
        $this->setName("reset");
        $this->setDescription("Reset to original state");
    }

    /**
     * @inheritdoc
     */
    public function run(InputInterface $input, MvcCliStyle $io, TagInterface $tag)
    {
        $tag->reset();
        return true;
    }
}