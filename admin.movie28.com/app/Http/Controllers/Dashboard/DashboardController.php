<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Dashboard\DashboardModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends AdminController
{
    protected $data = [];
    public $model;
    public function __construct(Request $request)
    {
        $this->model    = new DashboardModel();
        parent::__construct($request);
    }
    public function index()
    {
        $this->model                    = new DashboardModel();
        $this->_params['listOption']    = $this->model->data;
       
        return view('dashboard.index', ['params' => $this->_params]);
    }
    public function getMovieTmdb(Request $request){
       
        $this->_params['items'] = $this->model->getItem($this->_params, ['task' => 'get-movie-list']);
        return response()->json(['params' => $this->_params]);
    }
    public function saveAutoMovie(){
        $filePath = storage_path('app/movie_urls.json'); // Đường dẫn file JSON

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File JSON không tồn tại'], 404);
        }
        
        $jsonContent = file_get_contents($filePath);
        $this->_params['movieUrls'] = json_decode($jsonContent, true);

        return $this->model->saveItem($this->_params, ['task' => 'save-auto-movie']);
    }
    public function test(){
        $result = Http::get('https://ophim1.com/phim/sieu-nang-lap-phuong-phan-1')->json();


        echo '<pre>';
        print_r($result['episodes'][0]['server_data']);
        echo '<pre>';
        die();
    }
}
