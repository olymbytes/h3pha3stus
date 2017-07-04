<?php

namespace Olymbytes\H3pha3stus\Filters;

use Olymbytes\H3pha3stus\Contracts\InputParser as InputParserContract;

/**
 * A class that determines which fields should be filtered and how they should
 * be filtered...according to the following requirements:
 *  - an input parameter, `filter`, indicates the fields by which to filter
 *  - `filter` must be a json-stringified array of objects
 *  - each of the objects must contain the following fields:
 *     - `key`: its value is the name of the field on which to filter
 *     - `value`: its value is the value by which it is filtered
 *  - each of the objects *may* contain the following field:
 *     - `operator`: its value is the operator by which it is filtered and must
 *       be one of the following: = (default), !=, >, <, >=, <=
 *  - unrecognized fields will be ignored
 *  - duplicate fields will be persisted
 *  - user/caller order of *keys* will be respected/preserved (@todo needs *direct* testing)
 *
 * @todo Do we need/want to implement this: fields may indicate "nested" fields, where applicable/available by using the decimal point: `field.nested-field`
 */
class FilterInputParser implements InputParserContract
{
    const INPUT_KEY = 'filter';

    const KEY_FIELD = 'key';

    const KEY_VALUE = 'value';

    const KEY_OPERATOR = 'operator';

    const OPERATOR_EQUAL = '=';

    const OPERATOR_NOT_EQUAL = '!=';

    const OPERATOR_LESS_THAN = '<';

    const OPERATOR_GREATER_THAN = '>';

    const OPERATOR_GREATER_THAN_OR_EQUAL = '>=';

    const OPERATOR_LESS_THAN_OR_EQUAL = '<=';

    const OPERATOR_VALUES = [
        self::OPERATOR_EQUAL,
        self::OPERATOR_NOT_EQUAL,
        self::OPERATOR_LESS_THAN,
        self::OPERATOR_GREATER_THAN,
        self::OPERATOR_GREATER_THAN_OR_EQUAL,
        self::OPERATOR_LESS_THAN_OR_EQUAL,
    ];

    const DEFAULT_OPERATOR = self::OPERATOR_EQUAL;

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
        /**
         * If no `filter` key, return empty array...
         */
        $filterData = $this->getFilterString($input);
        if (null === $filterData) {
            return [];
        }

        /**
         * If filter data isn't an array, return empty array...
         */
        if (!is_array($filterData)) {
            return [];
        }

        return $this->buildFilteringData($filterData);
    }

    protected function getFilterString(array $input)
    {
        return ($input[self::INPUT_KEY] ?? null);
    }

    protected function buildFilteringData(array $filterData)
    {
        $result = [];

        foreach ($filterData as $filterDatum) {
            /**
             * Get key/value, default to NULL if missing...
             */
            $key = ($filterDatum['key'] ?? null);
            $value = ($filterDatum['value'] ?? null);

            /**
             * Skip on missing key, missing value, or unrecognized key...
             */
            if ((null === $key) || (null === $value) || !in_array($key, $this->fields)) {
                continue;
            }

            /**
             * Get operator, default if missing or unrecognized...
             */
            $operator = ($filterDatum['operator'] ?? self::DEFAULT_OPERATOR);
            if (!in_array($operator, self::OPERATOR_VALUES) || is_array($value)) {
                $operator = self::DEFAULT_OPERATOR;
            }
            $result[] = [
                'key' => $key,
                'operator' => $operator,
                'value' => $value,
            ];
        }

        return array_values($result);
    }
}
