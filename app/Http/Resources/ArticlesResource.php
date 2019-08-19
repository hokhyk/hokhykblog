<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticlesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
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
            'user_info' => new UserInfoResource($this->whenLoaded('user'))
        ];
    }
}
