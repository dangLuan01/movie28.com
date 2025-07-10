<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\ProductRequest;
use App\Models\Movie\CountryModel;
use App\Models\Movie\CrawlerModel;
use App\Models\Movie\GenreModel;
use App\Models\Movie\ProductModel;
use Illuminate\Http\Request;

class ProductController extends AdminController
{
    
    protected $data = [];
    public $model;
    public function __construct(Request $request)
    {
        $this->model                        = new ProductModel();
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        //$this->_params["item-per-page"]     = $this->getCookie('-item-per-page', 25);
        $this->_params['model']             = $this->model->listItem($this->_params, ['task' => "admin-index"]);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function create(){
        $this->genre             = new GenreModel();
        $this->_params['genres'] = $this->genre->listItem($this->_params, ['task' => 'get-info']);
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function store(ProductRequest $request){
        $this->model->saveItem($this->_params, ['task' => 'add-item']);
        return response()->json(array('success' => true, 'message' => 'Thêm thành công'));
    }
    public function edit($id)
    {
        $this->_params[$this->model->columnPrimaryKey()] = $id;
        $this->genre                = new GenreModel();
        $this->_params['genres']    = $this->genre->listItem($this->_params, ['task' => 'get-info']);
        $this->_params['item']      = $this->model->getItem($this->_params, ['task' => 'get-item-info']);
       
        return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function update(ProductRequest $request)
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
    public function destroy(Request $request)
    {
        // $this->_params['id'] = $request->input('id');
        // $this->model->deleteItem($this->_params, ['task' => 'delete-item']);
        // return response()->json(array('success' => true, 'message' => 'Cập nhật thành công'));
    }
    public function crawler(){
       return view($this->_viewAction, ['params' => $this->_params]);
    }
    public function crawlerData(Request $request){
        $this->crawler = new CrawlerModel();
        $this->_params['movies']  = $this->crawler->listItem($this->_params, ['task' => 'crawler-data']);
        return response()->json(['data' => $this->_params['movies']]);
    }
    public function storeCrawler(Request $request){
        $this->model->saveItem($this->_params, ['task' => 'add-item-crawler']);
        return response()->json(array('success' => true, 'message' => 'Thêm thành công'));
        
    }
}
