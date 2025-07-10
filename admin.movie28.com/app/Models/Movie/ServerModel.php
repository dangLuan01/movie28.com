<?php

namespace App\Models\Movie;

use App\Models\BackendModel;
use Illuminate\Database\Eloquent\Model;

class ServerModel extends BackendModel
{
    public function __construct()
    {
        $this->table = config('constants.TABLE_SERVER');
        parent::__construct();
    }
    public function listItem($params, $options){
        if ($options['task'] == 'list-item') {
            $query = self::select('id','name')->get();
            if ($query) {
                $this->_data = $query->toArray();
            }
        }
        return $this->_data;
    }
}
