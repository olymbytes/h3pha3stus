<?php 

namespace Olymbytes\H3pha3stus\Test\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $guarded = [];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}