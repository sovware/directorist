<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

$config               = require __DIR__ . '/global.config.php';
$exclude_files_config = require __DIR__ . '/exclude.files.config.php';

return array_merge(
    [
    // By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
    // directory. You can however define which files should be scoped by defining a collection of Finders in the
    // following configuration key.
    //
    // This configuration entry is completely ignored when using Box.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#finders-and-paths
        'finders' => [
            Finder::create()
            ->files()
            ->ignoreVCS( true )
            ->notName( '/LICENSE|.*\\.md|.*\\.dist|Makefile' )
            ->exclude(
                [
                    'doc',
                    'test',
                    'test_old',
                    'tests',
                    'Tests',
                    'vendor-bin'
                ]
            )
            ->in( $directory . 'vendor-src' ),
            Finder::create()->append(
                [
                    $directory . 'composer.json',
                    $directory . 'composer.lock'
                ]
            )
        ],
    ], $exclude_files_config, $config
);