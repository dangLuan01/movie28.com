<?php

namespace App\Models\Movie;

use App\Models\BackendModel;

class MovieCollectionModel extends BackendModel
{
    public function __construct()
    {
        $this->table = config('constants.TABLE_MOVIE_COLLECTION');
        parent::__construct();
    }
    public function saveItem($params = null, $options = null){
        if ($options['task'] == 'add-item') {
            $collection_id              = $params['inserted_id'];
            $params['movie_collection'] = array_map(function($movie_id) use( $collection_id ) {
                return [
                    'movie_id'      => $movie_id,
                    'collection_id' => $collection_id,
                ];
            },$params['movie_id']); 
            $this->insert($this->prepareParams($params['movie_collection']));
        }
    }

}
