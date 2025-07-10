<?php

namespace App\Http\Controllers\Elasticsearch;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Elasticsearch\ElasticsearchModel;
use Elasticsearch\Client;
use Illuminate\Http\Request;
class ElasticsearchController extends Controller
{
    protected Client $elasticsearch;
    public $es, $model;
    public function __construct(Client $elasticsearch)
    {
        $this->es       = $elasticsearch;
        $this->model    = new ElasticsearchModel($elasticsearch);
    }
    public function search(Request $request)
    {
        // Logic to search data in Elasticsearch
        $params['q'] = $request->input('q');
        $results = $this->model->searchItem($params, ['task' => 'search-item']);
        return response()->json($results);
    }
    public function store($params, $id)
    {
        // Logic to store data in Elasticsearch
        $results = $this->model->saveItem($params, ['task' => 'add-item', 'id' => $id]);
        if (!$results) {
            return response()->json(['success' => false, 'message' => 'Failed to index data'], 500);
        }
        return response()->json(['success' => true, 'message' => 'Data indexed successfully']);
    }
    public function update($params, $id)
    {
        // Logic to update data in Elasticsearch
        $results = $this->model->saveItem($params, ['task' => 'edit-item', 'id' => $id]);
        if (!$results) {
            return response()->json(['success' => false, 'message' => 'Failed to update data'], 500);
        }
        return response()->json(['success' => true, 'message' => 'Data updated successfully']);
    }
    
}
