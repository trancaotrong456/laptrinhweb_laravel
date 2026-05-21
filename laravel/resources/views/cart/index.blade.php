@extends('layout')

@section('title', 'Gio hang - Sieu thi Mini')

@section('content')
<section class="py-4 bg-light border-top border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-shopping-cart text-primary me-2"></i>
            Gio hang cua ban
        </h2>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Tiep tuc mua sam
        </a>
    </div>
</section>

<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($couponHint)
        <div class="alert alert-warning">{{ $couponHint }}</div>
    @endif

    @if(session()->has('cart_last_removed'))
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <span>Ban vua xoa 1 san pham khoi gio hang.</span>
            <a href="{{ route('cart.undoRemove') }}" class="btn btn-sm btn-dark">Hoan tac</a>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(empty($cart) || count($cart) === 0)
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="120" class="mb-3" alt="Empty cart">
            <h4 class="text-muted">Gio hang cua ban dang trong</h4>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Mua sam ngay</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width:48px;">
                                        <input id="selectAllItems" type="checkbox" class="form-check-input" checked>
                                    </th>
                                    <th>San pham</th>
                                    <th class="text-center">Gia</th>
                                    <th class="text-center">So luong</th>
                                    <th class="text-end">Thanh tien</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    @php
                                        $price = (float) ($item['price'] ?? 0);
                                        $qty = (int) ($item['quantity'] ?? 1);
                                        $lineTotal = $price * $qty;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input
                                                type="checkbox"
                                                class="form-check-input checkout-item-checkbox"
                                                value="{{ $id }}"
                                                data-total="{{ $lineTotal }}"
                                                checked
                                            >
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if(!empty($item['image']))
                                                    <img src="{{ asset('images/' . $item['image']) }}" width="64" height="64" class="rounded" style="object-fit:cover;" alt="{{ $item['name'] }}">
                                                @else
                                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width:64px; height:64px;">N/A</div>
                                                @endif
                                                <strong>{{ $item['name'] }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-center text-primary fw-bold">{{ number_format($price) }} �</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('cart.update') }}" class="d-flex justify-content-center gap-1">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $id }}">
                                                <input type="number" name="quantity" value="{{ $qty }}" min="1" class="form-control text-center" style="width:76px;">
                                                <button class="btn btn-sm btn-outline-success" title="Cap nhat so luong">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($lineTotal) }} �</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('cart.remove', $id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-link text-danger p-0" title="Xoa san pham">
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Tong quan don hang</h5>

                        <div class="mb-3">
                            <form method="POST" action="{{ route('cart.coupon.apply') }}" class="d-flex gap-2">
                                @csrf
                                <input
                                    type="text"
                                    name="coupon_code"
                                    class="form-control"
                                    placeholder="Nhap ma giam gia"
                                    value="{{ old('coupon_code', $appliedCouponCode) }}"
                                >
                                <button class="btn btn-outline-primary" type="submit">Ap dung</button>
                            </form>

                            @if($appliedCouponCode)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-success">Dang dung ma: <strong>{{ $appliedCouponCode }}</strong></small>
                                    <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                        @csrf
                                        <button class="btn btn-link text-danger p-0">Bo ma</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Da chon (<span id="selectedItemsCount">{{ count($cart) }}</span> sp):</span>
                            <span id="selectedSubtotal" class="fw-bold">{{ number_format($total) }} �</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Giam gia:</span>
                            <span id="selectedDiscount" class="text-success">-{{ number_format($couponDiscount) }} �</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Phi van chuyen:</span>
                            <span id="selectedShipping" class="text-primary">0 �</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Tong thanh toan:</span>
                            <span id="selectedGrandTotal" class="fw-bold text-danger">{{ number_format(max(0, $total - $couponDiscount)) }} �</span>
                        </div>

                        <div
                            id="pricingMeta"
                            data-shipping-fee="{{ $shippingFee }}"
                            data-free-shipping-threshold="{{ $freeShippingThreshold }}"
                            data-coupon-code="{{ $appliedCouponCode ?? '' }}"
                            data-coupon-type="{{ data_get($couponMeta, 'type', '') }}"
                            data-coupon-value="{{ data_get($couponMeta, 'value', 0) }}"
                            data-coupon-max="{{ data_get($couponMeta, 'max_discount', '') }}"
                            data-coupon-min="{{ data_get($couponMeta, 'min_order_value', 0) }}"
                        ></div>

                        <button type="button" class="btn btn-success btn-lg w-100 mb-2" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="fas fa-credit-card me-2"></i> Xac nhan thanh toan
                        </button>

                        <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger w-100" onclick="return confirm('Ban co chac muon xoa toan bo gio hang?')">
                            Xoa toan bo gio hang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="fas fa-credit-card me-2"></i> Chon hinh thuc thanh toan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    <div id="selectedItemsHolder"></div>
                    <input type="hidden" id="checkoutCouponCode" name="coupon_code" value="{{ $appliedCouponCode ?? '' }}">

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                        <label class="form-check-label" for="cod">COD - Thanh toan khi nhan hang</label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                        <label class="form-check-label" for="bank">Chuyen khoan ngan hang</label>
                    </div>

                    <label for="notes" class="form-label">Ghi chu don hang:</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Vi du: giao gio hanh chinh...">{{ old('notes') }}</textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huy</button>
                <button type="submit" form="checkoutForm" class="btn btn-success">Xac nhan don hang</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllItems');
    const selectedCountEl = document.getElementById('selectedItemsCount');
    const selectedSubtotalEl = document.getElementById('selectedSubtotal');
    const selectedDiscountEl = document.getElementById('selectedDiscount');
    const selectedShippingEl = document.getElementById('selectedShipping');
    const selectedGrandTotalEl = document.getElementById('selectedGrandTotal');
    const checkoutForm = document.getElementById('checkoutForm');
    const selectedItemsHolder = document.getElementById('selectedItemsHolder');
    const checkoutCouponCode = document.getElementById('checkoutCouponCode');
    const pricingMeta = document.getElementById('pricingMeta');

    const itemCheckboxes = () => Array.from(document.querySelectorAll('.checkout-item-checkbox'));

    function formatMoney(value) {
        return `${Math.round(value).toLocaleString('vi-VN')} �`;
    }

    function calculateDiscount(subtotal) {
        if (!pricingMeta) return 0;

        const couponType = pricingMeta.dataset.couponType || '';
        const couponValue = parseFloat(pricingMeta.dataset.couponValue || '0');
        const couponMaxRaw = pricingMeta.dataset.couponMax;
        const couponMin = parseFloat(pricingMeta.dataset.couponMin || '0');

        if (!couponType || subtotal <= 0) return 0;
        if (couponMin > 0 && subtotal < couponMin) return 0;

        let discount = 0;

        if (couponType === 'percent') {
            discount = subtotal * (couponValue / 100);
        } else {
            discount = couponValue;
        }

        if (couponMaxRaw !== '' && couponMaxRaw !== null && couponMaxRaw !== undefined) {
            const couponMax = parseFloat(couponMaxRaw);
            if (!Number.isNaN(couponMax)) {
                discount = Math.min(discount, couponMax);
            }
        }

        return Math.min(discount, subtotal);
    }

    function calculateShipping(subtotalAfterDiscount) {
        if (!pricingMeta) return 0;

        const shippingFee = parseFloat(pricingMeta.dataset.shippingFee || '0');
        const freeThreshold = parseFloat(pricingMeta.dataset.freeShippingThreshold || '0');

        if (subtotalAfterDiscount <= 0) return 0;
        return subtotalAfterDiscount >= freeThreshold ? 0 : shippingFee;
    }

    function updateSelectAllState() {
        if (!selectAll) return;

        const items = itemCheckboxes();
        const checkedCount = items.filter(cb => cb.checked).length;

        selectAll.checked = checkedCount === items.length && items.length > 0;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < items.length;
    }

    function recalculateSelected() {
        const items = itemCheckboxes();
        const checked = items.filter(cb => cb.checked);

        const selectedCount = checked.length;
        const selectedSubtotal = checked.reduce((sum, cb) => {
            const rowTotal = parseFloat(cb.dataset.total || '0');
            return sum + rowTotal;
        }, 0);

        const discount = calculateDiscount(selectedSubtotal);
        const subtotalAfterDiscount = Math.max(0, selectedSubtotal - discount);
        const shipping = calculateShipping(subtotalAfterDiscount);
        const grandTotal = subtotalAfterDiscount + shipping;

        if (selectedCountEl) selectedCountEl.textContent = selectedCount.toString();
        if (selectedSubtotalEl) selectedSubtotalEl.textContent = formatMoney(selectedSubtotal);
        if (selectedDiscountEl) selectedDiscountEl.textContent = `-${formatMoney(discount)}`;
        if (selectedShippingEl) selectedShippingEl.textContent = formatMoney(shipping);
        if (selectedGrandTotalEl) selectedGrandTotalEl.textContent = formatMoney(grandTotal);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            itemCheckboxes().forEach(cb => {
                cb.checked = selectAll.checked;
            });
            recalculateSelected();
            updateSelectAllState();
        });
    }

    itemCheckboxes().forEach(cb => {
        cb.addEventListener('change', function () {
            recalculateSelected();
            updateSelectAllState();
        });
    });

    if (checkoutForm && selectedItemsHolder) {
        checkoutForm.addEventListener('submit', function (event) {
            selectedItemsHolder.innerHTML = '';

            const selected = itemCheckboxes().filter(cb => cb.checked);

            if (selected.length === 0) {
                event.preventDefault();
                alert('Vui long chon it nhat 1 san pham de thanh toan.');
                return;
            }

            selected.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'selected_items[]';
                hidden.value = cb.value;
                selectedItemsHolder.appendChild(hidden);
            });

            if (checkoutCouponCode && pricingMeta) {
                checkoutCouponCode.value = pricingMeta.dataset.couponCode || '';
            }
        });
    }

    recalculateSelected();
    updateSelectAllState();

    @if($errors->has('selected_items') || $errors->has('payment_method') || $errors->has('notes') || $errors->has('coupon_code'))
    const modalEl = document.getElementById('checkoutModal');
    if (modalEl && window.bootstrap) {
        new bootstrap.Modal(modalEl).show();
    }
    @endif
});
</script>
@endsection
