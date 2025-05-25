<?php

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
