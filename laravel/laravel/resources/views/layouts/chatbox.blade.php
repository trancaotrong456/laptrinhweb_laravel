{{-- ============================================================
     CHATBOX AI - Siêu thị trực tuyến
     Chỉ hiển thị khi người dùng đã đăng nhập (@auth)
     ============================================================ --}}
@auth
@php
    // Lấy danh sách sản phẩm từ DB để làm ngữ cảnh cho AI (kèm id, description để tạo link)
    $chatProducts = \App\Models\Product::select('id', 'name', 'price', 'description')
        ->orderBy('name')
        ->limit(80)
        ->get();

    // Mảng JSON truyền sang JS: [{id, name, price, url, description}]
    $chatProductsJson = $chatProducts->map(function ($p) {
        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'price'       => $p->price,
            'url'         => route('products.detail', $p->id),
            'description' => \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 120),
        ];
    });

    // Chuỗi danh sách cho system prompt AI (kèm URL)
    $danhSachSanPham = '';
    foreach ($chatProducts as $p) {
        $giaFormatted = number_format($p->price, 0, ',', '.');
        $url = route('products.detail', $p->id);
        $desc = \Illuminate\Support\Str::limit(strip_tags($p->description ?? ''), 80);
        $danhSachSanPham .= "- {$p->name}: {$giaFormatted}đ | Link: {$url}" . ($desc ? " | Mô tả: {$desc}" : '') . "\n";
    }
    if (empty(trim($danhSachSanPham))) {
        $danhSachSanPham = 'Hiện tại shop đang cập nhật sản phẩm mới.';
    }

    $tenKhachHang = Auth::user()->name;
@endphp

{{-- ── NÚT MỞ CHAT ─────────────────────────────────────────── --}}
<button id="chatToggleBtn" aria-label="Mở chat hỗ trợ">
    <i class="fas fa-comment-dots"></i>
    <span class="chat-toggle-pulse"></span>
</button>

{{-- ── HỘP CHAT CHÍNH ──────────────────────────────────────── --}}
<div id="chatBox" role="dialog" aria-label="Hộp chat tư vấn" aria-hidden="true">

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-header-info">
            <div class="chat-avatar-wrap">
                <i class="fas fa-leaf"></i>
            </div>
            <div>
                <div class="chat-shop-name">Siêu thị trực tuyến</div>
                <div class="chat-status">
                    <span class="chat-dot"></span> Xin chào, <strong>{{ $tenKhachHang }}</strong>!
                </div>
            </div>
        </div>
        <button id="closeChatBtn" aria-label="Đóng chat" class="chat-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Vùng tin nhắn --}}
    <div id="chatMessages" class="chat-messages">
        <div class="chat-msg-row bot">
            <div class="chat-bubble-avatar"><i class="fas fa-leaf"></i></div>
            <div class="chat-bubble bot">
                Chào <strong>{{ $tenKhachHang }}</strong>! 👋 Bạn cần tư vấn sản phẩm nào của shop ạ?
            </div>
        </div>
    </div>

    {{-- Input gửi tin --}}
    <div class="chat-footer">
        <input type="text" id="chatInput" placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="500">
        <button id="sendMessageBtn" aria-label="Gửi tin nhắn">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

{{-- ── CSS ─────────────────────────────────────────────────── --}}
<style>
/* ── Toggle Button ── */
#chatToggleBtn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2e7d32 0%, #43a047 100%);
    color: #fff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 6px 24px rgba(46,125,50,.45);
    z-index: 9999;
    transition: transform .25s cubic-bezier(.34,1.56,.64,1), opacity .25s;
    position: fixed;
}
#chatToggleBtn:hover { transform: scale(1.1); }
#chatToggleBtn.hidden-btn { transform: scale(0); opacity: 0; pointer-events: none; }

