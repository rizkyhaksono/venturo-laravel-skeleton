<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollections extends ResourceCollection
{
  public function toArray($request)
  {
    return [
      'list' => $this->collection, // otomatis mengikuti format CustomerResource
      'meta' => [
        'links' => $this->resource->getUrlRange(1, $this->resource->lastPage()),
        'total' => $this->resource->total()
      ]
    ];
  }
}
