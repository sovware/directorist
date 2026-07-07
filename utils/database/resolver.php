<?php

namespace Directorist\Utils\Database;

\defined("ABSPATH") || exit;
use wpdb;
class Resolver
{
    protected array $network_tables = ['blogmeta', 'blogs', 'blog_versions', 'registration_log', 'signups', 'site', 'sitemeta', 'usermeta', 'users'];
    public function set_network_tables(array $tables)
    {
        $this->network_tables = \array_merge($this->network_tables, $tables);
    }
    public function table(string $table)
    {
        $table_args = \func_get_args();
        if (1 === \count($table_args)) {
            return $this->resolve_table_name($table);
        }
        return \array_map(function ($table) {
            return $this->resolve_table_name($table);
        }, $table_args);
    }
    protected function resolve_table_name(string $table)
    {
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        if (\in_array($table, $this->network_tables)) {
            return $wpdb->base_prefix . $table;
        }
        return $wpdb->prefix . $table;
    }
}
