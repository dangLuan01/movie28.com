<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackendModel extends Model
{
    protected $table            = '';
    protected $folderUpload     = '' ;
    protected $connection       = 'mysql';
    protected $crudNotAccepted  = [
                                    '_token',
                                    'prefix',
                                    'controller',
                                    'action',
                                    'as',
                                    '_method',
                                    'ID',
                                    ];
    protected $_data        = [];
    public $timestamps      = false;
    public $checkall        = true;

    public function __construct($connection = 'mysql')
    {
        $this->connection = $connection;
        $this->primaryKey = $this->columnPrimaryKey();
    }
    public function columnPrimaryKey($key = 'id')
    {
        return $key;
    }
    public function prepareParams($params)
    {
        $crudNotAccepted = [
            '_token',
            'prefix',
            'controller',
            'action',
            'as',
            '_method',
            'totalItemsPerPage'
        ];
        $crudNotAccepted = array_merge($this->crudNotAccepted, $crudNotAccepted);
        
        return array_diff_key($params, array_flip($crudNotAccepted));
    }
}
