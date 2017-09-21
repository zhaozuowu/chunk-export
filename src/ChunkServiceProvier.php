<?php

namespace Stephen\Chunk;

use Illuminate\Support\ServiceProvider;
use Stephen\Chunk\Service\ChunkService;
use Stephen\Chunk\Service\ChunkServicel;

class ChunkServiceProvier extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        try {
            $this->app->bind('chunk', ChunkService::class);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
