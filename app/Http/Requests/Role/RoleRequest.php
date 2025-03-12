<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    if ($this->isMethod('post')) {
      return $this->createRules();
    }

    return $this->updateRules();
  }

  private function createRules(): array
  {
    return [
      'name' => 'required|string',
      'access' => 'required|string',
    ];
  }

  private function updateRules(): array
  {
    return [
      'name' => 'string',
      'access' => 'string',
    ];
  }
}
