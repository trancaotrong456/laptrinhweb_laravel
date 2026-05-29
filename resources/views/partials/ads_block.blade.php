{{-- Advertising overlay (closeable) --}}
<div id="promoAdOverlay"
    style="position:fixed;inset:0;z-index:10000;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.55);padding:16px;">
    <div
        style="width:min(920px,100%);border-radius:20px;overflow:hidden;position:relative;background:#fff;box-shadow:0 30px 90px rgba(0,0,0,.35);">
        <button type="button" id="promoAdCloseBtn" aria-label="Đóng quảng cáo"
            style="position:absolute;top:12px;right:12px;width:40px;height:40px;border:none;border-radius:12px;background:rgba(0,0,0,.55);color:#fff;font-size:18px;cursor:pointer;">
            ✕
        </button>

        <div
            style="height:420px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#667eea,#764ba2);">
            <div style="text-align:center;padding:24px;color:#fff;">
                <div style="font-size:14px;letter-spacing:.08em;text-transform:uppercase;opacity:.95;font-weight:700;">
                    Quảng cáo
                </div>
                <div style="font-size:34px;font-weight:900;line-height:1.15;margin-top:10px;">
                    Siêu ưu đãi hôm nay!
                </div>
                <div style="opacity:.9;margin-top:12px;max-width:520px;font-size:16px;">
                    Nhấn nút X để đóng. (Overlay sẽ không hiện lại nếu bạn đã đóng trước đó.)
                </div>
                <div style="margin-top:18px;">
                    <a href="{{ route('coupons.index') }}"
                        style="display:inline-block;text-decoration:none;background:#fff;color:#4f46e5;font-weight:800;padding:12px 22px;border-radius:999px;">
                        Xem mã giảm giá
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const overlay = document.getElementById('promoAdOverlay');
    if (!overlay) return;

    // If user already closed this ad on this browser, don't show again.
    const closedKey = 'promoAdClosed_v1';
    if (localStorage.getItem(closedKey) === '1') {
        overlay.remove();
        return;
    }

    const closeBtn = document.getElementById('promoAdCloseBtn');
    const close = () => {
        localStorage.setItem(closedKey, '1');
        overlay.remove();
    };

    if (closeBtn) closeBtn.addEventListener('click', close);

    // Optional: click outside the modal area to close
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) close();
    });
})();
</script>