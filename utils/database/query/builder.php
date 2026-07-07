<?php

namespace Directorist\Utils\Database\Query;

\defined("ABSPATH") || exit;
use DateTime;
use InvalidArgumentException;
use Directorist\Utils\Database\Eloquent\Model;
use Directorist\Utils\Database\Eloquent\Relations\HasMany;
use Directorist\Utils\Database\Eloquent\Relationship;
use Directorist\Utils\Database\Query\Compilers\Compiler;
use Directorist\Utils\Database\Eloquent\Relations\Relation;
use Directorist\Utils\Database\Clauses\WhereClause;
use Directorist\Utils\Database\Clauses\HavingClause;
use wpdb;
use stdClass;
class Builder extends Relationship
{
    use WhereClause, HavingClause;
    /**
     * The current query value bindings.
     *
     * @var array
     */
    protected $bindings = [];
    /**
     * The model being queried.
     *
     * @param \Directorist\Utils\Database\Eloquent\Model
     */
    public $model;
    /**
     *
     * @var string
     */
    public $from;
    /**
     *
     * @var string
     */
    public $as;
    /**
     * The groupings for the query.
     *
     * @var array
     */
    public $groups;
    /**
     * An aggregate function and column to be run.
     *
     * @var array
     */
    public $aggregate;
    /**
     * The columns that should be returned.
     *
     * @var array
     */
    public $columns = [];
    /**
     * Indicates if the query returns distinct results.
     *
     * Occasionally contains the columns that should be distinct.
     *
     * @var bool|array
     */
    public $distinct = \false;
    /**
     * The table joins for the query.
     *
     * @var array
     */
    public $joins = [];
    /**
     * The table limit for the query.
     *
     * @var int
     */
    public $limit;
    /**
     * The orderings for the query.
     *
     * @var array
     */
    public $orders;
    /**
     * The number of records to skip.
     *
     * @var int
     */
    public $offset;
    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    protected $relations = [];
    public $count_relations = [];
    /**
     * All of the available clause operators.
     *
     * @var string[]
     */
    public $operators = ['=', '<', '>', '<=', '>=', '<>', '!=', '<=>', 'like', 'like binary', 'not like', 'ilike', '&', '|', '^', '<<', '>>', '&~', 'rlike', 'not rlike', 'regexp', 'not regexp', '~', '~*', '!~', '!~*', 'similar to', 'not similar to', 'not ilike', '~~*', '!~~*'];
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /**
     * Set the table which the query is targeting.
     *
     * @param  Builder  $query
     * @param  string|null  $as
     * @return $this
     */
    public function from(string $table, $as = null)
    {
        $this->from = $this->model->resolver()->table($table);
        $this->as = \is_null($as) ? $table : $as;
        return $this;
    }
    /**
     * Set the columns to be selected.
     *
     * @param  array|mixed  $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->columns = \is_array($columns) ? $columns : \func_get_args();
        return $this;
    }
    /**
     * Force the query to only return distinct results.
     *
     * @param  mixed  ...$distinct
     * @return $this
     */
    public function distinct()
    {
        $this->distinct = \true;
        return $this;
    }
    /**
     * Set the relationships that should be eager loaded.
     *
     * @param  string|array  $relations
     * @param  string|(Closure(static): mixed)|array|null $callback
     * @return $this
     */
    public function with($relations, $callback = null)
    {
        if (!\is_array($relations)) {
            $relations = [$relations => $callback];
        }
        foreach ($relations as $relation => $callback) {
            if (\is_int($relation)) {
                $relation = $callback;
            }
            $current =& $this->relations;
            // Traverse the items string and create nested arrays
            $items = \explode('.', $relation);
            foreach ($items as $key) {
                if (!isset($current[$key])) {
                    $query = new static($this->model);
                    $current[$key] = ['query' => $query, 'children' => []];
                } else {
                    $query = $current[$key]['query'];
                }
                $current =& $current[$key]['children'];
            }
            // Apply the callback to the last item
            if (\is_callable($callback)) {
                \call_user_func($callback, $query);
            }
        }
        return $this;
    }
    /**
     * @param  string $relations
     * @param  (Closure(static): mixed)|null $callback
     * @return $this
     */
    public function with_count($relations, $callback = null)
    {
        $relation_keys = \explode(' as ', $relations);
        /**
         * @var Relation $relationship
         */
        $relationship = $this->model->{$relation_keys[0]}();
        if (!$relationship instanceof HasMany) {
            return $this;
        }
        $related = $relationship->get_related();
        $table_name = $related::get_table_name();
        $total_key = $relation_keys[1];
        $join_alias = $total_key . '_count';
        $columns = $this->columns;
        $columns[] = "COALESCE({$join_alias}.{$total_key}, 0) as {$total_key}";
        $this->select($columns);
        return $this->left_join("{$table_name} as {$join_alias}", function (JoinClause $join) use($relationship, $total_key, $callback) {
            $join->on_column("{$join->as}.{$relationship->foreign_key}", '=', "{$this->as}.{$relationship->local_key}")->select("{$join->as}.{$relationship->foreign_key}", "COUNT(*) AS {$total_key}")->group_by("{$join->as}.{$relationship->foreign_key}");
            if (\is_callable($callback)) {
                \call_user_func($callback, $join);
            }
        });
    }
    /**
     * Add a join clause to the query.
     *
     * @param  string $table
     * @param  (Closure(JoinClause): mixed)|array|string $first
     * @param  string|null  $operator
     * @param  string|null $second
     * @param  string $type
     * @param  bool $where
     * @return $this
     */
    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = \false)
    {
        $join = new JoinClause($table, $type, $this->model);
        if (\is_callable($first)) {
            \call_user_func($first, $join);
        } else {
            if ($where) {
                $join->where($first, $operator, $second);
            } else {
                $join->on_column($first, $operator, $second);
            }
        }
        $this->joins[] = $join;
        return $this;
    }
    /**
     * Add a left join to the query.
     *
     * @param  string  $table
     * @param  (Closure(JoinClause): mixed)|array|string $first
     * @param  string|null $operator
     * @param  string|null $second
     * @param  bool $where
     * @return $this
     */
    public function left_join($table, $first, $operator = null, $second = null, $where = \false)
    {
        return $this->join($table, $first, $operator, $second, 'left', $where);
    }
    /**
     * Add a right join to the query.
     *
     * @param  string $table
     * @param  (Closure(JoinClause): mixed)|array|string $first
     * @param  string|null $operator
     * @param  string|null $second
     * @param  bool $where
     * @return $this
     */
    public function right_join($table, $first, $operator = null, $second = null, $where = \false)
    {
        return $this->join($table, $first, $operator, $second, 'right', $where);
    }
    /**
     * Add a "group by" clause to the query.
     *
     * @param  array|string  ...$groups
     * @return $this
     */
    public function group_by($groups)
    {
        $this->groups = \is_array($groups) ? $groups : \func_get_args();
        return $this;
    }
    /**
     * Add an "order by" clause to the query.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function order_by($column, $direction = 'asc')
    {
        $direction = \strtolower($direction);
        if (!\in_array($direction, ['asc', 'desc'], \true)) {
            throw new InvalidArgumentException('Order direction must be "asc" or "desc".');
        }
        $this->orders[] = ['column' => $column, 'direction' => $direction];
        return $this;
    }
    /**
     * Add a descending "order by" clause to the query.
     * @return $this
     */
    public function order_by_desc($column)
    {
        return $this->order_by($column, 'desc');
    }
    /**
     * Add an "order by raw" clause to the query.
     *
     * @param  string  $sql
     * @return $this
     */
    public function order_by_raw(string $sql)
    {
        $this->orders[] = ['column' => $sql, 'direction' => ''];
        return $this;
    }
    /**
     * Set the "offset" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function offset(int $value)
    {
        $this->offset = \max(0, $value);
        return $this;
    }
    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit(int $value)
    {
        $this->limit = \max(1, $value);
        return $this;
    }
    /**
     * Get the SQL representation of the query.
     *
     * @return string
     */
    public function to_sql()
    {
        if (empty($this->columns)) {
            $this->columns = ['*'];
        }
        $compiler = new Compiler();
        return $this->bind_values($compiler->compile_select($this));
    }
    /**
     * Insert new records into the database.
     *
     * @param  array  $values
     * @return string
     */
    public function to_sql_insert(array $values)
    {
        $compiler = new Compiler();
        return $this->bind_values($compiler->compile_insert($this, $values));
    }
    /**
     * Get the SQL representation of the query.
     *
     * @return string
     */
    public function to_sql_update(array $values)
    {
        $compiler = new Compiler();
        return $this->bind_values($compiler->compile_update($this, $values));
    }
    /**
     * Get the SQL representation of the query.
     *
     * @return string
     */
    public function to_sql_delete()
    {
        $compiler = new Compiler();
        return $this->bind_values($compiler->compile_delete($this));
    }
    public function get()
    {
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        return $this->process_relationships($wpdb->get_results($this->to_sql()), $this->relations, $this->model);
    }
    public function pagination(int $current_page, int $per_page = 10, int $min_per_page = 10, int $max_per_page = 100)
    {
        if ($per_page > $max_per_page || $per_page < $min_per_page) {
            $per_page = $max_per_page;
        }
        if (0 >= $current_page) {
            $current_page = 1;
        }
        $offset = ($current_page - 1) * $per_page;
        return $this->limit($per_page)->offset($offset)->get();
    }
    /**
     * @return stdClass|null
     */
    public function first()
    {
        $data = $this->limit(1)->get();
        return isset($data[0]) ? $data[0] : null;
    }
    /**
     * Insert new records into the database.
     *
     * @param  array  $values
     * @return bool|integer
     */
    public function insert(array $values)
    {
        $sql = $this->to_sql_insert($values);
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        return $wpdb->query($sql);
    }
    /**
     * Insert new single record into the database and get id.
     *
     * @param  array  $values
     */
    public function insert_get_id(array $values)
    {
        $this->insert($values);
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        return $wpdb->insert_id;
    }
    /**
     * Update records in the database.
     *
     * @param array $values
     * @return integer
     */
    public function update(array $values)
    {
        $sql = $this->to_sql_update($values);
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $result = $wpdb->query($sql);
        return $result;
    }
    /**
     * Delete records from the database.
     *
     * @return mixed
     */
    public function delete()
    {
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        return $wpdb->query($this->to_sql_delete());
    }
    /**
     * Prepare Query Values
     *
     * @param string $sql
     * @return string
     */
    public function bind_values(string $sql)
    {
        if (empty($this->bindings)) {
            return $sql;
        }
        global $wpdb;
        /**
         * @var wpdb $wpdb
         */
        $sql_query = $wpdb->prepare($sql, ...\array_map(
            //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            function ($value) {
                return $value instanceof DateTime ? $value->format('Y-m-d H:i:s') : $value;
            },
            $this->bindings
        ));
        $this->bindings = [];
        return $sql_query;
    }
    /**
     * Set query values for the using wpdb::prepare
     *
     * @param mixed $value
     * @return string
     */
    public function set_binding($value)
    {
        if (\is_null($value)) {
            return "null";
        }
        $this->bindings[] = $value;
        $type = \gettype($value);
        if ('integer' === $type || 'boolean' === $type) {
            return '%d';
        }
        if ('double' === $type) {
            return '%f';
        }
        return '%s';
    }
    public function get_bindings()
    {
        return $this->bindings;
    }
    public function set_bindings(array $bindings)
    {
        return $this->bindings = \array_merge($this->bindings, $bindings);
    }
    public function aggregate($function, $columns = ['*'])
    {
        $results = $this->set_aggregate($function, $columns)->get();
        return (int) $results[0]->aggregate;
    }
    public function aggregate_to_sql($function, $columns = ['*'])
    {
        return $this->set_aggregate($function, $columns)->to_sql();
    }
    /**
     * Set the aggregate property without running the query.
     *
     * @param  string  $function
     * @param  array  $columns
     * @return $this
     */
    protected function set_aggregate($function, $columns)
    {
        $this->aggregate = \compact('function', 'columns');
        if (empty($this->groups)) {
            $this->orders = null;
        }
        return $this;
    }
    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     * @return int
     */
    public function count(string $column = '*')
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }
    /**
     * Retrieve the minimum value of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function min($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }
    /**
     * Retrieve the maximum value of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function max($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }
    /**
     * Retrieve the sum of the values of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function sum($column)
    {
        $result = $this->aggregate(__FUNCTION__, [$column]);
        return $result ?: 0;
    }
    /**
     * Retrieve the average of the values of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function avg($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }
    /**
     * Prepare the value and operator for a where clause.
     *
     * @param  string  $value
     * @param  string  $operator
     * @param  bool  $use_default
     * @return array
     *
     * @throws InvalidArgumentException
     */
    protected function prepare_value_and_operator($value, $operator, $use_default = \false)
    {
        if ($use_default) {
            return [$operator, '='];
        } elseif ($this->invalid_operatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }
        return [$value, $operator];
    }
    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param  string  $operator
     * @param  mixed  $value
     * @return bool
     */
    protected function invalid_operatorAndValue($operator, $value)
    {
        return \is_null($value) && \in_array($operator, $this->operators) && !\in_array($operator, ['=', '<>', '!=']);
    }
    /**
     * Determine if the given operator is supported.
     *
     * @param  string  $operator
     * @return bool
     */
    protected function invalid_operator($operator)
    {
        return !\is_string($operator) || !\in_array(\strtolower($operator), $this->operators, \true);
    }
}
