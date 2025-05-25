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
                ]
            );
        }
    }
}
