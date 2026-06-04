@extends('layout')
@section('title', 'Edit Coupon')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    <h3 class="mb-3">Edit Coupon: {{ $coupon->code }}</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('coupons.update', $coupon) }}" class="card shadow-sm border-0">
        @csrf
        @method('PUT')
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">Code</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percent</option>
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Value</label>
                <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon->value) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Min Order Value</label>
                <input type="number" step="0.01" min="0" name="min_order_value" value="{{ old('min_order_value', $coupon->min_order_value) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Max Discount (optional)</label>
                <input type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Usage Limit (optional)</label>
                <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Starts At</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ends At</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($coupon->ends_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection