<?php

namespace Juzaweb\TemplateDownloader;

use Illuminate\Support\ServiceProvider;

class TemplateDownloaderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    \Juzaweb\TemplateDownloader\Commands\DownloadStyleCommand::class,
                    \Juzaweb\TemplateDownloader\Commands\DownloadTemplateCommand::class,
                ]
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/template-downloader.php', 'template-downloader');

        $this->publishes([
            __DIR__.'/../config/template-downloader.php' => config_path('template-downloader.php')
        ], 'template-downloader-config');
    }
}
