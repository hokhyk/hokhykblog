<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            '_id' => $this->_id,
            'title' => $this->title,
            'article_content' => $this->article_content,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
//            'user_info' => new UserInfoResource($this->whenLoaded('user'))
        ];
    }

    //TODO: Pagination information to be added...
    public function with($request)
    {
        return [
            'links'    => [
                'self' => url('api/articles/' . $this->id),
            ],
        ];
    }
}
