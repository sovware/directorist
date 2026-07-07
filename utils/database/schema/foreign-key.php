<?php

namespace Directorist\Utils\Database\Schema;

\defined("ABSPATH") || exit;
class ForeignKey
{
    /**
     * The column in the current table that is the foreign key.
     *
     * @var string
     */
    protected string $column;
    /**
     * The referenced column in the foreign table. Defaults to 'id'.
     *
     * @var string
     */
    protected string $reference_column = 'id';
    /**
     * The referenced table in the foreign key constraint.
     *
     * @var string
     */
    protected string $reference_table = '';
    /**
     * The action to perform on delete (e.g., CASCADE, SET NULL).
     *
     * @var string|null
     */
    protected ?string $on_delete = null;
    /**
     * The action to perform on update (e.g., CASCADE, SET NULL).
     *
     * @var string|null
     */
    protected ?string $on_update = null;
    /**
     * ForeignKey constructor.
     *
     * @param string $column The column in the current table that is the foreign key.
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }
    /**
     * Set the referenced column in the foreign table.
     *
     * @param string $reference_column The referenced column name.
     * @return self
     */
    public function references(string $reference_column) : self
    {
        $this->reference_column = $reference_column;
        return $this;
    }
    /**
     * Set the referenced table in the foreign key constraint.
     *
     * @param string $reference_table The referenced table name (without prefix).
     * @return self
     */
    public function on(string $reference_table) : self
    {
        global $wpdb;
        $this->reference_table = $wpdb->prefix . $reference_table;
        return $this;
    }
    /**
     * Set the ON DELETE action for the foreign key constraint.
     *
     * @param string $action The action to perform on delete (e.g., CASCADE, SET NULL).
     * @return self
     */
    public function on_delete(string $action) : self
    {
        $this->on_delete = \strtoupper($action);
        return $this;
    }
    /**
     * Set the ON UPDATE action for the foreign key constraint.
     *
     * @param string $action The action to perform on update (e.g., CASCADE, SET NULL).
     * @return self
     */
    public function on_update(string $action) : self
    {
        $this->on_update = \strtoupper($action);
        return $this;
    }
    /**
     * Get the column in the current table that is the foreign key.
     *
     * @return string
     */
    public function get_column() : string
    {
        return $this->column;
    }
    /**
     * Get the referenced table in the foreign key constraint.
     *
     * @return string
     */
    public function get_reference_table() : string
    {
        return $this->reference_table;
    }
    /**
     * Get the referenced column in the foreign table.
     *
     * @return string
     */
    public function get_reference_column() : string
    {
        return $this->reference_column;
    }
    /**
     * Get the ON DELETE action for the foreign key constraint.
     *
     * @return string|null
     */
    public function get_on_delete() : ?string
    {
        return $this->on_delete;
    }
    /**
     * Get the ON UPDATE action for the foreign key constraint.
     *
     * @return string|null
     */
    public function get_on_update() : ?string
    {
        return $this->on_update;
    }
}
