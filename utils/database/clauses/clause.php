<?php

namespace Directorist\Utils\Database\Clauses;

\defined("ABSPATH") || exit;

use Closure;

trait Clause {
    protected array $clauses = [];
    
    public function get_clauses() {
        return $this->clauses;
    }

    /**
     * Set a clause in the query.
     *
     * @param string $clause_type The type of the clause.
     * @param array $args The arguments for the clause.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function set_clause(string $clause_type, array $args, ?string $name = null) {
        if ($name) {
            $this->clauses[$clause_type][$name] = $args;
        } else {
            $this->clauses[$clause_type][] = $args;
        }
        return $this;
    }

    /**
     * Unset a clause from the query.
     *
     * @param string $clauses The type of clause (e.g., 'wheres', 'havings', 'ons') to unset.
     * @param int|string $key The key or index of the clause to remove.
     * @return static Returns the current instance for method chaining.
     */
    protected function unset_clause(string $clauses, $key) {
        unset($this->clauses[$clauses][$key]);
        return $this;
    }

    /**
     * Add a basic clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @return static
     */
    protected function clause(string $clause_type, $column, $operator = null, $value = null, ?string $name = null, $boolean = 'and', bool $not = \false) {
        if ($column instanceof Closure) {
            $type = 'nested';
            $query = new static($this->model);
            if (\is_callable($column)) {
                \call_user_func($column, $query);
            }
            $data = \compact('type', 'boolean', 'query', 'name', 'not');
        } else {
            // Prepare value and operator for the clause
            [$value, $operator] = $this->prepare_value_and_operator($value, $operator, \func_num_args() === 2);
            // If the operator is invalid, default to '='
            if ($this->invalid_operator($operator)) {
                [$value, $operator] = [$operator, '='];
            }
            $type = 'basic';
            // Define the type of the clause
            $data = \compact('type', 'boolean', 'column', 'operator', 'value', 'not');
        }
        return $this->set_clause($clause_type, $data, $name);
    }