/* Pulse ring */
.chat-toggle-pulse {
    position: absolute;
    top: 0; left: 0;
    width: 58px; height: 58px;
    border-radius: 50%;
    background: rgba(46,125,50,.35);
    animation: chatPulse 2s ease-out infinite;
}
@keyframes chatPulse {
    0%   { transform: scale(1);   opacity: .6; }
    70%  { transform: scale(1.6); opacity: 0;  }
    100% { transform: scale(1.6); opacity: 0;  }
}

/* ── Chat Box ── */
#chatBox {
    position: fixed;
    bottom: 100px;
    right: 28px;
    width: min(370px, calc(100vw - 32px));
    height: min(520px, calc(100vh - 120px));
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,.18), 0 4px 16px rgba(0,0,0,.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 9998;
    border: 1px solid rgba(46,125,50,.15);
    transform: translateY(20px) scale(.96);
    opacity: 0;
    pointer-events: none;
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s;
}
#chatBox.chat-open {
    transform: translateY(0) scale(1);
    opacity: 1;
    pointer-events: all;
}

/* Header */
.chat-header {
    background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%);
    color: #fff;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.chat-header-info { display: flex; align-items: center; gap: 10px; }
.chat-avatar-wrap {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    border: 2px solid rgba(255,255,255,.35);
    flex-shrink: 0;
}
.chat-shop-name { font-weight: 700; font-size: 14px; line-height: 1.2; }
.chat-status { font-size: 11.5px; color: rgba(255,255,255,.82); display: flex; align-items: center; gap: 5px; margin-top: 2px; }
.chat-dot {
    display: inline-block;
    width: 7px; height: 7px;
    background: #69f0ae;
    border-radius: 50%;
    animation: dotBlink 1.6s ease-in-out infinite;
}
@keyframes dotBlink { 0%,100%{opacity:1;} 50%{opacity:.3;} }
.chat-close-btn {
    background: rgba(255,255,255,.15);
    border: none; color: #fff;
    width: 30px; height: 30px;
    border-radius: 50%;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    transition: background .2s;
}
.chat-close-btn:hover { background: rgba(255,255,255,.3); }

/* Messages area */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px 14px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #f8fdf8;
    scroll-behavior: smooth;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: #c8e6c9; border-radius: 4px; }

/* Message rows */
.chat-msg-row { display: flex; align-items: flex-end; gap: 8px; }
.chat-msg-row.bot  { justify-content: flex-start; }
.chat-msg-row.user { justify-content: flex-end; }

.chat-bubble-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg,#2e7d32,#66bb6a);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
    margin-bottom: 2px;
}

/* Bubbles */
.chat-bubble {
    max-width: 76%;
    padding: 10px 14px;
    border-radius: 18px;
    font-size: 13.5px;
    line-height: 1.55;
    word-break: break-word;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}
.chat-bubble.bot {
    background: #fff;
    color: #1a1a1a;
    border: 1px solid #e8f5e9;
    border-bottom-left-radius: 6px;
}
.chat-bubble.user {
    background: linear-gradient(135deg,#2e7d32,#43a047);
    color: #fff;
    border-bottom-right-radius: 6px;
}

/* Typing indicator */
.chat-typing {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 10px 14px;
    background: #fff;
    border: 1px solid #e8f5e9;
    border-radius: 18px;
    border-bottom-left-radius: 6px;
    width: fit-content;
}
.chat-typing span {
    width: 7px; height: 7px;
    background: #66bb6a;
    border-radius: 50%;
    animation: typingBounce .9s ease-in-out infinite;
}
.chat-typing span:nth-child(2) { animation-delay: .15s; }
.chat-typing span:nth-child(3) { animation-delay: .30s; }
@keyframes typingBounce { 0%,60%,100%{transform:translateY(0);} 30%{transform:translateY(-6px);} }

/* Footer / Input */
.chat-footer {
    padding: 10px 12px;
    background: #fff;
    border-top: 1px solid #e8f5e9;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
#chatInput {
    flex: 1;
    border: 1.5px solid #c8e6c9;
    border-radius: 24px;
    padding: 9px 16px;
    font-size: 13.5px;
    outline: none;
    background: #f8fdf8;
    transition: border-color .2s, background .2s;
    font-family: inherit;
    color: #1a1a1a;
}
#chatInput:focus {
    border-color: #2e7d32;
    background: #fff;
}
#chatInput::placeholder { color: #aaa; }
#sendMessageBtn {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg,#2e7d32,#43a047);
    color: #fff;
    border: none;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 3px 10px rgba(46,125,50,.35);
}
#sendMessageBtn:hover { transform: scale(1.08); box-shadow: 0 5px 14px rgba(46,125,50,.45); }
#sendMessageBtn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

