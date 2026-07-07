<?php

namespace Directorist\Utils\Database\Schema;

\defined("ABSPATH") || exit;
use wpdb;
class Schema
{
    /**
     * Creates a new database table with the specified name and schema definition.
     *
     * This method uses a callback to define the table schema using a Blueprint instance.
     * It generates the SQL for table creation and executes it using WordPress's dbDelta function.
     * Optionally, it can return the generated SQL instead of executing it.
     * After table creation, it applies any defined foreign key constraints.
     *
     * @param string   $table_name The name of the table to create (without prefix).
     * @param (Closure(Blueprint): mixed) $callback A callback that receives a Blueprint instance to define the table schema.
     * @param bool     $return     Optional. If true, returns the generated SQL instead of executing it. Default false.
     *
     * @return void|string Returns the SQL string if $return is true, otherwise void.
     */
    public static function create(string $table_name, callable $callback, bool $return = \false)
    {
        /**
         * @var wpdb $wpdb
         */
        global $wpdb;
        $table_name = $wpdb->prefix . $table_name;
        $charset_collate = $wpdb->get_charset_collate();
        $blueprint = new Blueprint($table_name, $charset_collate);
        $callback($blueprint);
        $sql = $blueprint->to_sql();
        if ($return) {
            return $sql;
        }
        if (!\function_exists('Directorist\\dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }
        dbDelta($sql);
        self::apply_foreign_keys($table_name, $blueprint->get_foreign_keys());
    }
    /**
     * Drops a database table if it exists.
     *
     * Constructs and executes a SQL statement to drop the specified table,
     * using the WordPress database prefix. If the $return parameter is true,
     * the SQL statement is returned instead of being executed.
     *
     * @param string $table_name The name of the table to drop (without prefix).
     * @param bool   $return     Optional. Whether to return the SQL statement instead of executing it. Default false.
     * @return string|null       The SQL statement if $return is true, otherwise null.
     */
    public static function drop_if_exists(string $table_name, bool $return = \false)
    {
        global $wpdb;
        $full_table_name = $wpdb->prefix . $table_name;
        $sql = "DROP TABLE IF EXISTS `{$full_table_name}`;";
        if ($return) {
            return $sql;
        }
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $wpdb->query($sql);
    }
    /**
     * Renames a database table from one name to another.
     *
     * @param string $from   The current name of the table (without prefix).
     * @param string $to     The new name for the table (without prefix).
     * @param bool   $return Optional. If true, returns the generated SQL query instead of executing it. Default false.
     * @return void|string   Returns the SQL query string if $return is true, otherwise void.
     */
    public static function rename(string $from, string $to, bool $return = \false)
    {
        global $wpdb;
        $from_table = $wpdb->prefix . $from;
        $to_table = $wpdb->prefix . $to;
        $sql = "RENAME TABLE `{$from_table}` TO `{$to_table}`;";
        if ($return) {
            return $sql;
        }
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $wpdb->query($sql);
    }
    /**
     * Alters the structure of a database table using a callback to define changes.
     *
     * @param string   $table_name The name of the table to alter (without prefix).
     * @param callable $callback   A callback that receives a Blueprint instance to define the alterations.
     * @param bool     $return     Optional. If true, returns the generated SQL query instead of executing it. Default false.
     * @return void|string         Returns the SQL query string if $return is true, otherwise void.
     */
    public static function alter(string $table_name, callable $callback, bool $return = \false)
    {
        global $wpdb;
        $full_table_name = $wpdb->prefix . $table_name;
        $charset_collate = $wpdb->get_charset_collate();
        $blueprint = new Blueprint($full_table_name, $charset_collate);
        $callback($blueprint);
        $sql = $blueprint->to_alter_sql();
        if ($return) {
            return $sql;
        }
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $wpdb->query($sql);
        self::apply_foreign_keys($table_name, $blueprint->get_foreign_keys());
    }
    /**
     * Applies foreign key constraints to a specified table if they do not already exist.
     *
     * Iterates through the provided array of foreign key definitions, checks if each constraint
     * exists in the database, and adds it if missing. The constraint name is generated using
     * the table name and column. Supports optional ON DELETE and ON UPDATE actions.
     *
     * @param string $table_name    The name of the table to which foreign keys will be applied (without prefix).
     * @param ForeignKey[] $foreign_keys  An array of foreign key objects, each providing methods:
     *                              - get_column(): string - The column in the current table.
     *                              - get_reference_table(): string - The referenced table name.
     *                              - get_reference_column(): string - The referenced column name.
     *                              - get_on_delete(): string|null - The ON DELETE action (e.g., CASCADE, SET NULL).
     *                              - get_on_update(): string|null - The ON UPDATE action (e.g., CASCADE, SET NULL).
     *
     * @global wpdb $wpdb           WordPress database abstraction object.
     *
     * @return void
     */
    protected static function apply_foreign_keys(string $table_name, array $foreign_keys) : void
    {
        global $wpdb;
        foreach ($foreign_keys as $fk) {
            $column = $fk->get_column();
            $reference = $fk->get_reference_table();
            $ref_column = $fk->get_reference_column();
            $on_delete = $fk->get_on_delete();
            $on_update = $fk->get_on_update();
            $constraint = "fk_{$table_name}_{$column}";
            /**
             * Checks if a specific foreign key constraint exists on a given table.
             */
            $exists = $wpdb->get_results($wpdb->prepare("\n                SELECT CONSTRAINT_NAME\n                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE\n                WHERE TABLE_SCHEMA = %s\n                AND TABLE_NAME = %s\n                AND CONSTRAINT_NAME = %s", DB_NAME, $table_name, $constraint));
            if (empty($exists)) {
                $alter_sql = \sprintf("ALTER TABLE `%s` ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES %s(`%s`)%s%s;", $table_name, $constraint, $column, $reference, $ref_column, $on_delete ? " ON DELETE {$on_delete}" : "", $on_update ? " ON UPDATE {$on_update}" : "");
                //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                $wpdb->query($alter_sql);
            }
        }
    }
}
