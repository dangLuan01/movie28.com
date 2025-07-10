<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Collection\CollectionModel;
use App\Models\Movie\ProductModel;
use Illuminate\Http\Request;

class CollectionController extends AdminController
{
    protected $data = [];
    public $model, $movie;
    
    public function __construct(Request $request)
    {
        $this->model   = new CollectionModel();
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        //$this->_params["item-per-page"]     = $this->getCookie('-item-per-page', 25);
        $this->_params['model']             = $this->model->listItem($this->_params, ['task' => "admin-index"]);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function create(){
        $this->movie                = new ProductModel();
        $this->_params['movie']     = $this->movie->listItem($this->_params, ['task' => 'list-item']);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function store(Request $request){
        $this->model->saveItem($this->_params, ['task' => 'add-item']);
        return response()->json(array('success' => true, 'message' => 'Thêm thành công'));
    }
    public function edit($id){
        $this->_params['id']    = $id;
        $this->movie            = new ProductModel();
        $this->_params['movie'] = $this->movie->listItem($this->_params, ['task' => 'list-item']);
        $this->_params['item'] = $this->model->getItem($this->_params, ['task' => 'get-info']);
        return(view($this->_viewAction, ['params' => $this->_params]));
    }
    public function update(Request $request){
         if (isset($this->_params['_method']) && $this->_params['_method'] == 'PUT') {
            $this->model->saveItem($this->_params, ['task' => 'edit-item']);
        }
        return response()->json(array('success' => true, 'message' => 'Cập nhật thành công'));
    }
}
