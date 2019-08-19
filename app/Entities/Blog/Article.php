<?php

namespace App\Entities\Blog;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model;
//use MongoDB\Collection as MongoCollection;

/**
 * Class Article.
 *
 * @package namespace App\Entities\Blog;
 */
class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'article_content'];

    protected $dates = [];

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'string',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Entities\User');
    }

}
