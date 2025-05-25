<?php

namespace Juzaweb\TemplateDownloader\Commands;

use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

class DownloadTemplateCommand extends DownloadTemplateCommandAbstract
{
    protected $name = 'html:download';

    protected array $data = [];

    public function handle(): void
    {
        $this->sendBaseDataAsks();

        $this->downloadFileAsks();
    }

    protected function sendBaseDataAsks(): void
    {
        $this->data['url'] = $this->ask(
            'Url Template?',
            $this->getDataDefault('url')
        );

        $this->setDataDefault('url', $this->data['url']);

        $this->data['container'] = $this->ask(
            'Container?',
            $this->getDataDefault('container', '.container-fluid')
        );

        $this->setDataDefault('container', $this->data['container']);
    }

    protected function downloadFileAsks(): void
    {
        $this->data['file'] = $this->ask(
            'File?',
            $this->getDataDefault('file', 'index.blade.php')
        );

        $this->setDataDefault('file', $this->data['file']);

        $extension = pathinfo($this->data['file'], PATHINFO_EXTENSION);

        if ($extension != 'php') {
            $this->data['file'] = "{$this->data['file']}.blade.php";
        }

        $output = $this->option('output');

        $path = "{$output}/{$this->data['file']}";

        $contents = $this->getFileContent($this->data['url']);

        $content = str_get_html($contents)->find($this->data['container'], 0)->outertext;

        File::put(
            base_path($path),
            "@extends('{$this->option('layout')}')

                @section('content')
                    {$content}
                @endsection"
        );

        $this->info("-- Downloaded file {$path}");
    }

    protected function getOptions(): array
    {
        return [
            ['output', 'o', InputOption::VALUE_OPTIONAL, 'Output path', 'resources/views'],
            ['layout', null, InputOption::VALUE_OPTIONAL, 'Layout', 'theme::layouts.frontend'],
        ];
    }
}
