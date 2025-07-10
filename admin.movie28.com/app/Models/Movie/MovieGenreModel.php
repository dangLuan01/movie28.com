<?php

namespace App\Models\Movie;

use Illuminate\Database\Eloquent\Model;

class MovieGenreModel extends Model
{
    public function __construct()
    {
        $this->table = config('constants.TABLE_MOVIE_GENRE');
        parent::__construct();
    }
}
