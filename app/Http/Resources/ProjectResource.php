<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'phone' => $this->phone,
            'start_date' => $this->start_date->format('d/m/Y'),
            'end_date' => $this->end_date->format('d/m/Y'),
            'description' => $this->description,
            'status' => $this->status,
            'image' => asset($this->image),
            'users' =>  UserResource::collection($this->users),
        ];
    }
}
