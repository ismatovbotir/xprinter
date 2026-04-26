{{-- Confirm modal — included in admin + marketplace layouts --}}
<div id="x-confirm" aria-modal="true" role="dialog" style="display:none;position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;padding:20px">
    <div id="x-confirm-backdrop" style="position:absolute;inset:0;background:rgba(10,27,61,0.45);backdrop-filter:blur(4px)"></div>
    <div style="position:relative;background:#fff;border-radius:20px;width:100%;max-width:400px;box-shadow:0 24px 64px rgba(10,27,61,0.18);overflow:hidden">

        {{-- Icon --}}
        <div style="display:flex;justify-content:center;padding:32px 32px 0">
            <div style="width:60px;height:60px;border-radius:18px;background:#FFF1F2;display:flex;align-items:center;justify-content:center">
                <svg viewBox="0 0 24 24" style="width:30px;height:30px;stroke:#9F1239;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round">
                    <path d="M3 6h18M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6M9 6V4h6v2"/>
                </svg>
            </div>
        </div>

        {{-- Text --}}
        <div style="padding:20px 32px 28px;text-align:center">
            <div style="font-family:'Unbounded',sans-serif;font-size:15px;font-weight:600;color:var(--ink);margin-bottom:10px">
                Tasdiqlash
            </div>
            <div id="x-confirm-msg" style="font-size:13.5px;color:var(--muted);line-height:1.6"></div>
        </div>

        {{-- Actions --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--line)">
            <button id="x-confirm-cancel"
                    style="padding:16px;font-family:'Manrope',sans-serif;font-size:13px;font-weight:600;color:var(--muted);background:none;border:none;border-right:1px solid var(--line);cursor:pointer;transition:background .12s"
                    onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='none'">
                Bekor qilish
            </button>
            <button id="x-confirm-ok"
                    style="padding:16px;font-family:'Manrope',sans-serif;font-size:13px;font-weight:600;color:#9F1239;background:none;border:none;cursor:pointer;transition:background .12s"
                    onmouseover="this.style.background='#FFF1F2'" onmouseout="this.style.background='none'">
                O'chirish
            </button>
        </div>

    </div>
</div>

<script>
(function () {
    var modal      = document.getElementById('x-confirm');
    var msgEl      = document.getElementById('x-confirm-msg');
    var btnOk      = document.getElementById('x-confirm-ok');
    var btnCancel  = document.getElementById('x-confirm-cancel');
    var backdrop   = document.getElementById('x-confirm-backdrop');
    var pending    = null;

    function open(form) {
        pending = form;
        msgEl.textContent = form.dataset.confirm;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function close() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        pending = null;
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.dataset.confirm) return;
        e.preventDefault();
        open(form);
    });

    btnOk.addEventListener('click', function () {
        if (!pending) return;
        var form = pending;
        delete form.dataset.confirm;
        close();
        form.submit();
    });

    btnCancel.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') close();
    });
})();
</script>
