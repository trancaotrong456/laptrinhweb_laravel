@extends('layouts.admin')
@section('title', 'Chỉnh sửa Voucher - TTP Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-edit" style="color:#2563eb;"></i> Chỉnh sửa Voucher</h1>
    <a href="{{ route('coupons.index') }}" class="btn-outline-admin"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="admin-card" style="max-width: 700px; margin: 0 auto;">
    <div class="admin-card-body" style="padding: 24px;">
        <form action="{{ route('coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Mã Voucher (Code) <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="code" class="form-ctrl" value="{{ old('code', $coupon->code) }}" required style="text-transform:uppercase;">
                    @error('code')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-ctrl">Loại giảm giá <span style="color:#ef4444;">*</span></label>
                    <select name="type" class="form-select-ctrl" required>
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                        <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-ctrl">Giá trị giảm <span style="color:#ef4444;">*</span></label>
                    <input type="number" step="0.01" name="value" class="form-ctrl" value="{{ old('value', $coupon->type === 'percent' ? rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') : (int)$coupon->value) }}" required>
                    @error('value')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-ctrl">Đơn tối thiểu (VNĐ)</label>
                    <input type="number" step="0.01" name="min_order_value" class="form-ctrl" value="{{ old('min_order_value', (int)$coupon->min_order_value) }}">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div>
                    <label class="form-label-ctrl">Số lần sử dụng tối đa</label>
                    <input type="number" name="usage_limit" class="form-ctrl" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                </div>
                <div>
                    <label class="form-label-ctrl">Trạng thái</label>
                    <select name="is_active" class="form-select-ctrl">
                        <option value="1" {{ old('is_active', $coupon->is_active) == '1' ? 'selected' : '' }}>Kích hoạt (Cho phép dùng)</option>
                        <option value="0" {{ old('is_active', $coupon->is_active) == '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                    </select>
                </div>
            </div>

            <div style="text-align:right;border-top:1px solid #f1f5f9;padding-top:20px;">
                <button type="submit" class="btn-primary-admin"><i class="fas fa-save"></i> Cập nhật Voucher</button>
            </div>
        </form>
    </div>
</div>
@endsection