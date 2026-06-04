<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('product')->orderByDesc('id')->get();
        $products   = Product::orderBy('name')->get();
        return view('flash_sale.index', compact('flashSales', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|numeric|min:1',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'sale_price.required' => 'Vui lòng nhập giá Flash Sale.',
            'sale_price.min'      => 'Giá phải lớn hơn 0.',
            'ends_at.after'       => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        FlashSale::create([
            'product_id' => $request->product_id,
            'sale_price' => $request->sale_price,
            'starts_at'  => Carbon::parse($request->starts_at),
            'ends_at'    => Carbon::parse($request->ends_at),
        ]);

        return redirect()->route('admin.flash-sales.index')
                         ->with('success', 'Đã thêm Flash Sale thành công!');
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::orderBy('name')->get();
        return view('flash_sale.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|numeric|min:1',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
        ]);

        $flashSale->update([
            'product_id' => $request->product_id,
            'sale_price' => $request->sale_price,
            'starts_at'  => Carbon::parse($request->starts_at),
            'ends_at'    => Carbon::parse($request->ends_at),
        ]);

        return redirect()->route('admin.flash-sales.index')
                         ->with('success', 'Cập nhật Flash Sale thành công!');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->route('admin.flash-sales.index')
                         ->with('success', 'Đã xóa Flash Sale!');
    }
}
