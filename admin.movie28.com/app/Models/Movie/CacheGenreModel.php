<?php

namespace App\Models\Movie;

use App\Models\BackendModel;
use Illuminate\Support\Facades\Redis;

class CacheGenreModel extends BackendModel
{
    function deleteItem($params = null, $options = null){
        if ($options['task'] == 'delete-item') {
            if (Redis::exists($params['key'])) {
                Redis::del($params['key']);
            }
        }
    }
}
