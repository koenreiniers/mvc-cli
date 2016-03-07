<?php
namespace Kr\MvcCli\Directive\Output;

use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\InteractiveTag;

class Table extends InteractiveTag
{
    const TAGS = "table";

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDefaultOptions([
            "interactive"   => false,
            "table" => [
                "headers"   => [],
                "rows"      => [],
            ],
            "pagination" => [
                "enabled"       => false,
                "page"          => 1,
                "itemsPerPage"  => 5,
            ],
            "sort" => [
                "by"        => null,
                "direction" => "ASC",
            ],
            "search" => [
                "term"      => null,
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function loadDom(\DOMElement $dom, array $options = [])
    {
        $options['interactive'] = $dom->getAttribute("interactive");
        $options['pagination']['enabled'] = $dom->getAttribute("pagination");

        // Get headers and rows
        $rows = $this->readTableRows($dom);
        $headers = array_shift($rows);
        $options['table'] = [
            "headers"   => $headers,
            "rows"      => $rows,
        ];

        return $options;
    }

    /**
     * @inheritdoc
     */
    protected function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();
        $headers = $options['table']['headers'];
        $rows    = $options['table']['rows'];

        if($options['search']['term'] !== null) {
            $searchterm = $options['search']['term'];
            $rows = $this->search($searchterm, $rows);
            $io->text(count($rows) . " result(s) for {$searchterm}:");
        }

        $this->sort($rows, $options['sort']['by'], $options['sort']['direction']);

        // Pagination
        if($options['pagination']['enabled']) {
            $itemsPerPage   = $options['pagination']['itemsPerPage'];
            $amountOfElements = count($rows);
            $maxPage = ceil($amountOfElements / $itemsPerPage);

            if($options['pagination']['page'] > $maxPage) {
                $options['pagination']['page'] = $maxPage;
            }

            $start = ($options['pagination']['page'] -1) * $itemsPerPage;
            $rows = array_slice($rows, $start, $itemsPerPage);
        }

        // Show the table
        $io->table($headers, $rows);

        if($options['pagination']['enabled']) {
            $io->comment("Page {$options['pagination']['page']} of {$maxPage}. Showing ".min($itemsPerPage, count($rows))." of ".count($options['table']['rows'])." items");
        }
    }

    /**
     * TODO: Move to another service
     * Returns array of array of columns of a table
     *
     * @param \DOMElement $table
     *
     * @return array
     */
    public function readTableRows(\DOMElement $table)
    {
        $rows = [];
        $rowIndex = 0;

        foreach($table->childNodes as $row) {
            foreach($row->childNodes as $column)
            {
                if($column->nodeName == "td" || $column->nodeName == "th") {
                    $rows[$rowIndex][] = $column->nodeValue;
                }

            }
            $rowIndex++;
        }

        return $rows;
    }

    /**
     * Return rows that have a match with the searchterm
     *
     * @param string $searchterm
     * @param array $rows
     *
     * @return array
     */
    private function search($searchterm, $rows)
    {
        $matchedRows = [];
        foreach($rows as $row)
        {
            $match = false;
            foreach($row as $column) {
                if(strpos($column, $searchterm) !== false) {
                    $match = true;
                }
            }
            if($match) {
                $matchedRows[] = $row;
            }
        }
        return $matchedRows;
    }


    /**
     * Sort rows according to options
     *
     * @param array $rows
     * @param array $options
     */
    private function sort(array &$rows, $sortBy, $direction)
    {
        // TODO: sortBy => columnIndex
        $columnIndex    = $sortBy;

        if($columnIndex === null) {
            return;
        }

        if($direction == "DESC") {
            usort($rows, function($a, $b) use($columnIndex) {
                return $a[$columnIndex] < $b[$columnIndex];
            });
        } else {
            usort($rows, function($a, $b) use($columnIndex) {
                return $a[$columnIndex] > $b[$columnIndex];
            });
        }
    }
}