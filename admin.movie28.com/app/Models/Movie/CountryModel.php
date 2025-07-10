<?php

namespace App\Models\Movie;

use App\Models\BackendModel;
use Illuminate\Database\Eloquent\Model;

class CountryModel extends BackendModel
{
    public function __construct()
    {
        $this->table = config('constants.TABLE_COUNTRY');
        parent::__construct();
    }
    public function listItem($params = null, $options = null){
        if ($options['task'] == 'get-all') {
            $result = $this->get();
            if ($result) {
                $this->_data = $result->toArray();
            }
        }
        return $this->_data;
    }
    public function getItem($params = null, $options = null){
        $result = null;
        if ($options['task'] == 'get-all-country') {
            $result = self::select($this->table . '.id', $this->table . '.name')->get();
            if ($result) {
                $result = $result->toArray();
            }
        }
        return $result;
    }
}
