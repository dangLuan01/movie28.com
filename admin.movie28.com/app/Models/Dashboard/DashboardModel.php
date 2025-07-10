<?php

namespace App\Models\Dashboard;

use App\Jobs\ProcessAutoSaveMovie;
use App\Models\BackendModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use LDAP\Result;

class DashboardModel extends BackendModel
{
    public $data = [
            'Discover-Movie'    => 'https://api.themoviedb.org/3/discover/movie',
            'Now Playing-Movie' => 'https://api.themoviedb.org/3/movie/now_playing',
            'Popular-Movie'     => 'https://api.themoviedb.org/3/movie/popular',
            'Trending-Movie'    => 'https://api.themoviedb.org/3/trending/movie/day',
            'Discover-TV'       => 'https://api.themoviedb.org/3/discover/tv',
            'Now Playing-TV'    => 'https://api.themoviedb.org/3/tv/now_playing',
            'Popular-TV'        => 'https://api.themoviedb.org/3/tv/popular',
            'Trending-TV'       => 'https://api.themoviedb.org/3/trending/tv/day'
    ];
    public function __construct()
    {
        //$this->table        = TABLE_MOVIE;
        parent::__construct();
    }
    public function listItem($params = null, $options = null)
    {

        // if ($options['task'] == "admin-index") {
        //     $query = self::get();
        //     $this->_data['items'] = $query;
        // }
        return $this->_data;
    }
    public function getItem($params = null, $options = null){
        $result = null;
        if ($options['task'] == 'get-movie-list') {
          
            $result = Http::withHeaders([
                'Authorization' => env('TMDB_TOKEN'),
                'accept' => 'application/json'
            ])->get($params['url']);
            
        }
        return $result->json();
    }
    public function saveItem($params = null, $options = null){
        if($options['task'] == 'save-auto-movie'){
            foreach ($params['movieUrls'] as $url) {
                ProcessAutoSaveMovie::dispatch($url);
            }            
        }
        return response()->json(['Wating...'],200);
    }
}
