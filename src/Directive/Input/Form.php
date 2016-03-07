<?php
namespace Kr\MvcCli\Directive\Input;

use Kr\MvcCli\Directive\OldTagInterface;
use Kr\MvcCli\Style\MvcCliStyle;

class Form implements OldTagInterface
{
    /**
     * @TODO: Multiple validators + different error messages
     * @inheritdoc
     */
    public static function render(MvcCliStyle $io, \DOMElement $form)
    {
        $question = null;
        $default = null;
        $validator = null;

        $inputs = [];

        // Get the form name
        $formName = $form->getAttribute("name");

        // First parse all the input tags
        foreach($form->childNodes as $child) {
            if($child instanceof \DOMElement && $child->nodeName === "input") {

                $input = [];
                $input['label'] = $child->getAttribute("name");
                $input['name']  = $child->getAttribute("name");
                $input['type']  = $child->getAttribute("type");
                $input['value'] = $child->getAttribute("value");
                $input['validator'] = function($value){
                    return $value;
                };

                $inputs[] = $input;
            }
        }

        // Then the labels
        foreach($form->childNodes as $child)
        {
            if($child instanceof \DOMElement && $child->nodeName === "label") {

                $for = $child->getAttribute("for");
                // Find corresponding input
                foreach($inputs as &$input) {
                    if($input['name'] !== $for) {
                        continue;
                    }
                    $input['label'] = $child->nodeValue;
                }
                unset($input);
            }
        }

        self::displayForm($io, $formName, $inputs);


        return self::submit($io, $formName, $inputs);
    }

    public static function displayForm(MvcCliStyle $io, $formName, &$inputs)
    {
        // Prompt for input
        foreach($inputs as &$input)
        {
            if($input['type'] == "hidden") {
                $input['value'] = $io->askHidden($input['label'], $input['validator']);
            } else {
                $input['value'] = $io->ask($input['label'], $input['value'], $input['validator']);
            }
        }
        unset($input);

        $headers = ["Key", "Value"];
        $rows = [];
        foreach($inputs as $input) {
            $rows[] = [$input['label'], $input['value']];
        }
        $io->table($headers, $rows);

        $submit = $io->confirm("Do you want to submit the above values?");
        if(!$submit) {
            self::displayForm($io, $formName, $inputs);
        }
    }

    public static function submit(MvcCliStyle $io, $formName, $inputs)
    {
        $results = [];

        // Format result array (key => value)
        foreach($inputs as $input)
        {
            $results[$input['name']] = $input['value'];
        }

        $io->success("Success!");

        if($formName != null) {
            $results = [
                $formName => $results,
            ];
        }

        return $results;
    }
}