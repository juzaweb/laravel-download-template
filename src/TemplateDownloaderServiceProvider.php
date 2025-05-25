<?php

namespace Juzaweb\TemplateDownloader;

use Illuminate\Support\ServiceProvider;

class TemplateDownloaderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands(
            [
                \Juzaweb\TemplateDownloader\Commands\DownloadStyleCommand::class,
            ]
        );
    }
}
