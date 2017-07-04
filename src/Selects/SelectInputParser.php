<?php

namespace Olymbytes\H3pha3stus\Selects;

use Olymbytes\H3pha3stus\Contracts\InputParser as InputParserContract;

/**
 * A class that determines which fields can be selected/unselected...according
 * to the following requirements:
 *  - an input parameter, `fields`, indicates the fields to select/unselect
 *  - `fields` must provide one or more comma-separated field names
 *  - an empty/non-existant `fields` parameter results in the default set of
 *    fields being returned
 *  - an exclamation mark, `!`, preceding a field name can be used to indicate
 *    the desire to *exclude* the specified field
 *  - unrecognized fields will be ignored
 *  - duplicate fields (with or without `!`) may yield undependable results,
 *    although the first "inclusive" will likely be favored
 *  - if *only* fields prepended by exclamation marks, `!`, are present, then
 *    *all* selectable fields will be returned *except* for those specified
 *  - order cannot be depended upon
 *
 * @todo Do we need/want to implement this: fields may indicate "nested" fields, where applicable/available by using the decimal point: `field.nested-field`
 */
class SelectInputParser implements InputParserContract
{
    const INPUT_KEY = 'fields';

    protected $defaultSelectedFields = [];

    protected $selectableFields = [];

    public function __construct(array $selectableFields = [], array $defaultSelectedFields = [])
    {
        $this->setSelectableFields($selectableFields);
        $this->setDefaultSelectedFields($defaultSelectedFields);
    }

    public function setSelectableFields(array $selectableFields)
    {
        $this->selectableFields = $selectableFields;

        return $this;
    }

    public function setDefaultSelectedFields(array $defaultSelectedFields)
    {
        $this->defaultSelectedFields = $defaultSelectedFields;

        return $this;
    }

    public function parse(array $input)
    {
        return array_values(array_unique($this->buildSelectedFields(
            $this->getRequestedFieldSelection($input)
        )));
    }

    protected function getRequestedFieldSelection(array $input)
    {
        if (array_key_exists(self::INPUT_KEY, $input)) {
            return explode(',', $input[self::INPUT_KEY]);
        }

        return [];
    }

    protected function buildSelectedFields(array $fieldSelection)
    {
        if ($this->isBlacklistFieldSelection($fieldSelection)) {
            return $this->buildBlacklist($fieldSelection);
        }

        return $this->buildWhitelist($fieldSelection);
    }

    /**
     * Determines whether or not the field selection list represents a
     * "blacklist". That is, are *only* excluded fields listed (those prepended
     * by exclamation marks)...?
     */
    protected function isBlacklistFieldSelection(array $fieldSelection)
    {
        $isBlacklist = true;
        foreach ($fieldSelection as $field) {
            $isBlacklist = ((0 === strlen($field)) || ($field[0] === '!'));
            if (!$isBlacklist) {
                break;
            }
        }

        return $isBlacklist;
    }

    /**
     * Return the *default selected* fields list *less* the specified
     * "blacklist" fields.
     *
     * @param  array  $fieldSelection [description]
     * @return [type]                 [description]
     */
    protected function buildBlacklist(array $fieldSelection)
    {
        return array_diff($this->defaultSelectedFields, array_map(function ($field) {
            return substr($field, 1);
        }, $fieldSelection));
    }

    /**
     * Return *only* the fields specified in the list of requested fields that
     * do *not* have an exclamation mark and are also found in the Model's list
     * of selectable fields. Make sure array keys are "reordered".
     *
     * @param  array  $fieldSelection [description]
     * @return [type]                 [description]
     */
    protected function buildWhitelist(array $fieldSelection)
    {
        $selectedFields = array_where($fieldSelection, function ($value, $key) {
            return ($key[0] !== '!');
        });
        return array_intersect($selectedFields, $this->selectableFields);
    }
}
