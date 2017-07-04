<?php

namespace Olymbytes\H3pha3stus\Traits;

use Olymbytes\H3pha3stus\Searches\SearchInputParser;

/**
 * A trait that implements a scope making it possible to search fields according
 * to the following requirements:
 *  - searchable fields are supplied by the class in which this trait is used
 *  - input is supplied, which must contain the specified search strings
 *  - each search string will be ORed against all searchable fields
 *  - each search string will be ANDed with the other search strings
 *
 * Example (searchable fields: first_name, last_name): q=['john','doe']
 *  - `john` is in `first_name` OR `john` is in `last_name`
 *      *AND*
 *  - `doe` is in `first_name` OR `doe` is in `last_name`.
 *
 * Example (searchable fields: first_name, last_name, relation_field): q=['john','doe','henry']
 *  - `john` is in `first_name` OR `john` is in `last_name` OR `john` is in `relation_field`
 *      *AND*
 *  - `doe` is in `first_name` OR `doe` is in `last_name` OR `doe` is in `relation_field`
 *      *AND*
 *  - `henry` is in `first_name` OR `henry` is in `last_name` OR `henry` is in `relation_field`
 */
trait FieldSearchable
{
    /**
     * The attributes that can be used to search.
     *
     * @return array
     */
    abstract public function getSearchableFields();

    /**
     * Scope a query to search by field.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFieldSearchable($query, array $input)
    {
        $searchStrings = (new SearchInputParser())->parse($input);
        $searchableFields = $this->getSearchableFields();

        foreach ($searchStrings as $searchString) {
            $query->where(function ($query) use ($searchString, $searchableFields) {
                foreach ($searchableFields as $searchableField) {
                    $preppedSearchString = '%' . $searchString . '%';
                    $query->orWhere($searchableField, 'like', $preppedSearchString);
                }
            });
        }

        return $query;
    }
}
