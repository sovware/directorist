<?php

namespace Directorist\Utils\Database\Clauses;

\defined("ABSPATH") || exit;
trait WhereClause
{
    use Clause;
    /**
     * Get the array of where clauses.
     *
     * @return array The array of where clauses.
     */
    public function get_wheres() : array
    {
        return $this->clauses['wheres'] ?? [];
    }
    /**
     * Unset a where from the query.
     *
     * @param int|string $key The key or index of the where to remove.
     * @return static
     */
    public function unset_where($key)
    {
        return $this->unset_clause('wheres', $key);
    }
    /**
     * Add a basic where to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->clause("wheres", $column, $operator, $value, $name);
    }
    /**
     * Add an "or where" to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->or_clause("wheres", $column, $operator, $value, $name);
    }
    /**
     * Add an "where not" to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->clause_not("wheres", $column, $operator, $value, $name);
    }
    /**
     * Add an "or where not" to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->or_clause_not("wheres", $column, $operator, $value, $name);
    }
    /**
     * Add a where comparing two columns to the query.
     *
     * @param string $first_column The first column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $second_column The second column to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_column(string $first_column, $operator = null, $second_column = null, ?string $name = null)
    {
        return $this->clause_column("wheres", $first_column, $operator, $second_column, $name);
    }
    /**
     * Add an "or where comparing two columns" to the query.
     * 
     * @param string $first_column The first column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $second_column The second column to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_column(string $first_column, $operator = null, $second_column = null, ?string $name = null)
    {
        return $this->or_clause_column("wheres", $first_column, $operator, $second_column, $name);
    }
    /**
     * Add an exists where to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists where.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_exists($callback, ?string $name = null)
    {
        return $this->clause_exists("wheres", $callback, $name);
    }
    /**
     * Add an "or exists" where to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists where.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_exists($callback, ?string $name = null)
    {
        return $this->or_clause_exists("wheres", $callback, $name);
    }
    /**
     * Add a "not exists" where to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists where.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not_exists($callback, ?string $name = null)
    {
        return $this->clause_not_exists("wheres", $callback, $name);
    }
    /**
     * Add an "or not exists" where to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists where.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not_exists($callback, ?string $name = null)
    {
        return $this->or_clause_not_exists("wheres", $callback, $name);
    }
    /**
     * Add a "where in" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_in(string $column, array $values, ?string $name = null)
    {
        return $this->clause_in("wheres", $column, $values, $name);
    }
    /**
     * Add an "or in" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_in(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_in("wheres", $column, $values, $name);
    }
    /**
     * Add a "not in" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not_in(string $column, array $values, ?string $name = null)
    {
        return $this->clause_not_in("wheres", $column, $values, $name);
    }
    /**
     * Add an "or not in" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not_in(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_not_in("wheres", $column, $values, $name);
    }
    /**
     * Add a "like" where to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_like(string $column, string $value, ?string $name = null)
    {
        return $this->clause_like("wheres", $column, $value, $name);
    }
    /**
     * Add an "or like" where to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_like(string $column, string $value, ?string $name = null)
    {
        return $this->or_clause_like("wheres", $column, $value, $name);
    }
    /**
     * Add a "not like" where to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not_like(string $column, string $value, ?string $name = null)
    {
        return $this->clause_not_like("wheres", $column, $value, $name);
    }
    /**
     * Add an "or not like" where to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not_like(string $column, string $value, ?string $name = null)
    {
        return $this->or_clause_not_like("wheres", $column, $value, $name);
    }
    /**
     * Add an "is null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'where_null');
        return $this->where_null($column, $name);
    }
    /**
     * Add an "or is null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'or_where_null');
        return $this->or_where_null($column, $name);
    }
    /**
     * Add a "not is null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'where_not_null');
        return $this->where_not_null($column, $name);
    }
    /**
     * Add an "or not is null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'or_where_not_null');
        return $this->or_where_not_null($column, $name);
    }
    /**
     * Add an "null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_null(string $column, ?string $name = null)
    {
        return $this->clause_null("wheres", $column, $name);
    }
    /**
     * Add an "or null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_null(string $column, ?string $name = null)
    {
        return $this->or_clause_null("wheres", $column, $name);
    }
    /**
     * Add a "not null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not_null(string $column, ?string $name = null)
    {
        return $this->clause_not_null("wheres", $column, $name);
    }
    /**
     * Add an "or not null" where to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not_null(string $column, ?string $name = null)
    {
        return $this->or_clause_not_null("wheres", $column, $name);
    }
    /**
     * Add a "between" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_between(string $column, array $values, ?string $name = null)
    {
        return $this->clause_between("wheres", $column, $values, $name);
    }
    /**
     * Add an "or between" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_between(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_between("wheres", $column, $values, $name);
    }
    /**
     * Add a "not between" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_not_between(string $column, array $values, ?string $name = null)
    {
        return $this->clause_not_between("wheres", $column, $values, $name);
    }
    /**
     * Add an "or not between" where to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_not_between(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_not_between("wheres", $column, $values, $name);
    }
    /**
     * Add a raw where to the query.
     *
     * @param string $sql The SQL statement.
     * @param array $bindings The bindings for the raw SQL statement.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function where_raw(string $sql, array $bindings = [], ?string $name = null)
    {
        return $this->clause_raw("wheres", $sql, $bindings, $name);
    }
    /**
     * Add an "or raw" where to the query.
     *
     * @param string $sql The SQL statement.
     * @param array $bindings The bindings for the raw SQL statement.
     * @param ?string $name Optional name for the where.
     * @return static
     */
    public function or_where_raw(string $sql, array $bindings = [], ?string $name = null)
    {
        return $this->or_clause_raw("wheres", $sql, $bindings, $name);
    }
}
