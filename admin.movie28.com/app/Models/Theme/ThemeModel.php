<?php

namespace App\Models\Theme;

use App\Models\BackendModel;
use Illuminate\Database\Eloquent\Model;

class ThemeModel extends BackendModel
{
    public $data = [
        'type'=> [
            'Movie'     => 'single',
            'TV-Series' => 'series',
            'Animate'   => 'hoathinh',
        ],
        'layout' => [
            'Paginate'      => 1,
            'Owl-Carousel'  => 2
        ]
    ];
     protected $fillable = ['id'];
    //public $crudNotAccepted = ['movie_id'];
    public function __construct()
    {
        $this->table = config('constants.TABLE_THEME');
        parent::__construct();
    }
    public function listItem($params = null, $options = null){
        if ($options['task'] == 'admin-index') {
            $result = $this->orderBy("priority", "Asc")->paginate(20);
            if ($result) {
                $this->_data['items']   = $result;
                $this->_data['total']   = $result->total();
            }
        }
        return $this->_data;
    }
    public function getItem($params = null, $options = null){
        if ($options['task'] == 'get-info') {
            $result = $this->find($params['id']);
            if ($result) {
                $this->_data = $result->toArray();
            }
        }
        return $this->_data;
    }
    public function saveItem ($params = null, $options = null){
        if ($options['task'] == 'add-item') {
            $this->insert($this->prepareParams($params));
        }
        if ($options['task'] == 'edit-item') {
            $this->where($this->table . '.id', $params['id'])->update($this->prepareParams($params));
        }
        if ($options['task'] == 'change-status') {
            $status = ($params['status'] == "1") ? '0' : '1';
            $this->where($this->columnPrimaryKey(), $params[$this->columnPrimaryKey()])->update(['status' => $status]);
        }
    }
}
