<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportSalesCategory;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    protected $salesCustomer;

    public function index()
    {
        $sales = Sale::with('details')->get();
        return response()->json(['sales' => $sales], 200);
    }

    public function show($id)
    {
        $sale = Sale::with('details')->find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }
        return response()->json(['sale' => $sale], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'm_customer_id' => 'required|uuid',
            'product_detail' => 'required|array|min:1',
            'product_detail.*.m_product_id' => 'required',
            'product_detail.*.m_product_detail_id' => 'required',
            'product_detail.*.total_item' => 'required|integer|min:1',
            'product_detail.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'm_customer_id' => $request->m_customer_id,
                'date' => Carbon::now()->toDateString(),
            ]);

            foreach ($request->product_detail as $detail) {
                SaleDetail::create([
                    't_sales_id' => $sale->id,
                    'm_product_id' => $detail['m_product_id'],
                    'm_product_detail_id' => $detail['m_product_detail_id'],
                    'total_item' => $detail['total_item'],
                    'price' => $detail['price'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Sale created successfully', 'sale' => $sale], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create sale', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'm_customer_id' => 'required|uuid',
            'product_detail' => 'required|array|min:1',
            'product_detail.*.m_product_id' => 'required',
            'product_detail.*.m_product_detail_id' => 'required',
            'product_detail.*.total_item' => 'required|integer|min:1',
            'product_detail.*.price' => 'required|numeric|min:0',
        ]);

        $sale = Sale::find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        DB::beginTransaction();
        try {
            $sale->update([
                'm_customer_id' => $request->m_customer_id,
            ]);

            $sale->details()->delete();
            foreach ($request->product_detail as $detail) {
                SaleDetail::create([
                    't_sales_id' => $sale->id,
                    'm_product_id' => $detail['m_product_id'],
                    'm_product_detail_id' => $detail['m_product_detail_id'],
                    'total_item' => $detail['total_item'],
                    'price' => $detail['price'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Sale updated successfully', 'sale' => $sale], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update sale', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $sale = Sale::find($id);
        if (!$sale) {
            return response()->json(['message' => 'Sale not found'], 404);
        }

        DB::beginTransaction();
        try {
            $sale->details()->delete();
            $sale->delete();
            DB::commit();
            return response()->json(['message' => 'Sale deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete sale', 'error' => $e->getMessage()], 500);
        }
    }

    public function saleCustomer(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $sales = Sale::with(['customer', 'user'])
            ->when($request->m_customer_id, function ($query) use ($request) {
                return $query->where('m_customer_id', $request->m_customer_id);
            })
            ->when($request->m_user_id, function ($query) use ($request) {
                return $query->where('m_user_id', $request->m_user_id);
            })
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })
            ->get();

        return response()->json(['sales' => $sales], 200);
    }
}
