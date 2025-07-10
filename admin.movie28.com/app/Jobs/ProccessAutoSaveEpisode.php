<?php

namespace App\Jobs;

use App\Models\Movie\EpisodeModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProccessAutoSaveEpisode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $params;
    protected $movie_id;
    public $tries = 3;
    public function __construct($params, $movie_id)
    {
        $this->params       = $params;
        $this->movie_id     = $movie_id;
        $this->episode      = new EpisodeModel();
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $item = Http::get($this->params)->json();

        if (empty($item['movie'])) {
            return;
        }
        $serverData = $item['episodes'][0]['server_data'] ?? [];
        $existingEpisodes = EpisodeModel::where('movie_id', $this->movie_id)
                            ->pluck('episode')
                            ->toArray();

        $newEpisodes = [];
        foreach ($serverData as $episode) {
            if (!in_array($episode['name'], $existingEpisodes)) {
                $newEpisodes[] = [
                    'movie_id'  => $this->movie_id,
                    'episode'   => $episode['name'],
                    'hls'       => data_get($episode, 'link_m3u8', ''),
                    'server_id' => 1,
                ];
            }
            else {
                // Update existing episode if needed
                EpisodeModel::where('movie_id', $this->movie_id)
                    ->where('episode', $episode['name'])
                    ->update([
                        'hls' => data_get($episode, 'link_m3u8', ''),
                        'server_id' => 1,
                    ]);
            }
        }
        if (!empty($newEpisodes)) {
            EpisodeModel::insert($newEpisodes);
        }
    }

}
