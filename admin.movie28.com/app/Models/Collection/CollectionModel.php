<?php

namespace App\Models\Collection;

use App\Models\BackendModel;
use App\Models\Movie\MovieCollectionModel;
use App\Models\Movie\ProductModel;
use Tes\LaravelGoogleDriveStorage\GoogleDriveService;

class CollectionModel extends BackendModel
{
    protected $fillable = ['id'];
    public $crudNotAccepted = ['movie_id'];
    public function __construct()
    {
        $this->table = config('constants.TABLE_COLLECTION');
        parent::__construct();
    }
    public function listItem($params = null, $options = null){
        if ($options['task'] == 'admin-index') {
            $result = self::select($this->table . '.id', $this->table . '.name', $this->table . '.image', $this->table . '.status')->orderBy($this->table . '.id', 'desc')->paginate(20);
            if ($result) {
                $this->_data['items'] = $result;
                $this->_data['total'] = $result->total();
            }
        }
        return $this->_data;
    }
    public function saveItem ($params = null, $options = null){
        if ($options['task'] == 'add-item') {
           
            $params['image'] =  null;
            if (request()->hasFile('image')) {
                $image   = request()->file('image');
                $reponse = GoogleDriveService::uploadFile($image, env('GOOGLE_DRIVE_FOLDER_ID'));
                if (!$reponse->id) {
                    return response()->json(array('success' => false, 'msg' => 'Thêm yêu cầu thất bại!'));
                }
                $params['image'] = 'https://drive.google.com/uc?id=' . $reponse->id;
            }
            $params['inserted_id'] = $this->insertGetId($this->prepareParams($params));
            $this->movie_collection = new MovieCollectionModel();
            $this->movie_collection->saveItem($params, ['task' => 'add-item']);
        }
        if ($options['task'] == 'edit-item') {
            if (request()->hasFile('image')) {
                $image   = request()->file('image');
                $reponse = GoogleDriveService::uploadFile($image, env('GOOGLE_DRIVE_FOLDER_ID'));
                if (!$reponse->id) {
                    return response()->json(array('success' => false, 'msg' => 'Thêm yêu cầu thất bại!'));
                }
                $params['image'] = 'https://drive.google.com/uc?id=' . $reponse->id;
            }
            $collection = $this->find($params[$this->primaryKey]);
            $collection->movies()->sync($params['movie_id']);
            $this->where($this->table . '.id', $params['id'])->update($this->prepareParams($params));

        }
    }
    public function getItem($params = null, $options = null){
        if ($options['task'] == 'get-info') {
            $result = self::with('movies')->find($params['id']);
            if ($result) {
                $this->_data = $result;
            }
        }
        return $this->_data;
    }
    public function movies(){
        return $this->belongsToMany(ProductModel::class , config('constants.TABLE_MOVIE_COLLECTION'), 'collection_id','movie_id');
    }

}
