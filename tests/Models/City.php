<?php 

namespace Olymbytes\H3pha3stus\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Olymbytes\H3pha3stus\Traits\Sortable;
use Olymbytes\H3pha3stus\Traits\Filterable;
use Olymbytes\H3pha3stus\Traits\FieldSearchable;
use Olymbytes\H3pha3stus\Traits\FieldSelectable;

class City extends Model
{
    use FieldSearchable;
    use FieldSelectable;
    use Filterable;
    use Sortable;
    
    protected $table = 'cities';

    protected $guarded = [];

    public function getFilterableFields()
    {
        return [
            'code',
        ];
    }

    public function getSearchableFields()
    {
        return [
            'cities.code',
            'cities.name',
        ];
    }

    public function getSelectableFields()
    {
        return [
            'id',
            'code',
            'name',
            'created_at',
            'updated_at',
        ];
    }

    public function getDefaultSelectedFields()
    {
        return [
            'id',
            'code',
            'name',
            'created_at',
            'updated_at',
        ];
    }

    public function getSortableFields()
    {
        return [
            'code',
            'name',
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}