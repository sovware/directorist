<?php

namespace Directorist\Utils\Database\Eloquent;

\defined("ABSPATH") || exit;
use Directorist\Utils\Database\Eloquent\Relations\BelongsToMany;
use Directorist\Utils\Database\Eloquent\Relations\BelongsToOne;
use Directorist\Utils\Database\Eloquent\Relations\HasMany;
use Directorist\Utils\Database\Eloquent\Relations\HasOne;
use Directorist\Utils\Database\Query\Builder;
use Directorist\Utils\Database\Resolver;
abstract class Model
{
    static abstract function get_table_name() : string;
    public abstract function resolver() : Resolver;
    /**
     * Begin querying the model.
     *
     * @return Builder
     */
    public static function query($as = null)
    {
        $model = new static();
        $builder = new Builder($model);
        $builder->from(static::get_table_name(), $as);
        return $builder;
    }
    /**
     * Define a one-to-many relationship.
     *
     * @param  string $related
     * @param  string $foreign_key
     * @param  HasMany
     */
    public function has_many(string $related, string $foreign_key, string $local_key)
    {
        return new HasMany($related, $foreign_key, $local_key);
    }
    /**
     * Define a one-to-many relationship.
     *
     * @param  string $related
     * @param  string  $foreign_key
     * @param  string  $local_key
     * @return HasOne
     */
    public function has_one($related, $foreign_key, $local_key)
    {
        return new HasOne($related, $foreign_key, $local_key);
    }
    /**
     * Define an inverse one-to-one relationship.
     *
     * @param  string $related
     * @param  string  $foreign_key
     * @param  string  $local_key
     * @return BelongsToOne
     */
    public function belongs_to_one($related, $foreign_key, $local_key)
    {
        return new BelongsToOne($related, $foreign_key, $local_key);
    }
    /**
     * Define an inverse many-to-many relationship.
     *
     * @param  string $related
     * @param  string $pivot
     * @param  string $foreign_pivot_key
     * @param  string $local_pivot_key
     * @param  string $foreign_key
     * @param  string $local_key
     * @return BelongsToMany
     */
    public function belongs_to_many($related, $pivot, $foreign_pivot_key, $local_pivot_key, $foreign_key, $local_key)
    {
        return new BelongsToMany($related, $pivot, $foreign_pivot_key, $local_pivot_key, $foreign_key, $local_key);
    }
}
