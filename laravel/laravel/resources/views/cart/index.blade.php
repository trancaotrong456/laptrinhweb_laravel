@extends('layout')
@section('title', 'Giỏ hàng & Thanh toán - Siêu thị trực tuyến')
@section('content')
{{-- BREADCRUMB --}}
<div class="breadcrumb-bar">
    <div class="container">
        <a href="{{ route('home') }}">Trang chủ</a>
        <span class="sep">›</span>
        <span class="cur">Giỏ hàng & Thanh toán</span>
    </div>
</div>

<div class="container">
    <h1 class="cart-page-title mt-3">
        <i class="fas fa-shopping-cart"></i>
        Giỏ hàng & Thanh toán
    </h1>
    {{-- ALERTS --}}
    @if(session('success'))
    <div class="alert-st success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-st error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($couponHint)
    <div class="alert-st warning"><i class="fas fa-info-circle"></i> {{ $couponHint }}</div>
    @endif
    @if(session()->has('cart_last_removed'))
    <div class="alert-st warning" style="justify-content:space-between;">
        <span><i class="fas fa-info-circle"></i> Bạn vừa xóa 1 sản phẩm khỏi giỏ hàng.</span>
        <a href="{{ route('cart.undoRemove') }}" class="btn-green-outline"
            style="padding:6px 14px;font-size:12.5px;">Hoàn tác</a>
    </div>
    @endif
    @if($errors->any())
    <div class="alert-st error">
        <i class="fas fa-exclamation-circle"></i>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
        </ul>
    </div>
    @endif
    @if(empty($cart) || count($cart) === 0)
    {{-- EMPTY --}}
    <div class="empty-state" style="padding:80px 20px;">
        <i class="fas fa-cart-xmark"></i>
        <h5>Giỏ hàng của bạn đang trống</h5>
        <p style="font-size:13.5px;margin-bottom:20px;">Hãy thêm sản phẩm vào giỏ hàng để tiến hành thanh toán.</p>
        <a href="{{ route('products.all') }}" class="btn-green">
            <i class="fas fa-store"></i> Mua sắm ngay
        </a>
    </div>
    @else
    <div class="row g-4 pb-4">
        {{-- ─── LEFT: SẢN PHẨM + THÔNG TIN + THANH TOÁN ─── --}}
        <div class="col-lg-8">

            {{-- PRODUCTS --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-box-open"></i>
                    Sản phẩm trong giỏ ({{ count($cart) }})
                </div>
                @foreach($cart as $id => $item)
                @php
                $price = (float)($item['price'] ?? 0);
                $qty = (int)($item['quantity'] ?? 1);
                $lineTotal = $price * $qty;
                $itemImage = $item['image'] ?? null;
                $itemImageUrl = null;
                if ($itemImage) {
                    $itemImageUrl = \Illuminate\Support\Str::startsWith($itemImage, ['http://', 'https://'])
                        ? $itemImage
                        : (str_contains($itemImage, '/') ? asset('storage/' . $itemImage) : asset('images/' . $itemImage));
                }
                @endphp
                <div style="display:flex;align-items:center;gap:14px;padding:14px 0;
                            border-bottom:1px solid #f5f5f5;position:relative;">
                    {{-- Checkbox --}}
                    <input type="checkbox" class="checkout-item-checkbox" value="{{ $id }}"
                        data-total="{{ $lineTotal }}" checked
                        style="accent-color:#2e7d32;width:17px;height:17px;flex-shrink:0;cursor:pointer;">
                    {{-- Image --}}
                    @if($itemImageUrl)
                    <img src="{{ $itemImageUrl }}" class="cart-item-img" alt="{{ $item['name'] }}"
                        onerror="this.style.display='none'">
                    @else
                    <div
                        style="width:68px;height:68px;background:#f5f5f5;border-radius:10px;
                                display:flex;align-items:center;justify-content:center;color:#bdbdbd;font-size:20px;flex-shrink:0;">
                        <i class="fas fa-image"></i>
                    </div>
                    @endif

                    {{-- Name & price --}}
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:14px;margin-bottom:4px;
                                    overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            {{ $item['name'] }}
                        </div>
                        <div style="font-size:15px;font-weight:800;color:#e53935;">
                            {{ number_format($price) }}đ
                        </div>
                    </div>
                    {{-- Qty controls --}}
                    <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <div class="cart-qty-control">
                            <button type="button" onclick="changeCartQty(this,-1)">−</button>
                            <input type="number" name="quantity" value="{{ $qty }}" min="1"
                                onchange="this.form.submit()" style="width:44px;">
                            <button type="button" onclick="changeCartQty(this,1)">+</button>
                        </div>
                    </form>
                    {{-- Line total --}}
                    <div style="font-weight:800;font-size:14px;min-width:80px;text-align:right;">
                        {{ number_format($lineTotal) }}đ
                    </div>
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('cart.remove', $id) }}" style="flex-shrink:0;">
                        @csrf @method('DELETE')
                        <button type="submit" style="color:#bdbdbd;font-size:18px;padding:4px;" title="Xóa sản phẩm"
                            onmouseover="this.style.color='#e53935'" onmouseout="this.style.color='#bdbdbd'">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
                @endforeach
                <div
                    style="display:flex;justify-content:space-between;align-items:center;padding-top:14px;flex-wrap:wrap;gap:8px;">
                    <label
                        style="display:flex;align-items:center;gap:8px;font-size:13.5px;cursor:pointer;color:#757575;">
                        <input type="checkbox" id="selectAllItems" checked
                            style="accent-color:#2e7d32;width:16px;height:16px;"> Chọn tất cả
                    </label>
                    <a href="{{ route('cart.clear') }}"
                        onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')"
                        style="font-size:13px;color:#e53935;">
                        <i class="fas fa-trash me-1"></i> Xóa tất cả
                    </a>
                </div>
            </div>
            {{-- SHIPPING INFO --}}
            <div class="form-section" id="checkoutFormSection">
                <div class="form-section-title">
                    <i class="fas fa-truck"></i> Thông tin giao hàng
                </div>
                <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    <div id="selectedItemsHolder"></div>
                    <input type="hidden" id="checkoutCouponCode" name="coupon_code"
                        value="{{ $appliedCouponCode ?? '' }}">
                    <div class="form-group">
                        <div class="gr-row">
                            <div>
                                <label>Họ và tên <span style="color:#e53935;">*</span></label>
                                <input type="text" class="form-control-st" name="shipping_name" id="shipping_name"
                                    placeholder="Tên người nhận"
                                    value="{{ old('shipping_name', auth()->user()?->name ?? '') }}" required>
                            </div>
                            <div>
                                <label>Số điện thoại</label>
                                <input type="text" class="form-control-st" name="shipping_phone"
                                    placeholder="Ví dụ 0812xxxxxx" value="{{ old('shipping_phone') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email <span style="color:#e53935;">*</span></label>
                        <input type="email" class="form-control-st" name="shipping_email" required
                            value="{{ old('shipping_email', auth()->user()?->email ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ giao hàng <span style="color:#e53935;">*</span></label>
                        <input type="text" class="form-control-st" name="shipping_address" required
                            placeholder="Số nhà, tên đường, phường/xã..."
                            value="{{ old('shipping_address', auth()->user()?->address ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú đơn hàng</label>
                        <textarea name="shipping_notes" class="form-control-st" rows="3"
                            placeholder="Yêu cầu riêng về giao hàng...">{{ old('shipping_notes') }}</textarea>
                    </div>
                    {{-- PAYMENT METHOD --}}
                    <div class="form-section-title mt-2">
                        <i class="fas fa-credit-card"></i> Phương thức thanh toán
                    </div>
                    <div class="pay-methods">
                        <label class="pay-method-card active" id="pm-cod" onclick="selectPay('cod')">
                            <input type="radio" name="payment_method" value="cod" style="display:none;" checked>
                            <i class="fas fa-money-bill-wave"></i>
                            Khi nhận hàng (COD)
                        </label>
                        <label class="pay-method-card" id="pm-bank" onclick="selectPay('bank')">
                            <input type="radio" name="payment_method" value="bank" style="display:none;">
                            <i class="fas fa-university"></i>
                            Thẻ tín dụng/ATM
                        </label>
                        <label class="pay-method-card" id="pm-wallet" onclick="selectPay('wallet')">
                            <input type="radio" name="payment_method" value="wallet" style="display:none;">
                            <i class="fas fa-wallet"></i>
                            Ví MoMo/ZaloPay
                        </label>
                    </div>
                </form>
            </div>
        </div>
        {{-- ─── RIGHT: ORDER SUMMARY ─── --}}
        <div class="col-lg-4">
            <div class="order-summary">
                <h4><i class="fas fa-receipt"></i> Tóm tắt đơn hàng</h4>
                {{-- Coupon --}}
                <form method="POST" action="{{ route('cart.coupon.apply') }}">
                    @csrf
                    <div class="coupon-input-row">
                        <input type="text" name="coupon_code" placeholder="Mã giảm giá (Voucher)"
                            value="{{ old('coupon_code', $appliedCouponCode) }}">
                        <button type="submit" class="apply-btn">Áp dụng</button>
                    </div>
                </form>
                @if($appliedCouponCode)
                <div style="display:flex;justify-content:space-between;align-items:center;
                            background:#e8f5e9;border-radius:8px;padding:8px 12px;margin-bottom:12px;font-size:13px;">
                    <span style="color:#2e7d32;">🎉 Đang dùng: <strong>{{ $appliedCouponCode }}</strong></span>
                    <form method="POST" action="{{ route('cart.coupon.remove') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="color:#e53935;font-size:12px;font-weight:600;">Bỏ</button>
                    </form>
                </div>
                @endif
                {{-- Pricing --}}
                <div id="pricingMeta" data-shipping-fee="{{ $shippingFee }}"
                    data-free-shipping-threshold="{{ $freeShippingThreshold }}"
                    data-coupon-code="{{ $appliedCouponCode ?? '' }}"
                    data-coupon-type="{{ data_get($couponMeta,'type','') }}"
                    data-coupon-value="{{ data_get($couponMeta,'value',0) }}"
                    data-coupon-max="{{ data_get($couponMeta,'max_discount','') }}"
                    data-coupon-min="{{ data_get($couponMeta,'min_order_value',0) }}">
                </div>
                <div class="sum-row">
                    <span>Tạm tính (<span id="selectedItemsCount">{{ count($cart) }}</span> sản phẩm)</span>
                    <span id="selectedSubtotal">{{ number_format($total) }} đ</span>
                </div>
                <div class="sum-row">
                    <span>Phí vận chuyển</span>
                    <span id="selectedShipping" style="color:#1976d2;">0 đ</span>
                </div>
                <div class="sum-row">
                    <span>Giảm giá</span>
                    <span id="selectedDiscount" class="discount">-{{ number_format($couponDiscount) }} đ</span>
                </div>
                <div class="sum-row total">
                    <span>Tổng cộng</span>
                    <span class="sum-val" id="selectedGrandTotal">{{ number_format(max(0,$total-$couponDiscount)) }}
                        đ</span>
                </div>
                <button type="button" class="checkout-btn" onclick="submitCheckout()">
                    <i class="fas fa-check-circle"></i> Xác nhận đặt hàng →
                </button>
                <div class="guarantee-box">
                    <i class="fas fa-shield-alt"></i>
                    <div>Nhấn "Xác nhận đặt hàng" đồng nghĩa với việc bạn đồng ý với
                        <a href="#" style="color:#2e7d32;font-weight:600;">Điều khoản dịch vụ</a> của chúng tôi.
                    </div>
                </div>
                <a href="{{ route('home') }}"
                    style="display:block;text-align:center;margin-top:12px;font-size:13px;color:#9e9e9e;">
                    <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll         = document.getElementById('selectAllItems');
    const selectedCountEl   = document.getElementById('selectedItemsCount');
    const selectedSubEl     = document.getElementById('selectedSubtotal');
    const selectedDiscEl    = document.getElementById('selectedDiscount');
    const selectedShipEl    = document.getElementById('selectedShipping');
    const selectedGrandEl   = document.getElementById('selectedGrandTotal');
    const pricingMeta       = document.getElementById('pricingMeta');
    const checkoutForm      = document.getElementById('checkoutForm');
    const itemsHolder       = document.getElementById('selectedItemsHolder');
    const checkoutCouponInp = document.getElementById('checkoutCouponCode'); 
    
    const checks = () => Array.from(document.querySelectorAll('.checkout-item-checkbox')); 
    function fmt(v){ return Math.round(v).toLocaleString('vi-VN') + ' đ'; } 
    
    function calcDiscount(sub){
        if(!pricingMeta) return 0;
        const type  = pricingMeta.dataset.couponType || '';
        const val   = parseFloat(pricingMeta.dataset.couponValue || '0');
        const max   = pricingMeta.dataset.couponMax;
        const min   = parseFloat(pricingMeta.dataset.couponMin || '0');
        if(!type||sub<=0) return 0;
        if(min>0&&sub<min) return 0;
        let d = type==='percent' ? sub*(val/100) : val;
        // ĐÃ SỬA: Thay !isnan thành !isNaN ở dòng dưới
        if(max!==''&&max!==null&&max!==undefined){ const m=parseFloat(max); if(!isNaN(m)) d=Math.min(d,m); }
        return Math.min(d,sub);
    } 
    function calcShipping(subAfterDiscount){
        if(!pricingMeta) return 0;
        const fee   = parseFloat(pricingMeta.dataset.shippingFee||'0');
        const thres = parseFloat(pricingMeta.dataset.freeShippingThreshold||'0');
        if(subAfterDiscount<=0) return 0;
        return subAfterDiscount>=thres ? 0 : fee;
    }
    function recalc(){
        const checked = checks().filter(c=>c.checked);
        const count   = checked.length;
        const sub     = checked.reduce((s,c)=>s+parseFloat(c.dataset.total||'0'),0);
        const disc    = calcDiscount(sub);
        const subAfter= Math.max(0,sub-disc);
        const ship    = calcShipping(subAfter);
        const grand   = subAfter + ship;
        
        if(selectedCountEl) selectedCountEl.textContent = count;
        if(selectedSubEl)   selectedSubEl.textContent   = fmt(sub);
        if(selectedDiscEl)  selectedDiscEl.textContent  = '-'+fmt(disc);
        if(selectedShipEl)  selectedShipEl.textContent  = fmt(ship);
        if(selectedGrandEl) selectedGrandEl.textContent = fmt(grand);
    } 
    function updateSelectAll(){
        if(!selectAll) return;
        const c = checks(); const n = c.filter(x=>x.checked).length;
        selectAll.checked       = n===c.length&&c.length>0;
        selectAll.indeterminate = n>0&&n<c.length;
    } 
    if(selectAll){
        selectAll.addEventListener('change',()=>{
            checks().forEach(c=>c.checked=selectAll.checked);
            recalc(); updateSelectAll();
        });
    }
    checks().forEach(c=>c.addEventListener('change',()=>{ recalc(); updateSelectAll(); }));
    recalc(); updateSelectAll(); 
    
    // Payment method cards
    window.selectPay = function(v){
        document.querySelectorAll('.pay-method-card').forEach(el=>el.classList.remove('active'));
        document.getElementById('pm-'+v).classList.add('active');
        document.querySelector('.pay-method-card[id="pm-'+v+'"] input').checked=true;
    }; 
    // Cart qty
    window.changeCartQty = function(btn, d){
        const wrap = btn.parentElement;
        const inp  = wrap.querySelector('input[type="number"]');
        if(!inp) return;
        let v = parseInt(inp.value)||1;
        v = Math.max(1, v+d);
        inp.value = v;
        inp.form.submit();
    }; 
    // Submit
    window.submitCheckout = function(){
        if(!checkoutForm||!itemsHolder) return;
        itemsHolder.innerHTML='';
        const selected = checks().filter(c=>c.checked);
        if(selected.length===0){ alert('Vui lòng chọn ít nhất 1 sản phẩm.'); return; }
        selected.forEach(c=>{
            const inp=document.createElement('input');
            inp.type='hidden'; inp.name='selected_items[]'; inp.value=c.value;
            itemsHolder.appendChild(inp);
        });
        if(checkoutCouponInp&&pricingMeta) checkoutCouponInp.value=pricingMeta.dataset.couponCode||'';
        checkoutForm.submit();
    };
    @if($errors->has('selected_items') || $errors->has('payment_method') || $errors->has('shipping_name') || $errors->has('shipping_address'))
    document.getElementById('checkoutFormSection')?.scrollIntoView({behavior:'smooth'});
    @endif
});
</script>
@endsection
