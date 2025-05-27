<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

// You can do your own things here, e.g. collecting symbols to expose dynamically
// or files to exclude.
// However beware that this file is executed by PHP-Scoper, hence if you are using
// the PHAR it will be loaded by the PHAR. So it is highly recommended to avoid
// to auto-load any code here: it can result in a conflict or even corrupt
// the PHP-Scoper analysis.

// Example of collecting files to include in the scoped build but to not scope
// leveraging the isolated finder.

$directory = __DIR__ . '/../../';

$polyfills_bootstraps = array_map(
    static fn ( SplFileInfo $file_info ) => $file_info->getPathname(),
    iterator_to_array(
        Finder::create()
            ->files()
            ->in( $directory . 'vendor-src/symfony/polyfill-*' )
            ->name( 'bootstrap*.php' ),
        false,
    ),
);

$polyfills_stubs = array_map(
    static fn ( SplFileInfo $file_info ) => $file_info->getPathname(),
    iterator_to_array(
        Finder::create()
            ->files()
            ->in( $directory . 'vendor-src/symfony/polyfill-*/Resources/stubs' )
            ->name( '*.php' ),
        false,
    ),
);

return [
    // List of excluded files, i.e. files for which the content will be left untouched.
    // Paths are relative to the configuration file unless if they are already absolute
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
    'exclude-files' => [
        $directory . 'vendor-src/php-di/php-di/src/Compiler/Template.php',
        ...$polyfills_bootstraps,
        ...$polyfills_stubs,
    ]
];