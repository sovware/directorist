<?php

namespace Directorist\Utils\Database\Eloquent\Relations;

\defined("ABSPATH") || exit;
use Directorist\Utils\Database\Eloquent\Model;
class BelongsToMany extends Relation
{
    public Model $pivot;
    public $foreign_pivot_key;
    public $local_pivot_key;
    /**
     * @param  string $related
     * @param  string $pivot
     * @param  string $foreign_pivot_key
     * @param  string $local_pivot_key
     * @param  string $foreign_key
     * @param  string $local_key
     * @return BelongsToMany
     */
    public function __construct($related, $pivot, $foreign_pivot_key, $local_pivot_key, $foreign_key, $local_key)
    {
        $this->pivot = new $pivot();
        $this->foreign_pivot_key = $foreign_pivot_key;
        $this->local_pivot_key = $local_pivot_key;
        parent::__construct($related, $foreign_key, $local_key);
    }
}
