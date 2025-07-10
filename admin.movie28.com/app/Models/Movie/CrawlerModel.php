<?php

namespace App\Models\Movie;

use App\Models\BackendModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class CrawlerModel extends BackendModel
{
    public $searchUrl = 'https://ophim1.com/v1/api/tim-kiem?keyword=';
    public function __construct()
    {
        $this->table = config('constants.TABLE_MOVIE');
        parent::__construct();
    }
    public function listItem($params = null, $options = null){
        $result = [];
        if ($options['task'] == 'crawler-data') {

            if (!empty($params['page_from']) && !empty($params['page_to'])) {
                if (str_contains($params['url'], 'ophim1.com')) {
                    $responses = Http::pool(fn ($pool) => 
                        array_map(fn ($i) => 
                        $pool->get($params['url'] . '?page=' . $i), range($params['page_from'], $params['page_to']))
                    );
                    $result = array_merge(...array_map(fn ($response) => 
                        optional($response->json())['items'] ?? [], $responses
                    ));
                }
                else {
                    $responses = Http::pool(fn ($pool) => 
                        array_map(fn ($i) => 
                        $pool->get($this->searchUrl . $params['url'] . '?page=' . $i), range($params['page_from'], $params['page_to']))
                    );
                    $result = array_merge(...array_map(fn ($response) => 
                        optional($response->json())['data']['items'] ?? [], $responses
                    ));
                   
                }
            }
            
            $movies = collect($result)->map(fn ($response) => [
                'name'      => optional($response)['name'] ?? null,
                'slug'      => optional($response)['slug'] ?? null,
                'existed'   => 0,
            ])->toArray();

            $existingSlugs = self::whereIn('slug', array_column($movies, 'slug'))->pluck('slug')->toArray();
           
            $existingSlugs = array_flip($existingSlugs);
           
            foreach ($movies as &$movie) {
                if (isset($existingSlugs[$movie['slug']])) {
                    $movie['existed'] = 1;
                }
            }
            $result = $movies;
        }
        if ($options['task'] == 'crawler-data-episode') {

            if (!empty($params['page_from']) && !empty($params['page_to'])) {
                if (str_contains($params['url'], 'ophim1.com')) {
                    $responses = Http::pool(fn ($pool) => 
                        array_map(fn ($i) => 
                        $pool->get($params['url'] . '?page=' . $i), range($params['page_from'], $params['page_to']))
                    );
                    $results = array_merge(...array_map(fn ($response) => 
                        optional($response->json())['items'] ?? [], $responses
                    ));

                    $movieUrls = array_map(fn ($item) => 
                        "https://ophim1.com/phim/id/" . $item['_id'], $results
                    );

                    $movieResponses = Http::pool(fn ($pool) => 
                        array_map(fn ($url) => $pool->get($url), $movieUrls)
                    );
                    $result = [];
                    foreach ($results as $index => $item) {
                        $movieData = optional($movieResponses[$index]->json())['movie'] ?? [];
                        $result[] = array_merge($item, $movieData);
                    }
                }
                else {
                    $responses = Http::pool(fn ($pool) => 
                        array_map(fn ($i) => 
                        $pool->get($this->searchUrl . $params['url'] . '?page=' . $i), range($params['page_from'], $params['page_to']))
                    );
                    $result = array_merge(...array_map(fn ($response) => 
                        optional($response->json())['data']['items'] ?? [], $responses
                    ));
                   
                }
            }
            //dd($result);
            // foreach ($result as $re) {
            //     dd($re['_id']);
            //     $res = Http::get()
            // }
            $movies = collect($result)->map(fn ($response) => [
                'name'              => optional($response)['name'] ?? null,
                'slug'              => optional($response)['slug'] ?? null,
                'episode_current'   => optional($response)['episode_current'],
                'existed'           => 0,   
            ])->toArray();
            $existingSlugs = self::whereIn('slug', array_column($movies, 'slug'))->pluck('id','slug')->toArray();
           
            //$existingSlugs = array_flip($existingSlugs);
                
            foreach ($movies as &$movie) {
                if (isset($existingSlugs[$movie['slug']])) {
                    $movie['movie_id']      = $existingSlugs[$movie['slug']];
                    $this->totalEpisode     = new EpisodeModel();
                    $total_episode          = $this->totalEpisode->getItem($movie['movie_id'], ['task' => 'get-item-info']);
                    $movie['now_episode']   = $total_episode;
                    $movie['existed']       = 1;
                }
            }
            $result = $movies;
        }
        return $result;
    }
}
