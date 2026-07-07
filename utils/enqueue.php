<?php

namespace Directorist\Utils\Enqueue;

\defined('ABSPATH') || exit;

class Enqueue {
    static $root_url = ATBDP_URL;
    static $root_dir = ATBDP_DIR;
    static $version = ATBDP_VERSION;

    public static function get_version(): string {
        return static::$version;
    }

    public static function get_dir( string $dir ) {
        return static::$root_dir . \ltrim($dir, '/');
    }

    public static function get_url( string $url ) {
        return static::$root_url . \ltrim($url, '/');
    }

    public static function style(string $handle, string $src, array $deps = [], $media = 'all')
    {
        static::process_style($handle, $src, $deps, $media, 'wp_enqueue_style');
    }
    public static function register_style(string $handle, string $src, array $deps = [], $media = 'all')
    {
        static::process_style($handle, $src, $deps, $media, 'wp_register_style');
    }
    public static function script(string $handle, string $src, array $deps = [], bool $in_footer = \false)
    {
        static::process_script($handle, $src, $deps, $in_footer, 'wp_enqueue_script');
    }
    public static function register_script(string $handle, string $src, array $deps = [], bool $in_footer = \false)
    {
        static::process_script($handle, $src, $deps, $in_footer, 'wp_register_script');
    }
    protected static function process_style(string $handle, string $src, array $deps, $media, string $method)
    {
        $src = static::process_src($src);
        $asset_src = static::get_dir($src . '.asset.php');
        $js_deps = [];
        if (\file_exists($asset_src)) {
            $asset = (include $asset_src);
            $js_deps = $asset['dependencies'];
            $version = $asset['version'];
        } else {
            $version = static::get_version();
        }
        $method($handle, static::get_url("{$src}.css"), $deps, $version, $media);
        /**
         * Load css hot reload js script
         */
        if (\defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === \true && \file_exists(static::get_dir("{$src}.js"))) {
            wp_enqueue_script("{$handle}-script", static::get_url("{$src}.js"), $js_deps, $version);
        }
    }
    protected static function process_script(string $handle, string $src, array $deps, bool $in_footer, string $method) {
        $src       = static::process_src($src);
        $asset_src = static::get_dir($src . '.asset.php');
        $asset     = [ 'dependencies' => [] ];
        
        if ( \file_exists( $asset_src ) ) {
            $asset = ( include $asset_src );
            $deps  = \array_merge( $asset['dependencies'], $deps );
        }

        $version = isset( $asset['version'] ) ? $asset['version'] : static::get_version();

        /**
         * Removed self dependency
         */
        $handlers = \array_filter( \array_merge($asset['dependencies'], $deps), function ( $item ) use( $handle ) {
            return $item !== $handle;
        } );

        $method( $handle, static::get_url( "{$src}.js" ), \array_unique($handlers), $version, $in_footer );
    }
    protected static function process_src(string $src)
    {
        $path_info = \pathinfo($src);
        $src = $path_info['filename'];
        if ('\\' !== $path_info['dirname']) {
            $src = $path_info['dirname'] . '/' . $path_info['filename'];
        }
        $src = \ltrim($src, '.');
        $src = \ltrim($src, '/');
        return 'assets/' . $src;
    }
}
