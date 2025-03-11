<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCollections extends JsonResource
{
  public function toArray($request)
  {
    return [
      'list' => $this->collection, // otomatis mengikuti format CustomerResource
      'meta' => [
        'links' => $this->getUrlRange(1, $this->lastPage()),
        'total' => $this->total()
      ]
    ];
  }
}
