<?php

namespace Olymbytes\H3pha3stus\Sorts;

use Olymbytes\H3pha3stus\Contracts\InputParser as InputParserContract;

/**
 * A class that determines which fields should be sorted and how they should be
 * sorted...according to the following requirements:
 *  - an input parameter, `sort`, indicates the fields to sort on
 *  - `sort` must provide one or more comma-separated fields
 *  - an empty/non-existant `sort` parameter results in *no* custom sorting
 *  - sort direction can be explicitly defined by appending a pipe, `|`, to the
 *    field name followed by one of the following:
 *     - `asc`: ascending
 *     - `desc`: descending
 *  - default direction for all fields will be `asc`
 *  - unrecognized fields will be ignored
 *  - duplicate fields will favor the last
 *
 * @todo Do we need/want to implement this: fields may indicate "nested" fields, where applicable/available by using the decimal point: `field.nested-field`
 * @todo Make a parent class/interface that it has to inherit/apply to.
 */
class SortInputParser implements InputParserContract
{
    const INPUT_KEY = 'sort';

    const DIR_ASC = 'asc';

    const DIR_DESC = 'desc';

    const DIR_VALUES = [
        self::DIR_ASC,
        self::DIR_DESC,
    ];

    const DEFAULT_DIR = self::DIR_ASC;

    protected $fields = [];

    public function __construct(array $fields = [])
    {
        $this->setFields($fields);
    }

    public function setFields(array $fields)
    {
        if ($fields !== $this->fields) {
            $this->fields = $fields;
        }

        return $this;
    }

    public function parse(array $input)
    {
        return $this->buildSortingData($this->getRequestedSortFields($input));
    }

    protected function getRequestedSortFields(array $input)
    {
        if (array_key_exists(self::INPUT_KEY, $input)) {
            return explode(',', $input[self::INPUT_KEY]);
        }

        return [];
    }

    protected function buildSortingData(array $sortData)
    {
        $result = [];

        foreach ($sortData as $sortDatum) {
            $parts = explode('|', $sortDatum);

            $dir = ($parts[1] ?? self::DEFAULT_DIR);
            if (!in_array($dir, self::DIR_VALUES)) {
                $dir = self::DEFAULT_DIR;
            }

            $field = $parts[0];
            if (in_array($field, $this->fields)) {
                $result[$field] = $dir;
            }
        }

        return $result;
    }
}
