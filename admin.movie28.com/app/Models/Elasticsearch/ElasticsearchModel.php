<?php

namespace App\Models\Elasticsearch;

use App\Models\BackendModel;
use Elasticsearch\Client;

class ElasticsearchModel extends BackendModel
{
    // protected Client $elasticsearch;
    public $es;
    public function __construct($elasticsearch)
    {
        $this->es = $elasticsearch;
    }
    function searchItem($params = null, $options = null){
        $query = $params['q'];
        $params = [
            'index' => 'movies',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query'  => $query,
                        'fields' => ['name^2', 'origin_name'],
                    ],
                ],
            ],
        ];
        return $this->es->search($params);
    }
    public function saveItem($params = null, $options = null)
    {
       if ($options['task'] == 'add-item') {
            $this->es->index([
                'index' => 'movies',
                'id'    => $options['id'],
                'body'  => $params,
            ]);
            return true;
        }
        if ($options['task'] == 'edit-item') {
            
            $data = [
                'name'          => $params['name'],
                'origin_name'   => $params['origin_name'],
                'slug'          => $params['slug'],
                'runtime'       => $params['runtime'],
                'type'          => $params['type'],
                'age'           => $params['age'],
                'release_date'  => $params['release_date'],
            ];
            if ($params['poster'] != null) {
                $data['poster'] = $params['poster'];
            }
            $this->es->update([
                'index' => 'movies',
                'id'    => $options['id'],
                'body'  => [
                    'doc' => $data,
                ],
            ]);
        }
        return true;
    }
    
}