    /**
     * Add an "or clause" to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause(string $clause_type, $column, $operator = null, $value = null, ?string $name = null) {
        return $this->clause($clause_type, $column, $operator, $value, $name, 'or');
    }

    /**
     * Add an "clause not" to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function clause_not(string $clause_type, $column, $operator = null, $value = null, ?string $name = null) {
        return $this->clause($clause_type, $column, $operator, $value, $name, 'and', \true);
    }
    
    /**
     * Add an "or clause not" to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param (Closure(static): mixed)|static|string $column The column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_not(string $clause_type, $column, $operator = null, $value = null, ?string $name = null)
    {
        return $this->clause($clause_type, $column, $operator, $value, $name, 'or', \true);
    }
    /**
     * Add a clause comparing two columns to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $first_column The first column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $second_column The second column to compare.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @return static
     */
    protected function clause_column(string $clause_type, string $first_column, $operator = null, $second_column = null, ?string $name = null, $boolean = 'and')
    {
        // Prepare value and operator for the clause
        [$second_column, $operator] = $this->prepare_value_and_operator($second_column, $operator, \func_num_args() === 2);
        // If the operator is invalid, default to '='
        if ($this->invalid_operator($operator)) {
            [$second_column, $operator] = [$operator, '='];
        }
        $type = 'column';
        // Define the type of the clause
        $column = $first_column;
        $value = $second_column;
        $data = \compact('type', 'boolean', 'column', 'operator', 'value');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or clause comparing two columns" to the query.
     * 
     * @param string $clause_type The type of the clause.
     * @param string $first_column The first column to compare.
     * @param mixed $operator The operator for comparison.
     * @param mixed $second_column The second column to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_column(string $clause_type, string $first_column, $operator = null, $second_column = null, ?string $name = null)
    {
        return $this->clause_column($clause_type, $first_column, $operator, $second_column, $name, 'or');
    }
    /**
     * Add an exists clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists clause.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @param bool $not Whether to negate the exists clause.
     * @return static
     */
    protected function clause_exists(string $clause_type, $callback, ?string $name = null, $boolean = 'and', $not = \false)
    {
        if (\is_callable($callback)) {
            $query = new static($this->model);
            \call_user_func($callback, $query);
        } else {
            $query = $callback;
        }
        $type = 'exists';
        // Define the type of the clause
        $data = \compact('type', 'query', 'boolean', 'not');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or exists" clause to the query.
     *
     * @param string $clause_type The type of the clause to add (e.g., 'wheres', 'havings').
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists clause.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_exists(string $clause_type, $callback, ?string $name = null)
    {
        return $this->clause_exists($clause_type, $callback, $name, 'or', \false);
    }
    /**
     * Add a "not exists" clause to the query.
     *
     * @param string $clause_type The type of the clause to add (e.g., 'wheres', 'havings').
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists clause.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function clause_not_exists(string $clause_type, $callback, ?string $name = null)
    {
        return $this->clause_exists($clause_type, $callback, $name, 'and', \true);
    }
    /**
     * Add an "or not exists" clause to the query.
     *
     * @param string $clause_type The type of the clause to add (e.g., 'wheres', 'havings').
     * @param (Closure(static): mixed)|static $callback The query or callback for the exists clause.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_not_exists(string $clause_type, $callback, ?string $name = null)
    {
        return $this->clause_exists($clause_type, $callback, $name, 'or', \true);
    }
    /**
     * Add a "clause in" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @param bool $not Whether to negate the in clause.
     * @return static
     */
    protected function clause_in(string $clause_type, string $column, array $values, ?string $name = null, $boolean = 'and', $not = \false)
    {
        $type = 'in';
        // Define the type of the clause
        $data = \compact('type', 'boolean', 'column', 'values', 'not');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or in" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_in(string $clause_type, string $column, array $values, ?string $name = null)
    {
        return $this->clause_in($clause_type, $column, $values, $name, 'or', \false);
    }
    /**
     * Add a "not in" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function clause_not_in(string $clause_type, string $column, array $values, ?string $name = null)
    {
        return $this->clause_in($clause_type, $column, $values, $name, 'and', \true);
    }
    /**
     * Add an "or not in" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to check against.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_not_in(string $clause_type, string $column, array $values, ?string $name = null)
    {
        return $this->clause_in($clause_type, $column, $values, $name, 'or', \true);
    }
    /**
     * Add a "like" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @param bool $not Whether to negate the like clause.
     * @return static
     */
    protected function clause_like(string $clause_type, string $column, string $value, ?string $name = null, $boolean = 'and', $not = \false)
    {
        $type = 'like';
        // Define the type of the clause
        $data = \compact('type', 'boolean', 'column', 'value', 'not');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or like" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_like(string $clause_type, string $column, string $value, ?string $name = null)
    {
        return $this->clause_like($clause_type, $column, $value, $name, 'or', \false);
    }
    /**
     * Add a "not like" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function clause_not_like(string $clause_type, string $column, string $value, ?string $name = null)
    {
        return $this->clause_like($clause_type, $column, $value, $name, 'and', \true);
    }
    /**
     * Add an "or not like" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param string $value The value to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_not_like(string $clause_type, string $column, string $value, ?string $name = null)
    {
        return $this->clause_like($clause_type, $column, $value, $name, 'or', \true);
    }
    /**
     * Add a "between" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @param bool $not Whether to negate the between clause.
     * @return static
     */
    protected function clause_between(string $clause_type, string $column, array $values, ?string $name = null, $boolean = 'and', $not = \false)
    {
        $type = 'between';
        // Define the type of the clause
        $data = \compact('type', 'boolean', 'column', 'values', 'not');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or between" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_between(string $clause_type, string $column, array $values, ?string $name = null)
    {
        return $this->clause_between($clause_type, $column, $values, $name, 'or', \false);
    }
    /**
     * Add a "not between" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function clause_not_between(string $clause_type, string $column, array $values, ?string $name = null)
    {
        return $this->clause_between($clause_type, $column, $values, $name, 'and', \true);
    }
    /**
     * Add an "or not between" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to compare.
     * @param array $values The values to compare.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_not_between(string $clause_type, string $column, array $values, ?string $name = null)
    {
        return $this->clause_between($clause_type, $column, $values, $name, 'or', \true);
    }
    /**
     * Add a raw clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $sql The SQL statement.
     * @param array $bindings The bindings for the raw SQL statement.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @return static
     */
    protected function clause_raw(string $clause_type, string $sql, array $bindings = [], ?string $name = null, $boolean = 'and')
    {
        $type = 'raw';
        // Define the type of the clause
        $data = \compact('type', 'boolean', 'sql', 'bindings');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or raw" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $sql The SQL statement.
     * @param array $bindings The bindings for the raw SQL statement.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_raw(string $clause_type, string $sql, array $bindings = [], ?string $name = null)
    {
        return $this->clause_raw($clause_type, $sql, $bindings, $name, 'or');
    }
    /**
     * Add an "null" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to check.
     * @param ?string $name Optional name for the clause.
     * @param string $boolean The boolean operator ('and' or 'or').
     * @param bool $not Whether to negate the is null clause.
     * @return static
     */
    protected function clause_null(string $clause_type, string $column, ?string $name = null, $boolean = 'and', $not = \false)
    {
        $type = 'is_null';
        // Define the type of the clause
        $data = \compact('type', 'boolean', 'column', 'not');
        return $this->set_clause($clause_type, $data, $name);
    }
    /**
     * Add an "or null" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to check.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_null(string $clause_type, string $column, ?string $name = null)
    {
        return $this->clause_null($clause_type, $column, $name, 'or', \false);
    }
    /**
     * Add a "not null" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to check.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function clause_not_null(string $clause_type, string $column, ?string $name = null)
    {
        return $this->clause_null($clause_type, $column, $name, 'and', \true);
    }
    /**
     * Add an "or not null" clause to the query.
     *
     * @param string $clause_type The type of the clause.
     * @param string $column The column to check.
     * @param ?string $name Optional name for the clause.
     * @return static
     */
    protected function or_clause_not_null(string $clause_type, string $column, ?string $name = null)
    {
        return $this->clause_null($clause_type, $column, $name, 'or', \true);
    }
}
