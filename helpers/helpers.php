<?php

if (!function_exists('is_url')) {
    /**
     * Return true if string is a url
     *
     * @param string|null $url
     * @return bool
     */
    function is_url(?string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

if (!function_exists('get_domain_by_url')) {
    function get_domain_by_url(string $url, bool $noneWWW = false): string|bool
    {
        if (str_starts_with($url, 'https://')
            || str_starts_with($url, 'http://')
            || str_starts_with($url, '//')
        ) {
            $domain = explode('/', $url)[2];
            if ($noneWWW) {
                if (str_starts_with($domain, 'www.')) {
                    $domain = str_replace('www.', '', $domain);
                }
            }

            return explode('?', $domain)[0];
        }

        return false;
    }
}
