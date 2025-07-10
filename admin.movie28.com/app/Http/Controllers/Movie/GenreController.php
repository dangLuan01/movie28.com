<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Movie\GenreModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GenreController extends AdminController
{
    protected $data = [];
    public $model;
    public function __construct(Request $request)
    {
        $this->model                        = new GenreModel();
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        //$this->_params["item-per-page"]     = $this->getCookie('-item-per-page', 25);
        $this->_params['model']             = $this->model->listItem($this->_params, ['task' => "admin-index"]);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function edit($id)
    {
        $this->_params[$this->model->columnPrimaryKey()] = $id;
        $this->_params['item']      = $this->model->getItem($this->_params, ['task' => 'get-item-info']);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function update()
    {
        if (isset($this->_params['_method']) && $this->_params['_method'] == 'PUT') {
            $this->model->saveItem($this->_params, ['task' => 'edit-item']);
        }
        return response()->json(array('success' => true, 'message' => 'Cập nhật thành công'));
    }
    public function status($status, $id)
    {
        $this->_params['status']    = $status;
        $this->_params['id']        = $id;
        $this->model->saveItem($this->_params, ['task' => 'change-status']);
        
        return redirect()->back();
    }
}
