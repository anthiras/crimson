<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
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
            'userId' => $this->user_id,
            'createdAt' => $this->created_at->__toString(),
            'expiresAt' => $this->expires_at,
            'paidAt' => $this->paid_at
        ];
    }
}
