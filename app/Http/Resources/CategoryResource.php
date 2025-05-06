<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        #return parent::toArray($request);
        $locale = (app()->getLocale()) ? app()->getLocale() : 'da';

        #dd($this->facts);
        return [
            'id' => $this->id,
            // 'title' => $this->getTranslation('title', 'da'),
            // 'description' => $this->getTranslation('description', 'da'),
            'title' => $this->title,
            'description' => $this->description,
            'emoji' => $this->emoji,
            'icon' => $this->icon,
            'image_url' => $this->image_url,
            'color' => $this->color,
            'color_light' => $this->color_light,
            'color_dark' => $this->color_dark,
            'facts' => FactResource::collection($this->facts),
        ];
    }
}
