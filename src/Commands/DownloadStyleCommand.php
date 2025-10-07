<?php

namespace Juzaweb\TemplateDownloader\Commands;

use Illuminate\Support\Facades\File;
use Juzaweb\HtmlDom\HtmlDom;
use Symfony\Component\Console\Input\InputOption;

class DownloadStyleCommand extends DownloadTemplateCommandAbstract
{
    protected $name = 'style:download';

    protected $description = 'Download styles (css and js) from url';

    protected array $data = [];

    public function handle(): void
    {
        $this->sendAsks();

        $html = $this->curlGet($this->data['url']);

        $domp = str_get_html($html);

        $css = $this->downloadCss($domp);

        $js = $this->downloadJs($domp);

        $this->generateMixFile($css, $js);
    }

    protected function generateMixFile(array $css, array $js): void
    {
        if (! $this->option('mix')) {
            return;
        }

        $output = $this->option('mix-output');

        $mix = "const mix = require('laravel-mix');

mix.styles([
    ". implode(",\n", $css) ."
], '{$output}/css/main.css');

mix.combine([
    ". implode(",\n", $js) ."
], '{$output}/js/main.js');";

        File::put(base_path("{$output}/webpack.mix.js"), $mix);

        $this->info("-- Generated {$output}/webpack.mix.js");
    }

    protected function sendAsks(): void
    {
        $this->data['url'] = $this->ask(
            'Url Template?',
            $this->getDataDefault('url')
        );

        $this->setDataDefault('url', $this->data['url']);
    }

    protected function downloadCss(HtmlDom $domp): array
    {
        $result = [];
        $output = $this->option('output');

        foreach ($domp->find('link[rel="stylesheet"]') as $e) {
            $href = $e->href;
            $href = $this->parseHref($href);

            if ($this->isExcludeDomain($href)) {
                continue;
            }

            $name = explode('?', basename($href))[0];

            $path = "{$output}/css/{$name}";

            try {
                $this->downloadFile($href, base_path($path));

                $result[] = "'{$path}'";

                // $this->downloadAssetsFromCss([$path]);

                $this->info("-- Downloaded file {$path}");
            } catch (\Exception $e) {
                $this->warn("Download error {$href}: " . $e->getMessage());
            }

            // $this->downloadAssetsFromCss([$path]);

            $this->info("-- Downloaded file {$path}");
        }

        return $result;
    }

    protected function downloadAssetsFromCss(array $cssFiles): void
    {
        foreach ($cssFiles as $cssPath) {
            $fullPath = base_path(trim($cssPath, "'"));
            
            if (!File::exists($fullPath)) {
                continue;
            }

            $content = File::get($fullPath);
            preg_match_all('/url\(["\']?(.*?)["\']?\)/i', $content, $matches);

            foreach ($matches[1] as $assetUrl) {
                $parsedUrl = $this->parseHref($assetUrl);

                if ($this->isExcludeDomain($parsedUrl)) {
                    continue;
                }

                $urlPath = parse_url($assetUrl, PHP_URL_PATH);
                if (! $urlPath) {
                    continue;
                }

                $relativePath = ltrim($urlPath, '/');
                $savePath = $this->option('output') . '/' . $relativePath;

                try {
                    $this->downloadFile($parsedUrl, base_path($savePath));
                    $this->info("-- Downloaded asset {$savePath}");
                } catch (\Exception $e) {
                    $this->warn("Failed to download asset: {$parsedUrl}");
                }
            }
        }
    }

    protected function downloadJs(HtmlDom $domp): array
    {
        $result = [];
        $output = $this->option('output');

        foreach ($domp->find('script') as $e) {
            $href = $e->src;
            if (empty($href)) {
                continue;
            }

            $href = $this->parseHref($href);

            $this->info("-- Download file {$href}");

            if ($this->isExcludeDomain($href)) {
                continue;
            }

            $name = explode('?', basename($href))[0];

            $path = "{$output}/js/{$name}";

            try {
                $this->downloadFile($href, base_path($path));
                $result[] = "'{$path}'";
                $this->info("-- Downloaded file {$path}");
            } catch (\Exception $e) {
                $this->warn("Download error: {$href}");
            }
        }

        return $result;
    }

    protected function parseHref(string $href): mixed
    {
        if (str_starts_with($href, '//')) {
            $href = 'https:' . $href;
        }

        if (!is_url($href)) {
            $baseUrl = explode('/', $this->data['url'])[0];
            $baseUrl .= '://' . get_domain_by_url($this->data['url']);

            if (str_starts_with($href, '/')) {
                $href = $baseUrl . trim($href);
            } else {
                $dir = dirname($this->data['url']);
                $href = "{$dir}/" . trim($href);
            }
        }

        return $href;
    }

    protected function getOptions(): array
    {
        return [
            ['output', 'o', InputOption::VALUE_OPTIONAL, 'Output path', 'resources'],
            ['mix', null, InputOption::VALUE_NONE, 'Use mix'],
            ['mix-output', null, InputOption::VALUE_OPTIONAL, 'Mix output path', ''],
        ];
    }
}
