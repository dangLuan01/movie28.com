<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\AdminController;
use App\Models\Movie\CountryModel;
use App\Models\Movie\GenreModel;
use App\Models\Theme\ThemeModel;
use Illuminate\Http\Request;

class ThemeController extends AdminController
{
    protected $data = [];
    public $model, $genre, $country;
    
    public function __construct(Request $request)
    {
        $this->model   = new ThemeModel();
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        //$this->_params["item-per-page"]     = $this->getCookie('-item-per-page', 25);
        $this->_params['model']             = $this->model->listItem($this->_params, ['task' => "admin-index"]);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
     public function create(){
        $this->genre                = new GenreModel();
        $this->country              = new CountryModel();
        $this->_params['genres']    = $this->genre->listItem($this->_params, ['task' => 'get-info']);
        $this->_params['countries'] = $this->country->listItem($this->_params, ['task' => 'get-all']);
        $this->_params['data']      = $this->model->data;

        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function store(Request $request){
        $this->model->saveItem($this->_params, ['task' => 'add-item']);
        return response()->json(array('success' => true, 'message' => 'Thêm thành công'));
    }
    public function edit($id){
        $this->_params['id']    = $id;
        $this->genre                = new GenreModel();
        $this->country              = new CountryModel();
        $this->_params['genres']    = $this->genre->listItem($this->_params, ['task' => 'get-info']);
        $this->_params['countries'] = $this->country->listItem($this->_params, ['task' => 'get-all']);
        $this->_params['item']      = $this->model->getItem($this->_params, ['task' => 'get-info']);
        $this->_params['data']      = $this->model->data;
       
        return(view($this->_viewAction, ['params' => $this->_params]));
    }
    public function update(Request $request){
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
