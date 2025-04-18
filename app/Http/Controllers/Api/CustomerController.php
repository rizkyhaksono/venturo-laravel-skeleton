<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Customer\CustomerHelper;
use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Resources\Customer\CustomerCollections;
use App\Http\Resources\Customer\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
  private $customer;
  private $user;

  public function __construct()
  {
    $this->customer = new CustomerHelper();
    $this->user = new UserHelper();
  }

  public function index(Request $request)
  {
    $filter = [
      'name' => $request->name ?? '',
    ];
    $customers = $this->customer->getAll($filter, 5, $request->sort ?? '');

    return response()->success(new CustomerCollections($customers['data']));
  }

  public function show($id)
  {
    $customer = $this->customer->getById($id);

    if (!($customer['status'])) {
      return response()->failed(['Data customer tidak ditemukan'], 404);
    }

    return response()->success(new CustomerResource($customer['data']));
  }

  public function store(CustomerCreateRequest $request)
  {
    if (isset($request->validator) && $request->validator->fails()) {
      return response()->failed($request->validator->errors());
    }
    try {
      DB::beginTransaction();

      $payload_user = $request->only(['email', 'name', 'password']);
      $user = $this->user->create($payload_user);

      $payload_customer = $request->only(['name', 'address', 'photo', 'phone']);
      $payload_customer['m_user_id'] = $user['data']->id;
      $customer = $this->customer->create($payload_customer);

      if (!$customer['status']) {
        return response()->failed($customer['error']);
      }

      DB::commit();
      return response()->success(new CustomerResource($customer['data']), "Customer berhasil ditambahkan");
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->failed($th);
    }
  }

  public function update(CustomerUpdateRequest $request)
  {
    if (isset($request->validator) && $request->validator->fails()) {
      return response()->failed($request->validator->errors());
    }
    try {
      DB::beginTransaction();

      $payload_user = $request->only(['email', 'name', 'password']);
      $payload_user['id'] = $request->m_user_id;
      $user = $this->user->update($payload_user, $payload_user['id'] ?? 0);

      $payload_customer = $request->only(['name', 'address', 'photo', 'id', 'phone']);
      $customer = $this->customer->update($payload_customer, $payload_customer['id'] ?? 0);

      if (!$customer['status']) {
        return response()->failed($customer['error']);
      }
      DB::commit();
      return response()->success(new CustomerResource($customer['data']), "Customer berhasil diubah");
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->failed($th);
    }
  }

  public function destroy($id)
  {
    $customer = $this->customer->delete($id);

    if (!$customer) {
      return response()->failed(['Mohon maaf data customer tidak ditemukan']);
    }

    return response()->success($customer, "Customer berhasil dihapus");
  }
}
