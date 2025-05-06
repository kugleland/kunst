<?php

namespace App\Http\Resources;

use App\Models\Fact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

class FactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = auth('sanctum')->user();
        
        $factModel = Fact::find($this->id);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'category_title' => ($this->category) ? $this->category->title : null,
            'category_image_url' => ($this->category) ? $this->category->image_url : '',
            'upvotes_count' => $this->upvoters()->count(), // Count of upvotes
            'downvotes_count' => $this->downvoters()->count(), // Count of downvotes
            'user_vote' => $this->getUserVoteStatus($user, $factModel), // Current user's vote status,
            'user_bookmark' => $this->getUserBookmarkStatus($user, $factModel), // Current user's bookmark status,
            'icon' => $this->category->icon,
            'color' => $this->category->color,

        ];
    }

    protected function getUserVoteStatus($user, $fact)
    {
        if (!$user) {
            return null;
        }

        if ($user->hasUpvoted($fact)) {
            return 'upvoted';
        }

        if ($user->hasDownvoted($fact)) {
            return 'downvoted';
        }

        return $user->hasVoted($fact);
    }

    protected function getUserBookmarkStatus($user, $fact)
    {
        if (!$user) {
            return null;
        }

        return $user->hasBookmarked($fact);
    }
}
