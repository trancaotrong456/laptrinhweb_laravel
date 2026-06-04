@extends('layouts.admin')

@section('title', 'Quản lý Flash Sale - TTP Admin')

@push('styles')
<style>
/* ═══════ PAGE HEADER ═══════ */
.fs-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px;
}
.fs-page-title {
    font-size: 22px; font-weight: 800; color: #1e293b;
    display: flex; align-items: center; gap: 10px;
}
.fs-page-title i { color: #ef4444; }

/* ═══════ TABLE CARD ═══════ */
.fs-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 15px rgba(0,0,0,.04);
    overflow: hidden;
}
.fs-card table thead th {
    background: #fff5f5;
    color: #ef4444;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: .5px;
    padding: 14px 18px;
    border-bottom: 2px solid #fecaca;
}
.fs-card table td {
    padding: 14px 18px;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
    font-size: 14px;
    color: #1e293b;
}
.fs-card table tbody tr:last-child td { border-bottom: none; }
.fs-card table tbody tr:hover { background: #fff5f5; }

/* Product thumbnail */
.fs-thumb {
    width: 48px; height: 48px;
    border-radius: 10px; object-fit: cover;
    border: 1px solid #e2e8f0;
}
.fs-no-thumb {
    width: 48px; height: 48px;
    border-radius: 10px; background: #f8fafc;
    display: inline-flex; align-items: center; justify-content: center;
    color: #cbd5e1; font-size: 18px;
}
.fs-product-name { font-weight: 600; color: #1e293b; }
.fs-product-name small { color: #94a3b8; font-weight: 400; font-size: 12px; }

/* Price */
.fs-original-price { text-decoration: line-through; color: #94a3b8; font-size: 13px; }
.fs-sale-price { color: #ef4444; font-weight: 800; font-size: 16px; }

/* Status badges */
.fs-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 50px;
    font-size: 12px; font-weight: 700;
}
.fs-badge.active   { background: #d1fae5; color: #059669; }
.fs-badge.upcoming { background: #fef9c3; color: #ca8a04; }
.fs-badge.ended    { background: #f1f5f9; color: #94a3b8; }

/* Time */
.fs-time { font-size: 12.5px; color: #475569; line-height: 1.6; }
.fs-time span { display: block; }

/* Action buttons */
.fs-action-btn {
    width: 34px; height: 34px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #e2e8f0; background: #fff; color: #64748b;
    text-decoration: none; transition: all .2s; cursor: pointer;
}
.fs-action-btn:hover.edit   { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
.fs-action-btn:hover.delete { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

/* Add button */
.btn-add-fs {
    background: #ef4444; color: #fff;
    padding: 10px 24px; border-radius: 50px;
    font-weight: 700; font-size: 14px;
    display: inline-flex; align-items: center; gap: 8px;
    border: none; cursor: pointer; transition: all .2s;
    text-decoration: none;
}
.btn-add-fs:hover {
    background: #dc2626; color: #fff;
    transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,.3);
}

/* ═══════ MODAL TWEAKS ═══════ */
.fs-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(15,23,42,.6); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none; transition: opacity .2s;
}
.fs-modal-overlay.show { opacity: 1; pointer-events: all; }
.fs-modal {
    background: #fff; border-radius: 16px;
    padding: 28px 32px 24px; width: 100%; max-width: 460px;
    box-shadow: 0 20px 60px rgba(0,0,0,.15);
    transform: translateY(-20px); transition: transform .25s;
}
.fs-modal-overlay.show .fs-modal { transform: translateY(0); }
.fs-modal-title {
    font-size: 20px; font-weight: 800; color: #1e293b;
    display: flex; align-items: center; gap: 10px; margin-bottom: 24px;
    padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;
}
.fs-modal-title i { color: #ef4444; font-size: 22px; }
.fs-form-group { margin-bottom: 20px; }
.fs-label {
    display: block; font-size: 13.5px; font-weight: 600;
    color: #334155; margin-bottom: 8px;
}

/* Đã sửa lỗi tràn viền */
.fs-input {
    width: 100%; height: 44px; padding: 0 14px;
    border: 1px solid #cbd5e1; border-radius: 8px;
    font-size: 14.5px; color: #1e293b; background: #fff;
    transition: all .2s; font-family: inherit;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    min-width: 0; 
}
.fs-input::placeholder { color: #94a3b8; }
.fs-input:focus { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); outline: none; }
.fs-input.is-invalid { border-color: #ef4444; background: #fef2f2; }
.fs-error { color: #ef4444; font-size: 12.5px; font-weight: 600; margin-top: 6px; display: block; }
.fs-modal-actions {
    display: flex; justify-content: flex-end; gap: 12px; 
    margin-top: 32px; padding-top: 20px;
    border-top: 1px solid #f1f5f9;
}

/* Đã sửa lỗi rớt chữ/chèn chữ ở nút bấm */
.btn-modal-cancel, .btn-modal-save {
    min-width: 110px; height: 42px; 
    padding: 0 20px; 
    white-space: nowrap; 
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    border-radius: 8px; font-weight: 600; font-size: 14.5px; 
    cursor: pointer; transition: all .2s; font-family: inherit;
}
.btn-modal-cancel {
    border: 1px solid #cbd5e1; background: #fff; color: #475569;
}
.btn-modal-cancel:hover { background: #f8fafc; color: #1e293b; border-color: #94a3b8; }
.btn-modal-save {
    background: #ef4444; color: #fff; border: none;
}
.btn-modal-save:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239,68,68,.25); }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="fs-page-header">
    <h1 class="fs-page-title">
        <i class="fas fa-bolt"></i> Quản lý Flash Sale
    </h1>
    <button class="btn-add-fs" onclick="openModal()">
        <i class="fas fa-plus"></i> Thêm Flash Sale
    </button>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert alert-success" style="border-radius:12px; background:#d1fae5; color:#059669; border:1px solid #a7f3d0; margin-bottom:20px;">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert" style="border-radius:12px; background:#fef2f2; color:#ef4444; border:1px solid #fecaca; margin-bottom:20px;">
    <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="alert" style="border-radius:12px; background:#fef2f2; color:#ef4444; border:1px solid #fecaca; margin-bottom:20px;">
    <i class="fas fa-exclamation-circle me-2"></i>
    <ul style="margin:0; padding-left:20px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

{{-- TABLE --}}
<div class="fs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá gốc</th>
                    <th>Giá Flash Sale</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th style="text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flashSales as $fs)
                @php
                    $status = $fs->status;
                    $img = null;
                    if ($fs->product && $fs->product->image) {
                        $img = str_contains($fs->product->image, '/')
                            ? asset('storage/' . $fs->product->image)
                            : asset('images/' . $fs->product->image);
                    }
                @endphp
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            @if($img)
                                <img src="{{ $img }}" class="fs-thumb" alt="{{ $fs->product->name }}">
                            @else
                                <span class="fs-no-thumb"><i class="fas fa-image"></i></span>
                            @endif
                            <div class="fs-product-name">
                                {{ $fs->product->name ?? 'N/A' }}
                                <small style="display:block;">#{{ $fs->product_id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fs-original-price">{{ number_format($fs->product->price ?? 0) }}đ</span>
                    </td>
                    <td>
                        <span class="fs-sale-price">{{ number_format($fs->sale_price) }}đ</span>
                        @if($fs->product && $fs->product->price > 0)
                        <div style="font-size:11px; color:#10b981; font-weight:600; margin-top:2px;">
                            -{{ round((1 - $fs->sale_price / $fs->product->price) * 100) }}%
                        </div>
                        @endif
                    </td>
                    <td class="fs-time">
                        <span><i class="fas fa-play-circle text-success me-1"></i>{{ $fs->starts_at->format('d/m H:i') }}</span>
                        <span><i class="fas fa-stop-circle text-danger me-1"></i>{{ $fs->ends_at->format('d/m H:i') }}</span>
                    </td>
                    <td>
                        <span class="fs-badge {{ $status }}">
                            @if($status === 'active')
                                <i class="fas fa-circle" style="font-size:7px;"></i> Đang diễn ra
                            @elseif($status === 'upcoming')
                                <i class="fas fa-clock"></i> Sắp diễn ra
                            @else
                                <i class="fas fa-check"></i> Đã kết thúc
                            @endif
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
                            <a href="{{ route('admin.flash-sales.edit', $fs->id) }}"
                               class="fs-action-btn edit" title="Chỉnh sửa">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.flash-sales.destroy', $fs->id) }}" method="POST"
                                  onsubmit="return confirm('Xóa Flash Sale này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="fs-action-btn delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:48px 20px;">
                        <div style="color:#94a3b8;">
                            <i class="fas fa-bolt" style="font-size:40px; display:block; margin-bottom:12px;"></i>
                            <h5 style="font-weight:600;">Chưa có Flash Sale nào</h5>
                            <p style="font-size:14px; margin-bottom:16px;">Tạo ngay một Flash Sale đầu tiên để thu hút khách hàng!</p>
                            <button class="btn-add-fs" onclick="openModal()">
                                <i class="fas fa-plus"></i> Thêm Flash Sale
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="fs-modal-overlay" id="fsModal">
    <div class="fs-modal">
        <div class="fs-modal-title">
            <i class="fas fa-bolt"></i> Thêm Flash Sale
        </div>
        <form action="{{ route('admin.flash-sales.store') }}" method="POST">
            @csrf
            <div class="fs-form-group">
                <label class="fs-label">Sản phẩm</label>
                <select name="product_id" class="fs-input @error('product_id') is-invalid @enderror" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ number_format($product->price) }}đ)</option>
                    @endforeach
                </select>
                @error('product_id') <span class="fs-error">{{ $message }}</span> @enderror
            </div>
            <div class="fs-form-group">
                <label class="fs-label">Giá Flash Sale (VNĐ)</label>
                <input type="number" name="sale_price" class="fs-input @error('sale_price') is-invalid @enderror" placeholder="Vd: 99000" min="1" value="{{ old('sale_price') }}" required>
                @error('sale_price') <span class="fs-error">{{ $message }}</span> @enderror
            </div>
            
            {{-- Đã đổi sang display: flex để fix lỗi tràn viền --}}
            <div style="display:flex; gap:16px;">
                <div class="fs-form-group" style="margin-bottom: 0; flex: 1; min-width: 0;">
                    <label class="fs-label">Bắt đầu</label>
                    <input type="datetime-local" name="starts_at" class="fs-input @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}" required>
                    @error('starts_at') <span class="fs-error">{{ $message }}</span> @enderror
                </div>
                <div class="fs-form-group" style="margin-bottom: 0; flex: 1; min-width: 0;">
                    <label class="fs-label">Kết thúc</label>
                    <input type="datetime-local" name="ends_at" class="fs-input @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}" required>
                    @error('ends_at') <span class="fs-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="fs-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal()">Hủy</button>
                <button type="submit" class="btn-modal-save"><i class="fas fa-save"></i> Lưu Flash Sale</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal()  { document.getElementById('fsModal').classList.add('show'); }
function closeModal() { document.getElementById('fsModal').classList.remove('show'); }
// Close when clicking outside modal box
document.getElementById('fsModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Auto-open modal if there are validation errors (form submitted)
@if($errors->any())
openModal();
@endif
</script>
@endpush