/* Links inside bot bubble */
.chat-bubble.bot .chat-link {
    color: #1b5e20;
    font-weight: 600;
    text-decoration: none;
    background: #e8f5e9;
    padding: 3px 9px;
    border-radius: 20px;
    border: 1px solid #a5d6a7;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
    transition: background .2s, color .2s;
    font-size: 12.5px;
}
.chat-bubble.bot .chat-link:hover {
    background: #2e7d32;
    color: #fff;
    border-color: #2e7d32;
}
</style>

{{-- ── JAVASCRIPT ───────────────────────────────────────────── --}}
<script>
(function() {
    // ─── Config ───────────────────────────────────────────────
    const API_KEY = '{!! config('services.gemini.key') !!}';
    const GEMINI_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${API_KEY}`;

    // Dữ liệu sản phẩm được nhúng từ PHP
    const danhSachSanPham = {!! json_encode($danhSachSanPham) !!};
    const chatProducts    = {!! json_encode($chatProductsJson) !!}; // [{id,name,price,url,description}]
    const tenKhach = {!! json_encode($tenKhachHang) !!};

    const systemPrompt = `Bạn là nhân viên tư vấn nhiệt tình, thân thiện của Siêu thị trực tuyến. Tên khách hàng là: ${tenKhach}.
Xưng hô là "Shop" và "Bạn" (hoặc gọi tên khách).

SẢN PHẨM HIỆN CÓ CỦA SHOP (bao gồm tên, giá, link trang chi tiết, mô tả ngắn):
${danhSachSanPham}

CHÍNH SÁCH SHOP:
- Ship đồng giá 30.000đ, freeship cho đơn từ 300.000đ.
- Đổi trả trong 7 ngày kể từ ngày nhận hàng.

LƯU Ý QUAN TRỌNG (PHẢI TUÂN THỦ NGHIÊM NGẶT):
- Trả lời thân thiện, dễ thương, dùng emoji vừa phải.
- Tuyệt đối KHÔNG bịa ra sản phẩm không có trong danh sách trên.
- Nếu khách hỏi sản phẩm không có, hãy xin lỗi lịch sự và gợi ý sản phẩm tương tự đang có.
- CHỈ tư vấn đúng sản phẩm khách đang hỏi.
- Về giá: Luôn định dạng số kiểu 1.000đ, 150.000đ,...
- KHI KHÁCH HỎI VỀ MỘT SẢN PHẨM CỤ THỂ: hãy cung cấp thông tin chi tiết (tên, giá, mô tả) VÀ LUÔN LUÔN đính kèm link trang chi tiết sản phẩm đó theo định dạng markdown: [Xem chi tiết sản phẩm tại đây](URL_sản_phẩm)
- Ví dụ kết thúc mỗi câu trả lời về sản phẩm: "🔗 [Xem chi tiết & mua ngay](https://...)"`;

    // ─── DOM refs ─────────────────────────────────────────────
    const toggleBtn   = document.getElementById('chatToggleBtn');
    const chatBox     = document.getElementById('chatBox');
    const closeBtn    = document.getElementById('closeChatBtn');
    const messagesEl  = document.getElementById('chatMessages');
    const inputEl     = document.getElementById('chatInput');
    const sendBtn     = document.getElementById('sendMessageBtn');

    // ─── Conversation history (cho multi-turn) ────────────────
    const conversationHistory = [];

    // ─── Open / Close ─────────────────────────────────────────
    function openChat() {
        chatBox.classList.add('chat-open');
        chatBox.setAttribute('aria-hidden', 'false');
        toggleBtn.classList.add('hidden-btn');
        setTimeout(() => inputEl.focus(), 350);
    }
    function closeChat() {
        chatBox.classList.remove('chat-open');
        chatBox.setAttribute('aria-hidden', 'true');
        toggleBtn.classList.remove('hidden-btn');
    }

    toggleBtn.addEventListener('click', openChat);
    closeBtn.addEventListener('click', closeChat);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeChat(); });

    // ─── Render helpers ───────────────────────────────────────
    function appendBotBubble(html, isError = false) {
        const row = document.createElement('div');
        row.className = 'chat-msg-row bot';
        row.innerHTML = `
            <div class="chat-bubble-avatar"><i class="fas fa-leaf"></i></div>
            <div class="chat-bubble bot${isError ? ' chat-bubble-error' : ''}">${html}</div>
        `;
        messagesEl.appendChild(row);
        scrollBottom();
        return row;
    }

    function appendUserBubble(text) {
        const row = document.createElement('div');
        row.className = 'chat-msg-row user';
        row.innerHTML = `<div class="chat-bubble user">${escapeHtml(text)}</div>`;
        messagesEl.appendChild(row);
        scrollBottom();
    }

    function showTyping() {
        const row = document.createElement('div');
        row.className = 'chat-msg-row bot';
        row.id = 'chat-typing-row';
        row.innerHTML = `
            <div class="chat-bubble-avatar"><i class="fas fa-leaf"></i></div>
            <div class="chat-typing"><span></span><span></span><span></span></div>
        `;
        messagesEl.appendChild(row);
        scrollBottom();
    }
    function removeTyping() {
        const el = document.getElementById('chat-typing-row');
        if (el) el.remove();
    }

    function scrollBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function escapeHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Convert markdown → HTML (bold, link, newline)
    function formatBotText(text) {
        // 1. Escape HTML trước
        let out = text
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;');
        // 2. Render markdown link [label](url)  →  <a href="url">label</a>
        out = out.replace(
            /\[([^\]]+)\]\((https?:\/\/[^)]+)\)/g,
            '<a href="$2" target="_blank" rel="noopener" class="chat-link">$1 <i class="fas fa-external-link-alt" style="font-size:10px;"></i></a>'
        );
        // 3. **bold**
        out = out.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // 4. *italic*
        out = out.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // 5. Newline → <br>
        out = out.replace(/\n/g, '<br>');
        return out;
    }

    // ─── Send Message ─────────────────────────────────────────
    async function sendMessage() {
        const text = inputEl.value.trim();
        if (!text) return;

        appendUserBubble(text);
        inputEl.value = '';
        sendBtn.disabled = true;

        // Lưu vào history
        conversationHistory.push({ role: 'user', parts: [{ text }] });

        showTyping();

        try {
            const requestBody = {
                systemInstruction: { parts: [{ text: systemPrompt }] },
                contents: conversationHistory
            };

            const res = await fetch(GEMINI_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestBody)
            });

            const data = await res.json();
            removeTyping();

            let replyText = 'Xin lỗi, shop đang gặp sự cố nhỏ. Bạn nhắn lại giúp shop nhé! 🙏';

            if (data.candidates && data.candidates[0]?.content?.parts?.[0]?.text) {
                replyText = data.candidates[0].content.parts[0].text;
                // Lưu câu trả lời vào history để AI có context
                conversationHistory.push({ role: 'model', parts: [{ text: replyText }] });
            } else if (data.error) {
                replyText = '⚠️ Lỗi từ AI: ' + data.error.message;
            }

            appendBotBubble(formatBotText(replyText));

        } catch (err) {
            removeTyping();
            appendBotBubble('❌ Lỗi mạng! Bạn vui lòng kiểm tra kết nối và thử lại nhé.', true);
        }

        sendBtn.disabled = false;
        inputEl.focus();
    }

    sendBtn.addEventListener('click', sendMessage);
    inputEl.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
})();
</script>
@endauth
