<?php

namespace App\Models\Movie;

use App\Models\BackendModel;
use Tes\LaravelGoogleDriveStorage\GoogleDriveService;

class GenreModel extends BackendModel
{
    public function __construct()
    {
        $this->table = config('constants.TABLE_GENRE');
        parent::__construct();
    }
    public function listItem($params = null, $options = null){
        $result = null;
        if ($options['task'] == 'get-info') {
            $result = self::select($this->table . '.id', $this->table . '.name')->get();
            if ($result) {
                $this->_data = $result->toArray();
            }
        }
        if ($options['task'] == 'admin-index') {
            $result = self::select($this->table . '.id', $this->table . '.name', $this->table . '.image', $this->table . '.status')->orderBy($this->table . '.id', 'desc')->paginate(20);
            if ($result) {
                $this->_data['items'] = $result;
                 $this->_data['total'] = $result->total();
            }
        }
        return $this->_data;
    }
    public function getItem($params = null, $options = null)
    {   
        $result = null;
        if ($options['task'] == 'get-item-info') {
            $result = self::where($this->table . '.id', $params['id'])->first();
        }
        return $result;
    } 
    public function saveItem($params = null, $options = null){
        if ($options['task'] == 'edit-item') {
            if (request()->hasFile('image')) {
                $image   = request()->file('image');
                $reponse = GoogleDriveService::uploadFile($image, env('GOOGLE_DRIVE_FOLDER_ID'));
                if (!$reponse->id) {
                    return response()->json(array('success' => false, 'msg' => 'Thêm yêu cầu thất bại!'));
                }
                $params['image'] = 'https://drive.google.com/uc?id=' . $reponse->id;
            }
            $this->where($this->table . '.id', $params['id'])->update($this->prepareParams($params));
            $params['key']      = "genre-homepage";
            $this->cacheGenre   = new CacheGenreModel();
            $this->cacheGenre->deleteItem($params, ['task' => 'delete-item']);
        }
        if ($options['task'] == 'change-status') {
            $params['status'] = ($params['status'] == "1") ? '0' : '1';
            $this->where($this->table . '.id', $params['id'])->update(['status' => $params['status']]);
        }
    }
}
