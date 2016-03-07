<?php
namespace Kr\MvcCli\Command;

use Kr\MvcCli\MiniCli\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;

class PageCommand extends Command
{
    public function configure()
    {
        $this->setName("page");
        $this->setDescription("Change the current page");
        $this->addArgument("page", InputArgument::REQUIRED);
    }

    /**
     * @inheritdoc
     */
    public function run(InputInterface $input, MvcCliStyle $io, TagInterface $tag)
    {
        $tag->options['pagination']['page'] = $input->getArgument("page");

        return true;
    }
}