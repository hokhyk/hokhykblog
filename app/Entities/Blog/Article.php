<?php

namespace App\Entities\Blog;

use Jenssegers\Mongodb\Eloquent\Model;
//use Prettus\Repository\Contracts\Transformable;
//use Prettus\Repository\Traits\TransformableTrait;

use MongoDB\Collection as MongoCollection;
/**
 * Class Article.
 *
 * @package namespace App\Entities\Blog;
 */
class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content'];

}
