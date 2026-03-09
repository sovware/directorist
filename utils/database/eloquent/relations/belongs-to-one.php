<?php

namespace Directorist\Utils\Database\Eloquent\Relations;

\defined("ABSPATH") || exit;
class BelongsToOne extends Relation
{
    public $wheres = [];
    public function relation_where(string $parent_column, $operator = null, $value = null, $boolean = 'and')
    {
        [$value, $operator] = $this->prepare_value_and_operator($value, $operator, \func_num_args() === 2);
        $this->wheres[] = ['column' => $parent_column, 'value' => $value, 'operator' => $operator, 'boolean' => $boolean];
        return $this;
    }
    public function relation_or_where(string $parent_column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->relation_where($parent_column, $operator, $value, 'or');
    }
    protected function prepare_value_and_operator($value, $operator, $use_default = \false)
    {
        if ($use_default) {
            return [$operator, '='];
        }
        // elseif ($this->invalid_operatorAndValue($operator, $value)) {
        //     throw new InvalidArgumentException('Illegal operator and value combination.');
        // }
        return [$value, $operator];
    }
}
