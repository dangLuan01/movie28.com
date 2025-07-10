<?php

namespace App\Jobs;

use App\Models\Elasticsearch\ElasticsearchModel;
use App\Models\Movie\CountryModel;
use App\Models\Movie\EpisodeModel;
use App\Models\Movie\GenreModel;
use App\Models\Movie\ImageModel;
use App\Models\Movie\MovieGenreModel;
use App\Models\Movie\ProductModel;
use App\Models\Movie\ServerModel;
use App\Models\MovieCountryModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Elasticsearch\Client;
class ProcessAutoSaveMovie implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $params;
    public $tries = 3;
    public function __construct($params)
    {
        $this->params   = $params;
        $this->movie    = new ProductModel();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {  
            $item = Http::get($this->params)->json();
            if (empty($item['movie'])) {
                return;
            }
            $movie = $item['movie'];
            $episode_total = !empty($movie['episode_total']) && 
            preg_match('/(\d+)/', $movie['episode_total'], $matches)? $matches[0]: 0;
             if (preg_match('/<p>(.*?)<\/p>/s', $item['movie']['content'], $matches)) {
                $content = $matches[1]; 
            }
            $insertGetId = ProductModel::insertGetId([
                'name'          => data_get($movie, 'name', ''),
                'origin_name'   => data_get($movie, 'origin_name', ''),
                'slug'          => data_get($movie, 'slug', ''),
                'content'       => $content ?? data_get($movie, 'content', ''),
                'type'          => data_get($movie, 'type', ''),
                'runtime'       => data_get($movie, 'time', ''),
                'quality'       => data_get($movie, 'quality', ''),
                'imdb'          => data_get($movie, 'imdb.id', ''),
                'tmdb'          => data_get($movie, 'tmdb.id', ''),
                'rating'        => data_get($movie, 'tmdb.vote_average', ''),
                'episode_total' => $episode_total,
                'season'        => data_get($movie, 'tmdb.season', null),
                'trailer'       => data_get($movie, 'trailer_url', ''),
                'release_date'  => data_get($movie, 'year', ''),
            ]);
            if (!empty($movie['category'])) {
                $categorySlugs  = collect($movie['category'])->pluck('slug')->toArray();
                $existingGenres = GenreModel::whereIn('slug', $categorySlugs)->pluck('id', 'slug');
                $newGenres      = [];
                $movieGenres    = [];
    
                foreach ($movie['category'] as $genre) {
                    if (isset($existingGenres[$genre['slug']])) {
                        $movieGenres[] = [
                            'movie_id' => $insertGetId,
                            'genre_id' => $existingGenres[$genre['slug']],
                        ];
                    } else {
                        $newGenres[] = [
                            'name' => $genre['name'],
                            'slug' => $genre['slug'],
                        ];
                    }
                }
                if (!empty($newGenres)) {
                    GenreModel::insert($newGenres);
                    $insertedGenres = GenreModel::whereIn('slug', collect($newGenres)->pluck('slug'))->pluck('id', 'slug');
    
                    foreach ($newGenres as $genre) {
                        $movieGenres[] = [
                            'movie_id' => $insertGetId,
                            'genre_id' => $insertedGenres[$genre['slug']],
                        ];
                    }
                }
                MovieGenreModel::insert($movieGenres);
            }
            foreach($item['movie']['country'] as $country){
                $exitingCountry = CountryModel::where('slug', $country['slug'])->first();
                if ($exitingCountry) {
                    MovieCountryModel::insert([
                        'movie_id' => $insertGetId,
                        'country_id' => $exitingCountry['id']
                    ]);
                }else{
                    $newCountry = CountryModel::insertGetId([
                        'name'  => $country['name'],
                        'slug'  => $country['slug']
                    ]);
                    MovieCountryModel::insert([
                        'movie_id' => $insertGetId,
                        'country_id' => $newCountry
                    ]);
                }
            }
           
            $img_thumb = basename($item['movie']['thumb_url'] ?? '');
            $img_poster = basename($item['movie']['poster_url'] ?? '');
            $path = str_replace($img_thumb, '', $item['movie']['thumb_url']); 
            ImageModel::insert([
                'movie_id'  => $insertGetId,
                'image'     => $img_thumb,
                'path'      => $path,
            ]);  
            ImageModel::insert([
                'movie_id'      => $insertGetId,
                'image'         => $img_poster,
                'path'          => $path,
                'is_thumbnail'  => 1
            ]);  
           
            $episodes = [];
            foreach ($item['episodes'] as $episode) {
                $exitingServer = ServerModel::where('name', $episode['server_name'])->first();
                if($exitingServer){
                    foreach ($episode['server_data'] as $ep) {
                        $episodes[] = [
                            'movie_id'   => $insertGetId,
                            'hls'        => data_get($ep, 'link_m3u8', ''),
                            'episode'    => data_get($ep, 'name', ''),
                            'server_id'  => $exitingServer['id'],
                        ];
                    }
                }
                else{
                    $newServer = ServerModel::insertGetId([
                        'name'  => $episode['server_name'],
                    ]);
                    foreach ($episode['server_data'] as $ep) {
                        $episodes[] = [
                            'movie_id'   => $insertGetId,
                            'hls'        => data_get($ep, 'link_m3u8', ''),
                            'episode'    => data_get($ep, 'name', ''),
                            'server_id'  => $newServer,
                        ];
                    }
                }
            }
            EpisodeModel::insert($episodes);
            $data = [
                'name'          => $movie['name'] ?? null,
                'origin_name'   => $movie['origin_name'] ?? null,
                'slug'          => $movie['slug'] ?? null,
                'runtime'       => $movie['time'] ?? null,
                'type'          => $movie['type'] ?? null,
                'age'           => '16+',
                'release_date'  => $movie['year'] ?? null,
                'poster'        => $path . $img_thumb ?? null,
            ];
            $es = new ElasticsearchModel(app(Client::class));
            $es->saveItem($data, ['task' => 'add-item', 'id' => $insertGetId]);
            //######## CRAWL FILE JSON ##########//
            // $item = Http::get($this->params)->json();
            // if (preg_match('/(\d+)/', $item['movie']['episode_total'], $matchess)) {
            //     $episode_total = $matchess[0];
            // }
            // if (preg_match('/<p>(.*?)<\/p>/s', $item['movie']['content'], $matches)) {
            //     $content = $matches[1]; 
            // }
            // $insertGetId = ProductModel::insertGetId([
            //     'name'              =>  $item['movie']['name'] ?? null,
            //     'origin_name'       =>  $item['movie']['origin_name'] ?? null,
            //     'slug'              =>  $item['movie']['slug'] ?? null,
            //     'content'           =>  $content ?? $item['movie']['content'],
            //     'age'               => '16+',
            //     'type'              =>  $item['movie']['type'] ?? null,
            //     'runtime'           =>  $item['movie']['time'] ?? null,
            //     'quality'           =>  $item['movie']['quality'] ?? null,
            //     'imdb'              =>  $item['movie']['imdb']['id'] ?? null,
            //     'tmdb'              =>  $item['movie']['tmdb']['id'] ?? null,
            //     'rating'            =>  $item['movie']['tmdb']['vote_average'] ?? null,
            //     'episode_total'     =>  $episode_total ?? null,
            //     'season'            =>  $item['movie']['tmdb']['season'] ?? null,
            //     'trailer'           =>  $item['movie']['trailer_url'] ?? null,
            //     'release_date'      =>  $item['movie']['year'] ?? null,
            // ]);
            // foreach($item['movie']['category'] as $genre){
            //     $exitingGenre = GenreModel::where('slug', $genre['slug'])->first();
            //     if ($exitingGenre) {
            //         MovieGenreModel::insert([
            //             'movie_id' => $insertGetId,
            //             'genre_id' => $exitingGenre['id']
            //         ]);
            //     }else{
            //         $newGenre = GenreModel::insertGetId([
            //             'name'  => $genre['name'],
            //             'slug'  => $genre['slug']
            //         ]);
            //         MovieGenreModel::insert([
            //             'movie_id' => $insertGetId,
            //             'genre_id' => $newGenre
            //         ]);
            //     }
            // }

            // foreach($item['movie']['country'] as $country){
            //     $exitingCountry = CountryModel::where('slug', $country['slug'])->first();
            //     if ($exitingCountry) {
            //         MovieCountryModel::insert([
            //             'movie_id' => $insertGetId,
            //             'country_id' => $exitingCountry['id']
            //         ]);
            //     }else{
            //         $newCountry = CountryModel::insertGetId([
            //             'name'  => $country['name'],
            //             'slug'  => $country['slug']
            //         ]);
            //         MovieCountryModel::insert([
            //             'movie_id' => $insertGetId,
            //             'country_id' => $newCountry
            //         ]);
            //     }
            // }
           
            // $img_thumb = basename($item['movie']['thumb_url'] ?? '');
            // $img_poster = basename($item['movie']['poster_url'] ?? '');
            // $path = str_replace($img_thumb, '', $item['movie']['thumb_url']); 
            // ImageModel::insert([
            //     'movie_id'  => $insertGetId,
            //     'image'     => $img_thumb,
            //     'path'      => $path,
            // ]);  
            // ImageModel::insert([
            //     'movie_id'      => $insertGetId,
            //     'image'         => $img_poster,
            //     'path'          => $path,
            //     'is_thumbnail'  => 1
            // ]);  
            // $episodes = [];
            // foreach ($item['episodes'] as $episode) {
            //     $exitingServer = ServerModel::where('name', $episode['server_name'])->first();
            //     if($exitingServer){
            //         foreach ($episode['server_data'] as $ep) {
            //             $episodes[] = [
            //                 'movie_id'   => $insertGetId,
            //                 'hls'        => data_get($ep, 'link_m3u8', ''),
            //                 'episode'    => data_get($ep, 'name', ''),
            //                 'server_id'  => $exitingServer['id'],
            //             ];
            //         }
            //     }
            //     else{
            //         $newServer = ServerModel::insertGetId([
            //             'name'  => $episode['server_name'],
            //         ]);
            //         foreach ($episode['server_data'] as $ep) {
            //             $episodes[] = [
            //                 'movie_id'   => $insertGetId,
            //                 'hls'        => data_get($ep, 'link_m3u8', ''),
            //                 'episode'    => data_get($ep, 'name', ''),
            //                 'server_id'  => $newServer,
            //             ];
            //         }
            //     }
            // }
            // EpisodeModel::insert($episodes);
    }
}
