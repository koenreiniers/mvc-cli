<?php
namespace Kr\MvcCli\Directive;

use Kr\MvcCli\Directive\TagInterface;
use Kr\MvcCli\Directive\Input;
use Kr\MvcCli\Directive\Output;
use Kr\MvcCli\Directive\Control;

class TagRegistry
{
    const RESERVED_TAGS = "view|section";

    public function __construct()
    {
        $this->tags = [
            "ul"        => new Output\Listing(),

            "ask"       => new Input\Ask(),
            "confirm"   => new Input\Confirm(),

            "form"      => new Input\Form(),
        ];
        $directives = [
            Output\Heading::class,
            Output\Message::class,
            Input\Choice::class,
            Input\AnyKey::class,
            Control\Nav::class,
            Control\Redirect::class,
            Control\ExitTag::class,
            Output\Table::class,
        ];
        foreach($directives as $directive)
        {
            $this->add($directive);
        }
    }

    /**
     * Return array of reserved tags
     * @return array
     */
    public function getReservedTags()
    {
        return explode("|", self::RESERVED_TAGS);
    }

    /**
     * Add a new tag to the registry
     *
     * @param TagInterface $directive
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function add($directive)
    {
        $reservedTags = $this->getReservedTags();

        $tags = $directive::getTags();
        foreach($tags as $tag)
        {
            $tag = strtolower($tag);
            if(in_array($tag, $reservedTags)) {
                throw new \Exception("{$tag} is reserved");
            }

            $this->tags[$tag] = $directive;
        }
        return $this;
    }

    /**
     * Return all tags
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Retrieve tag by html tag name
     *
     * @param string $tagName
     *
     * @return TagInterface|null
     *
     * @throws \Exception
     */
    public function get($tagName)
    {
        $tags = $this->getTags();
        if(!isset($tags[$tagName])) {
            throw new \Exception("Invalid tag: {$tagName}");
        }
        return $tags[$tagName];
    }
}