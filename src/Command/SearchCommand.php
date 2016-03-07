<?php
namespace Kr\MvcCli\Command;


use Kr\MvcCli\MiniCli\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;
class SearchCommand extends Command
{
    public function configure()
    {
        $this->setName("search");
        $this->setDescription("Search for a term");
        $this->addArgument("term", InputArgument::REQUIRED);
    }

    /**
     * @inheritdoc
     */
    public function run(InputInterface $input, MvcCliStyle $io, TagInterface $tag)
    {
        $tag->options['pagination']['page']  = 1;
        $tag->options['search']['term']      = $input->getArgument("term");

        return true;
    }
}