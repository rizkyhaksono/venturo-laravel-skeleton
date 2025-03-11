<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class CustomerUpdateRequest extends FormRequest
{
  use ConvertsBase64ToFiles; // Library untuk convert base64 menjadi File

  public $validator;

  /**
   * Tampilkan pesan error ketika validasi gagal
   *
   * @return void
   */
  public function failedValidation(Validator $validator)
  {
    $this->validator = $validator;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'max:100',
      'photo' => 'file|image',
      'address' => '',
      'phone' => 'numeric5',
    ];
  }

  /**
   * inisialisasi key "photo" dengan value base64 sebagai "FILE"
   *
   * @return array
   */
  protected function base64FileKeys(): array
  {
    return [
      'photo' => 'foto-customer.jpg',
    ];
  }
}
