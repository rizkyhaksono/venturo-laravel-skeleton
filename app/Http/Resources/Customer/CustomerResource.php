<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'phone' => $this->phone,
      'photo' => $this->photo,
      'address' => $this->address,
      'email' => $this->user ? $this->user->email : "",
    ];
  }
}
