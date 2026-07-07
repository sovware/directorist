<?php

namespace Directorist\Utils\Database\Query\Compilers;

\defined("ABSPATH") || exit;
use Directorist\Utils\Database\Query\Builder;
use Directorist\Utils\Database\Query\JoinClause;
class Compiler
{
    /**
     * The components that make up a select clause.
     *
     * @var string[]
     */
    protected $select_components = ['aggregate', 'columns', 'from', 'joins', 'wheres', 'groups', 'havings', 'orders', 'limit', 'offset'];
    /**
     * Compile a select query into SQL.
     *
     * @param  Builder $query
     * @return string
     */
    public function compile_select(Builder $query)
    {
        return $this->concatenate($this->compile_components($query));
    }
    /**
     * Compile an insert statement into SQL.
     *
     * @param  Builder $query
     * @param  array  $values
     * @return string
     */
    public function compile_insert(Builder $query, array $values)
    {
        if (!\is_array(\reset($values))) {
            $values = [$values];
        } else {
            foreach ($values as $key => $value) {
                \ksort($value);
                $values[$key] = $value;
            }
        }
        $columns = $this->columnize(\array_keys(\reset($values)));
        $parameters = \implode(', ', \array_map(function ($record) use($query) {
            return '(' . \implode(', ', \array_map(function ($item) use($query) {
                return $query->set_binding($item);
            }, $record)) . ')';
        }, $values));
        $table = $query->from;
        return "insert into {$table} ({$columns}) values {$parameters}";
    }
    /**
     * Compile an update statement into SQL.
     *
     * @param  Builder $query
     * @param  array  $values
     * @return string
     */
    public function compile_update(Builder $query, array $values)
    {
        $keys = \array_keys($values);
        $columns = \implode(', ', \array_map(function ($value, $key) use($query) {
            return $key . ' = ' . $query->set_binding($value);
        }, $values, $keys));
        $where = $this->compile_wheres($query);
        return "update {$query->from} set {$columns} {$where}";
    }
    /**
     * Compile a delete statement into SQL.
     *
     * @param  Builder $query
     * @return string
     */
    public function compile_delete(Builder $query)
    {
        $where = $this->compile_wheres($query);
        $joins = $this->compile_joins($query, $query->joins);
        return "delete {$query->as} from {$query->from} as {$query->as} {$joins} {$where}";
    }
    /**
     * Compile the components necessary for a select clause.
     *
     * @param  Builder  $query
     * @return array
     */
    protected function compile_components(Builder $query)
    {
        $sql = [];
        $clauses = $query->get_clauses();
        foreach ($this->select_components as $component) {
            $method = 'compile_' . $component;
            if (isset($query->{$component})) {
                $sql[$component] = $this->{$method}($query, $query->{$component});
            } elseif (isset($clauses[$component])) {
                $sql[$component] = $this->{$method}($query, $clauses[$component]);
            }
        }
        return $sql;
    }
    /**
     * Compile the "select *" portion of the query.
     *
     * @param  Builder $query
     * @param  array  $columns
     * @return string|null
     */
    protected function compile_columns(Builder $query, $columns)
    {
        if (!\is_null($query->aggregate)) {
            return;
        }
        if ($query->distinct) {
            $select = 'select distinct ';
        } else {
            $select = 'select ';
        }
        return $select . $this->columnize($columns);
    }
    /**
     * Compile an aggregated select clause.
     *
     * @param  Builder $query
     * @param  array  $aggregate
     * @return string
     */
    protected function compile_aggregate(Builder $query, $aggregate)
    {
        $column = $this->columnize($query->aggregate['columns']);
        if ($query->distinct) {
            $column = 'distinct ' . $column;
        }
        return 'select ' . $aggregate['function'] . '(' . $column . ') as aggregate';
    }
    /**
     * Compile the "from" portion of the query.
     *
     * @param  Builder  $query
     * @param string $table
     * @return string
     */
    protected function compile_from(Builder $query, $table)
    {
        if (\is_null($query->as)) {
            return 'from ' . $table;
        }
        return "from {$table} as {$query->as}";
    }
    /**
     * Compile the "where" portions of the query.
     *
     * @param  Builder  $query
     * @return string
     */
    public function compile_wheres(Builder $query)
    {
        if (empty($query->get_wheres())) {
            return '';
        }
        return $this->compile_where_or_having($query, $query->get_wheres());
    }
    /**
     * Compile the "join on" portions of the query.
     *
     * @param JoinClause $query
     * @return string
     */
    public function compile_ons(JoinClause $query)
    {
        if (empty($query->get_ons())) {
            return '';
        }
        return $this->compile_where_or_having($query, $query->get_ons(), 'on');
    }
    protected function compile_where_or_having(Builder $query, array $items, string $type = 'where')
    {
        $where_query = $type;
        foreach ($items as $where) {
            switch ($where['type']) {
                case 'basic':
                    $basic = $where['not'] ? "{$where['boolean']} not" : $where['boolean'];
                    $where_query .= " {$basic} {$where['column']} {$where['operator']} {$query->set_binding($where['value'])}";
                    break;
                case 'like':
                    $like = $where['not'] ? 'not like' : 'like';
                    $where_query .= " {$where['boolean']} {$where['column']} {$like} {$query->set_binding($where['value'])}";
                    break;
                case 'between':
                    $between = $where['not'] ? 'not between' : 'between';
                    $where_query .= " {$where['boolean']} {$where['column']} {$between} {$query->set_binding($where['values'][0])} and {$query->set_binding($where['values'][1])}";
                    break;
                case 'in':
                    $in = $where['not'] ? 'not in' : 'in';
                    $values = \implode(', ', \array_map(function ($value) use($query) {
                        return $query->set_binding($value);
                    }, $where['values']));
                    $where_query .= " {$where['boolean']} {$where['column']} {$in} ({$values})";
                    break;
                case 'column':
                    if (\is_null($where['value'])) {
                        $where_query .= " {$where['boolean']} {$where['column']}";
                    } else {
                        $where_query .= " {$where['boolean']} {$where['column']} {$where['operator']} {$where['value']}";
                    }
                    break;
                case 'exists':
                    /**
                     * @var Builder $exists_query
                     */
                    $exists_query = $where['query'];
                    $sql = $exists_query->to_sql();
                    $exists = $where['not'] ? 'not exists' : 'exists';
                    $where_query .= " {$where['boolean']} {$exists} ({$sql})";
                    break;
                case 'raw':
                    $where_query .= " {$where['boolean']} {$where['sql']}";
                    break;
                case 'is_null':
                    $null = $where['not'] ? "not null" : "null";
                    $where_query .= " {$where['boolean']} {$where['column']} is {$null}";
                    break;
                case 'nested':
                    /**
                     * @var Builder $nested_query
                     */
                    $nested_query = $where['query'];
                    switch ($type) {
                        case 'where':
                            $nested_items = $nested_query->get_wheres();
                            break;
                        case 'having':
                            $nested_items = $nested_query->get_havings();
                            break;
                        case 'on':
                            $nested_items = $nested_query->get_ons();
                            break;
                    }
                    if (!empty($nested_items)) {
                        $sql = \ltrim($this->compile_where_or_having($nested_query, $nested_items, $type), $type);
                        $query->set_bindings($nested_query->get_bindings());
                        $nested = $where['not'] ? "{$where['boolean']} not" : $where['boolean'];
                        $where_query .= " {$nested} ({$sql} )";
                    }
            }
        }
        $where_query = \trim($this->remove_leading_boolean($where_query));
        if (\in_array($where_query, ['where', 'having', 'on', 'where not', 'having not', 'on not'], \true)) {
            return '';
        }
        return $where_query;
    }
    /**
     * Compile the "join" portions of the query.
     *
     * @param  Builder  $query
     * @param  array  $joins
     * @return string
     */
    protected function compile_joins(Builder $query, $joins)
    {
        return \implode(' ', \array_map(function (JoinClause $join) {
            if (!empty($join->columns) || !empty($join->get_wheres())) {
                $query = "({$join->to_sql()})";
            } else {
                $query = $join->from;
            }
            $query .= " as {$join->as}";
            if (!empty($join->joins)) {
                $query = "({$query} {$this->compile_joins($join, $join->joins)})";
            }
            return $join->bind_values(\trim("{$join->type} join {$query} {$this->compile_ons($join)}"));
        }, $joins));
    }
    /**
     * Compile the "order by" portions of the query.
     *
     * @param  Builder  $query
     * @param  array  $orders
     * @return string
     */
    protected function compile_orders(Builder $query, $orders)
    {
        if (empty($orders)) {
            return '';
        }
        return 'order by ' . \implode(', ', \array_map(function ($order) {
            return $order['column'] . ' ' . $order['direction'];
        }, $orders));
    }
    /**
     * Compile the "having" portions of the query.
     *
     * @param  Builder $query
     * @return string
     */
    protected function compile_havings(Builder $query)
    {
        if (empty($query->get_havings())) {
            return '';
        }
        return $this->compile_where_or_having($query, $query->get_havings(), 'having');
    }
    /**
     * Compile the "offset" portions of the query.
     *
     * @param  Builder $query
     * @param  int  $offset
     * @return string
     */
    protected function compile_offset(Builder $query, $offset)
    {
        return 'offset ' . $query->set_binding($offset);
    }
    /**
     * Compile the "limit" portions of the query.
     *
     * @param  Builder  $query
     * @param  int  $limit
     * @return string
     */
    protected function compile_limit(Builder $query, $limit)
    {
        return 'limit ' . $query->set_binding($limit);
    }
    /**
     * Compile the "group by" portions of the query.
     *
     * @param  Builder  $query
     * @param array $groups
     * @return string
     */
    protected function compile_groups(Builder $query, $groups)
    {
        return 'group by ' . \implode(', ', $groups);
    }
    /**
     * Concatenate an array of segments, removing empties.
     *
     * @param  array  $segments
     * @return string
     */
    protected function concatenate($segments)
    {
        return \implode(' ', \array_filter($segments, function ($value) {
            return (string) $value !== '';
        }));
    }
    /**
     * Convert an array of column names into a delimited string.
     *
     * @param  array  $columns
     * @return string
     */
    public function columnize(array $columns)
    {
        return \implode(', ', $columns);
    }
    /**
     * Remove the leading boolean from a statement.
     *
     * @param  string  $value
     * @return string
     */
    protected function remove_leading_boolean($value)
    {
        return \preg_replace('/and |or /i', '', $value, 1);
    }
}
