<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use JsonSerializable;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource instanceof \Illuminate\Database\Eloquent\Model ? $this->id : $this->resource,
            'name' => $this->resource instanceof \Illuminate\Database\Eloquent\Model ? $this->name : null,
            'email' => $this->resource instanceof \Illuminate\Database\Eloquent\Model ? $this->email : null,
            'photo_url' => ! empty($this->photo) ? Storage::disk('public')->url($this->photo) : Storage::disk('public')->url('../assets/img/no-image.png'),
            'phone_number' => $this->resource instanceof \Illuminate\Database\Eloquent\Model ? $this->phone_number : null,
            'updated_security' => $this->resource instanceof \Illuminate\Database\Eloquent\Model ? $this->updated_security : null,
            'm_user_roles_id' => $this->resource instanceof \Illuminate\Database\Eloquent\Model ? (string) $this->m_user_roles_id : null,
            'access' => isset($this->role->access) ? json_decode($this->role->access) : [],
        ];
    }
}
