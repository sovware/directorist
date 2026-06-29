<?php

namespace Directorist\Utils\Database\Schema;

\defined("ABSPATH") || exit;
class Blueprint
{
    protected string $table_name;
    protected string $charset_collate;
    protected array $columns = [];
    protected array $indexes = [];
    /**
     * @var ForeignKey[]
     */
    protected array $foreign_keys = [];
    protected array $drops = [];
    /**
     * Blueprint constructor.
     *
     * @param string $table_name
     * @param string $charset_collate
     */
    public function __construct(string $table_name, string $charset_collate)
    {
        $this->table_name = $table_name;
        $this->charset_collate = $charset_collate;
    }
    /**
     * Add a column definition.
     *
     * @param string $definition
     */
    protected function add_column(string $definition) : void
    {
        $this->columns[] = $definition;
    }
    /**
     * Add a BIGINT UNSIGNED AUTO_INCREMENT primary key.
     *
     * @param string $name
     */
    public function big_increments(string $name) : void
    {
        $this->add_column("`{$name}` BIGINT UNSIGNED AUTO_INCREMENT");
        $this->primary($name);
    }
    /**
     * Add an unsigned BIGINT column.
     *
     * @param string $name
     * @return self
     */
    public function unsigned_big_integer(string $name) : self
    {
        $this->add_column("`{$name}` BIGINT UNSIGNED NOT NULL");
        return $this;
    }
    /**
     * Add an INT column.
     *
     * @param string $name
     * @return self
     */
    public function integer(string $name) : self
    {
        $this->add_column("`{$name}` INT NOT NULL");
        return $this;
    }
    /**
     * Add an unsigned INT column.
     *
     * @param string $name
     * @return self
     */
    public function unsigned_integer(string $name) : self
    {
        $this->add_column("`{$name}` INT UNSIGNED NOT NULL");
        return $this;
    }
    /**
     * Add a DECIMAL column.
     *
     * @param string $name
     * @param int $precision
     * @param int $scale
     * @return self
     */
    public function decimal(string $name, int $precision = 10, int $scale = 2) : self
    {
        $this->add_column("`{$name}` DECIMAL({$precision}, {$scale}) NOT NULL");
        return $this;
    }
    /**
     * Add a VARCHAR column.
     *
     * @param string $name
     * @param int $length
     * @return self
     */
    public function string(string $name, int $length = 255) : self
    {
        $this->add_column("`{$name}` VARCHAR({$length}) NOT NULL");
        return $this;
    }
    /**
     * Add a TEXT column.
     *
     * @param string $name
     * @return self
     */
    public function text(string $name) : self
    {
        $this->add_column("`{$name}` TEXT NOT NULL");
        return $this;
    }
    /**
     * Add a LONGTEXT column.
     *
     * @param string $name
     * @return self
     */
    public function long_text(string $name) : self
    {
        $this->add_column("`{$name}` LONGTEXT");
        return $this;
    }
    /**
     * Add a JSON column.
     *
     * @param string $name
     * @return self
     */
    public function json(string $name) : self
    {
        $this->add_column("`{$name}` JSON NOT NULL");
        return $this;
    }
    /**
     * Add an ENUM column.
     *
     * @param string $name
     * @param array $values
     * @return self
     */
    public function enum(string $name, array $values) : self
    {
        $enum_values = \implode("','", $values);
        $this->add_column("`{$name}` ENUM('{$enum_values}') NOT NULL");
        return $this;
    }
    /**
     * Add a TINYINT column.
     *
     * @param string $name
     * @return self
     */
    public function tiny_integer(string $name) : self
    {
        $this->add_column("`{$name}` TINYINT NOT NULL");
        return $this;
    }
    /**
     * Add a TIMESTAMP column.
     *
     * @param string $name
     * @return self
     */
    public function timestamp(string $name) : self
    {
        $this->add_column("`{$name}` TIMESTAMP");
        return $this;
    }
    /**
     * Add created_at and updated_at TIMESTAMP columns.
     */
    public function timestamps() : void
    {
        $this->timestamp('created_at')->use_current();
        $this->timestamp('updated_at')->nullable()->use_current_on_update();
    }
    /**
     * Add a boolean column.
     *
     * @param string $name
     * @return self
     */
    public function boolean(string $name) : self
    {
        $this->add_column("`{$name}` TINYINT(1) NOT NULL");
        return $this;
    }
    /**
     * Make the last added column nullable.
     *
     * @return self
     */
    public function nullable() : self
    {
        $index = \count($this->columns) - 1;
        $this->columns[$index] = \str_replace('NOT NULL', 'NULL', $this->columns[$index]);
        return $this;
    }
    /**
     * Set a default value for the last added column.
     *
     * @param mixed $value
     * @return self
     */
    public function default($value) : self
    {
        $index = \count($this->columns) - 1;
        $value = \is_numeric($value) ? $value : "'{$value}'";
        $this->columns[$index] .= " DEFAULT {$value}";
        return $this;
    }
    /**
     * Add a comment to the last added column.
     *
     * @param string $text
     * @return self
     */
    public function comment(string $text) : self
    {
        $index = \count($this->columns) - 1;
        $this->columns[$index] .= " COMMENT '{$text}'";
        return $this;
    }
    /**
     * Set CURRENT_TIMESTAMP as default for the last added column.
     *
     * @return self
     */
    public function use_current() : self
    {
        $index = \count($this->columns) - 1;
        $this->columns[$index] .= " DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }
    /**
     * Set ON UPDATE CURRENT_TIMESTAMP for the last added column.
     *
     * @return self
     */
    public function use_current_on_update() : self
    {
        $index = \count($this->columns) - 1;
        $this->columns[$index] .= " ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }
    /**
     * Place the last added column after another column.
     *
     * @param string $column
     * @return self
     */
    public function after(string $column) : self
    {
        $index = \count($this->columns) - 1;
        $this->columns[$index] .= " AFTER `{$column}`";
        return $this;
    }
    /**
     * Drop a column.
     *
     * @param string $name
     */
    public function drop_column(string $name) : void
    {
        $this->drops[] = "DROP COLUMN `{$name}`";
    }
    /**
     * Drop an index.
     *
     * @param string $name
     */
    public function drop_index(string $name) : void
    {
        $this->drops[] = "DROP INDEX `{$name}`";
    }
    /**
     * Add a primary key.
     *
     * @param string|array $columns
     */
    public function primary($columns) : void
    {
        $cols = $this->wrap_column_list($columns);
        $this->indexes[] = "PRIMARY KEY ({$cols})";
    }
    /**
     * Add a unique index.
     *
     * @param string|array $columns
     * @param string $name
     */
    public function unique($columns, string $name = '') : void
    {
        $cols = $this->wrap_column_list($columns);
        $index_name = $name ?: 'unique_' . \md5($cols);
        $this->indexes[] = "UNIQUE KEY `{$index_name}` ({$cols})";
    }
    /**
     * Add an index.
     *
     * @param string|array $columns
     * @param string $name
     */
    public function index($columns, string $name = '') : void
    {
        $cols = $this->wrap_column_list($columns);
        $index_name = $name ?: 'index_' . \md5($cols);
        $this->indexes[] = "KEY `{$index_name}` ({$cols})";
    }
    /**
     * Wrap column names in backticks and return as comma-separated list.
     *
     * @param string|array $columns
     * @return string
     */
    protected function wrap_column_list($columns) : string
    {
        $cols = (array) $columns;
        return \implode(', ', \array_map(fn($col) => "`{$col}`", $cols));
    }
    /**
     * Add a foreign key.
     *
     * @param string $column
     * @return ForeignKey
     */
    public function foreign(string $column) : ForeignKey
    {
        $fk = new ForeignKey($column);
        $this->foreign_keys[] = $fk;
        return $fk;
    }
    /**
     * Get all foreign keys.
     *
     * @return array
     */
    public function get_foreign_keys() : array
    {
        return $this->foreign_keys;
    }
    /**
     * Generate CREATE TABLE SQL.
     *
     * @return string
     */
    public function to_sql() : string
    {
        $definitions = \array_merge($this->columns, $this->indexes);
        $body = \implode(",\n    ", $definitions);
        return "CREATE TABLE `{$this->table_name}` (\n    {$body}\n) {$this->charset_collate};";
    }
    /**
     * Generate ALTER TABLE SQL.
     *
     * @return string
     */
    public function to_alter_sql() : string
    {
        $definitions = [];
        foreach ($this->columns as $column) {
            $definitions[] = "ADD {$column}";
        }
        foreach ($this->indexes as $index) {
            $definitions[] = "ADD {$index}";
        }
        $definitions = \array_merge($definitions, $this->drops);
        $body = \implode(",\n    ", $definitions);
        return "ALTER TABLE `{$this->table_name}`\n    {$body};";
    }
}
