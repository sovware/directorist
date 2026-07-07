<?php

namespace Directorist\Utils\Repositories;

\defined('ABSPATH') || exit;
use Directorist\Utils\DTO;
use Directorist\Utils\Database\Query\Builder;

/**
 * Abstract class Repository
 * Provides a base implementation for a repository pattern to interact with the database.
 */
abstract class Repository
{
    /**
     * Get the query builder instance.
     * 
     * This method must be implemented by any concrete repository class.
     *
     * @return Builder An instance of the query builder.
     */
    public abstract function get_query_builder() : Builder;
    /**
     * Insert a new record into the database.
     *
     * @param DTO $dto Data transfer object containing the data to be inserted.
     * @return int The ID of the newly inserted record.
     */
    public function create(DTO $dto)
    {
        return $this->get_query_builder()->insert_get_id($this->process_values($dto->to_array()));
    }
    /**
     * Update an existing record in the database.
     *
     * @param DTO $dto Data transfer object containing the updated data.
     * @return int The number of affected rows.
     */
    public function update(DTO $dto)
    {
        return $this->get_query_builder()->where('id', $dto->get_id())->update($this->process_values($dto->to_array()));
    }
    /**
     * Retrieve a single record from the database based on a column value.
     *
     * @param string $column The column to search by.
     * @param mixed $value The value to match in the specified column.
     * @param array|string $columns The columns to select (default is all columns).
     * @return mixed The first matching record or null if no match is found.
     */
    public function get_by(string $column, $value, $columns = ['*'])
    {
        return $this->get_query_builder()->select($columns)->where($column, $value)->first();
    }
    /**
     * Retrieve a single record from the database by its ID.
     *
     * @param int $id The ID of the record to retrieve.
     * @param array|string $columns The columns to select (default is all columns).
     * @return mixed The first matching record or null if no match is found.
     */
    public function get_by_id(int $id, $columns = ['*'])
    {
        return $this->get_by('id', $id, $columns);
    }
    /**
     * Retrieve multiple records from the database by their IDs.
     *
     * @param array $ids An array of IDs to retrieve.
     * @param array|string $columns The columns to select (default is all columns).
     * @return array An array of matching records.
     */
    public function get_by_ids(array $ids, $columns = ['*'])
    {
        return $this->get_query_builder()->select($columns)->where_in('id', $ids)->get();
    }
    /**
     * Delete a record from the database based on a column value.
     *
     * @param string $column The column to search by.
     * @param mixed $value The value to match in the specified column.
     * @return int The number of affected rows.
     */
    public function delete_by(string $column, $value)
    {
        return $this->get_query_builder()->where($column, $value)->delete();
    }
    /**
     * Delete a record from the database by its ID.
     *
     * @param int $id The ID of the record to delete.
     * @return int The number of affected rows.
     */
    public function delete_by_id(int $id)
    {
        return $this->delete_by('id', $id);
    }
    /**
     * Processes the given array of values to prepare them for database operations.
     *
     * This method converts any array or `stdClass` object values into JSON strings using `wp_json_encode`.
     * This is typically used to ensure that complex data structures, such as arrays or generic objects,
     * are safely stored in the database in a serialized format.
     *
     * @param array $values The array of values to process.
     * @return array The processed array with JSON-encoded values for arrays and `stdClass` objects where applicable.
     */
    protected function process_values(array $values)
    {
        return \array_map(function ($value) {
            return \is_array($value) || \is_object($value) && \get_class($value) === 'stdClass' ? wp_json_encode($value) : $value;
        }, $values);
    }
}
