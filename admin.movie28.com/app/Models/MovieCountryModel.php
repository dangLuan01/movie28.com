<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieCountryModel extends Model
{
    public function __construct()
    {
        $this->table = config('constants.TABLE_MOVIE_COUNTRY');
        parent::__construct();
    }
}
