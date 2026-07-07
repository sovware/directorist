<?php

namespace Directorist\Utils\Database\Clauses;

\defined("ABSPATH") || exit;
trait HavingClause
{
    use Clause;
    /**
     * Get the array of having clauses.
     *
     * @return array The array of having clauses.
     */
    public function get_havings() : array
    {
        return $this->clauses['havings'] ?? [];
    }
    /**
     * Unset a having from the query.
     *
     * @param int|string $key The key or index of the having to remove.
     * @return static
     */
    public function unset_having($key)
    {
        return $this->unset_clause('havings', $key);
    }
    /**
     * Add a basic having to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->clause("havings", $column, $operator, $value, $name);
    }
    /**
     * Add an "or having" to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having(string $column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->or_clause("havings", $column, $operator, $value, $name);
    }
    /**
     * Add an "having not" to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->clause_not("havings", $column, $operator, $value, $name);
    }
    /**
     * Add an "or having not" to the query.
     *
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not($column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->or_clause_not("havings", $column, $operator, $value, $name);
    }
    /**
     * Add a having comparing two columns to the query.
     *
     * @param string $first_column The first column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $second_column The second column to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_column(string $first_column, $operator = null, $second_column = null, ?string $name = null)
    {
        return $this->clause_column("havings", $first_column, $operator, $second_column, $name);
    }
    /**
     * Add an "or having comparing two columns" to the query.
     * 
     * @param string $first_column The first column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $second_column The second column to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_column(string $first_column, $operator = null, $second_column = null, ?string $name = null)
    {
        return $this->or_clause_column("havings", $first_column, $operator, $second_column, $name);
    }
    /**
     * Add an exists having to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists having.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_exists($callback, ?string $name = null)
    {
        return $this->clause_exists("havings", $callback, $name);
    }
    /**
     * Add an "or exists" having to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists having.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_exists($callback, ?string $name = null)
    {
        return $this->or_clause_exists("havings", $callback, $name);
    }
    /**
     * Add a "not exists" having to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists having.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not_exists($callback, ?string $name = null)
    {
        return $this->clause_not_exists("havings", $callback, $name);
    }
    /**
     * Add an "or not exists" having to the query.
     *
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists having.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not_exists($callback, ?string $name = null)
    {
        return $this->or_clause_not_exists("havings", $callback, $name);
    }
    /**
     * Add a "having in" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_in(string $column, array $values, ?string $name = null)
    {
        return $this->clause_in("havings", $column, $values, $name);
    }
    /**
     * Add an "or in" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_in(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_in("havings", $column, $values, $name);
    }
    /**
     * Add a "not in" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not_in(string $column, array $values, ?string $name = null)
    {
        return $this->clause_not_in("havings", $column, $values, $name);
    }
    /**
     * Add an "or not in" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not_in(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_not_in("havings", $column, $values, $name);
    }
    /**
     * Add a "like" having to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_like(string $column, string $value, ?string $name = null)
    {
        return $this->clause_like("havings", $column, $value, $name);
    }
    /**
     * Add an "or like" having to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_like(string $column, string $value, ?string $name = null)
    {
        return $this->or_clause_like("havings", $column, $value, $name);
    }
    /**
     * Add a "not like" having to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not_like(string $column, string $value, ?string $name = null)
    {
        return $this->clause_not_like("havings", $column, $value, $name);
    }
    /**
     * Add an "or not like" having to the query.
     *
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not_like(string $column, string $value, ?string $name = null)
    {
        return $this->or_clause_not_like("havings", $column, $value, $name);
    }
    /**
     * Add an "is null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'having_null');
        return $this->having_null($column, $name);
    }
    /**
     * Add an "or is null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'or_having_null');
        return $this->or_having_null($column, $name);
    }
    /**
     * Add a "not is null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'having_not_null');
        return $this->having_not_null($column, $name);
    }
    /**
     * Add an "or not is null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not_is_null(string $column, ?string $name = null)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'or_having_not_null');
        return $this->or_having_not_null($column, $name);
    }
    /**
     * Add an "null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_null(string $column, ?string $name = null)
    {
        return $this->clause_null("havings", $column, $name);
    }
    /**
     * Add an "or null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_null(string $column, ?string $name = null)
    {
        return $this->or_clause_null("havings", $column, $name);
    }
    /**
     * Add a "not null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not_null(string $column, ?string $name = null)
    {
        return $this->clause_not_null("havings", $column, $name);
    }
    /**
     * Add an "or not null" having to the query.
     *
     * @param string $column The column to check.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not_null(string $column, ?string $name = null)
    {
        return $this->or_clause_not_null("havings", $column, $name);
    }
    /**
     * Add a "between" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_between(string $column, array $values, ?string $name = null)
    {
        return $this->clause_between("havings", $column, $values, $name);
    }
    /**
     * Add an "or between" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_between(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_between("havings", $column, $values, $name);
    }
    /**
     * Add a "not between" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_not_between(string $column, array $values, ?string $name = null)
    {
        return $this->clause_not_between("havings", $column, $values, $name);
    }
    /**
     * Add an "or not between" having to the query.
     *
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_not_between(string $column, array $values, ?string $name = null)
    {
        return $this->or_clause_not_between("havings", $column, $values, $name);
    }
    /**
     * Add a raw having to the query.
     *
     * @param string $sql The SQL statement.
     * @param array $bindings The bindings for the raw SQL statement.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function having_raw(string $sql, array $bindings = [], ?string $name = null)
    {
        return $this->clause_raw("havings", $sql, $bindings, $name);
    }
    /**
     * Add an "or raw" having to the query.
     *
     * @param string $sql The SQL statement.
     * @param array $bindings The bindings for the raw SQL statement.
     * @param ?string $name Optional name for the having.
     * @return static
     */
    public function or_having_raw(string $sql, array $bindings = [], ?string $name = null)
    {
        return $this->or_clause_raw("havings", $sql, $bindings, $name);
    }
}
