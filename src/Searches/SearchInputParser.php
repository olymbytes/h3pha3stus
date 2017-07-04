<?php 

namespace Olymbytes\H3pha3stus\Searches;

use Olymbytes\H3pha3stus\Contracts\InputParser as InputParserContract;

class SearchInputParser implements InputParserContract
{
    const INPUT_KEY = 'q';

    public function parse(array $input)
    {
        /**
         * If no `search` key, return empty array...
         */
        $searchData = $this->getSearchString($input);
        if (null === $searchData) {
            return [];
        }

        /**
         * If search data isn't an array, return empty array...
         */
        if (!is_array($searchData)) {
            return [];
        }

        return $this->buildSearchingData($searchData);
    }

    protected function getSearchString(array $input)
    {
        return ($input[self::INPUT_KEY] ?? null);
    }

    protected function buildSearchingData(array $searchData)
    {
        $result = [];

        foreach ($searchData as $searchDatum) {
            /**
             * Only add if value is a string, skip otherwise...
             */
            if (is_string($searchDatum)) {
                $result[] = $searchDatum;
            }
        }

        return array_unique($result);
    }
}