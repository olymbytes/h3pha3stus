<?php

namespace Olymbytes\H3pha3stus\Traits;

trait FieldSelectable
{
    /**
     * The attributes that can be selected.
     *
     * @return array
     */
    abstract public function getSelectableFields();

    /**
     * The attributes that are selected by default.
     *
     * @return array
     */
    abstract public function getDefaultSelectedFields();

    /**
     * Scope a query to select the specified fields.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectable($query, array $input)
    {
        $fieldsToSelect = new SelectInputParser(
            $this->getSelectableFields(),
            $this->getDefaultSelectedFields()
        )->parse($input);

        /**
         * Break out the fields by "direct" fields and "relationship" fields.
         */
        $tablePrefix = $this->getTable() . '.';
        $directFields = [];
        $relationshipFields = [];
        foreach ($fieldsToSelect as $field) {
            $parts = explode('.', $field);
            $count = count($parts);
            if (1 === $count) {
                $directFields[] = $tablePrefix . $parts[0];
            } elseif (2 === $count) {
                $relationshipFields[$parts[0]][] = $parts[1];
            }
        }

        /**
         * Add the "direct" fields to the select, then deal with "relationship"
         * fields.
         */
        $query->select($directFields);
        foreach ($relationshipFields as $relationshipKey => $relationshipFields) {
            $query->with([$relationshipKey => function ($query) use ($relationshipFields) {
                $query->select($relationshipFields);
            }]);
        }

        return $query;
    }
}
