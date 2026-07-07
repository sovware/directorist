<?php

namespace Directorist\Utils\Database\Query;

\defined("ABSPATH") || exit;
use Directorist\Utils\Database\Clauses\OnClause;
use Directorist\Utils\Database\Eloquent\Model;
class JoinClause extends Builder
{
    use OnClause;
    /**
     * The type of join being performed.
     *
     * @var string
     */
    public $type;
    /**
     * The table the join clause is joining to.
     *
     * @var string
     */
    public $table;
    /**
     * Create a new join clause instance.
     * 
     * @param  string  $table
     * @param  string  $type
     * @return void
     */
    public function __construct(string $table, string $type, Model $model)
    {
        parent::__construct($model);
        $table = \explode(' as ', $table);
        $this->from($table[0], isset($table[1]) ? $table[1] : null);
        $this->type = $type;
    }
}
