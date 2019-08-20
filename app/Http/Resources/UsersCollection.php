<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersCollection extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'article_info' => ArticlesResource::collection($this->whenLoaded('articles'))
        ];
    }
}
