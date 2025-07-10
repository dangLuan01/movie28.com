<?php

namespace App\Models\Movie;

use App\Jobs\ProccessAutoSaveEpisode;
use App\Models\BackendModel;
use Illuminate\Database\Eloquent\Model;

class EpisodeModel extends BackendModel
{
    protected $fillable = ['id'];
    public function __construct()
    {
        $this->table = config('constants.TABLE_EPISODE');
        parent::__construct();
    }
    public function getItem($params = null, $options = null)
    {
        $result = null;
        if ($options['task'] == 'get-item-info') {
           
            $result = $this->where('movie_id', $params)->count();
        }
        
        return $result;
    }
    public function saveItem($params = null, $options = null){
        if ($options['task'] == 'add-item-crawler') {
            if (!empty($params['movies'])) {
                $params['movies']   = json_decode($params['movies'], true);
                $params['urls']     = array_map(function ($movie) {
                    return [
                        'url'       => 'https://ophim1.com/phim/' . $movie['slug'],
                        'movie_id'  => $movie['movie_id'],
                    ];
                }, $params['movies']);
                foreach ($params['urls'] as $url) {
                    ProccessAutoSaveEpisode::dispatch($url['url'], $url['movie_id']);
                }  
            }
        }
        if ($options['task'] == 'add-item') {
            $movieId    = $params['movie_id'];
            $episodes   = $params['episodes'];
            $server_id  = $params['server_id'];
            $ep         = array_map(function ($episode, $hls) use ($movieId, $server_id) {
                return [
                    'movie_id'  => $movieId,
                    'episode'   => $episode,
                    'hls'       => $hls,
                    'server_id' => $server_id,
                ];
            }, $episodes['episode'], $episodes['hls']);
            self::insert($ep);
            $this->movie = new ProductModel();
            $this->movie->saveItem($params['movie_id'], ['task' => 'update-time']);
        }
        if ($options['task'] == 'edit-item') {
            
            $ep = array_map(function ($episode, $hls) use ($params) {
                return [
                    'movie_id'  => $params['id'],
                    'episode'   => $episode,
                    'hls'       => $hls,
                    'server_id' => 1,
                ];
            }, $params['episodes']['episode'], $params['episodes']['hls']);
            foreach ($ep as $value) {
                $exits = self::where('movie_id', $value['movie_id'])->where('episode', $value['episode'])->first();
                if ($exits) {
                    $exits->update($value);
                } else {
                    self::insert($value);
                }
            }

        }
    }
}
