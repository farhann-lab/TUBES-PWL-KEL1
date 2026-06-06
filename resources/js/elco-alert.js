/* ─── ELCO ALERT SYSTEM — Glassmorphism Dark ─────────────────────────────── */

(function () {

  /* ── CSS yang diinjeksi sekali ke <head> ── */
  const STYLE = `
    .elco-overlay {
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(5, 3, 2, 0.72);
      backdrop-filter: blur(10px) saturate(140%);
      -webkit-backdrop-filter: blur(10px) saturate(140%);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.28s ease;
    }
    .elco-overlay.elco-show {
      opacity: 1;
      pointer-events: auto;
    }
    .elco-modal {
      position: relative;
      width: min(420px, calc(100vw - 40px));
      border-radius: 26px;
      padding: 36px 32px 28px;
      text-align: center;
      background:
        linear-gradient(135deg, rgba(255, 246, 235, 0.13), rgba(255, 246, 235, 0.05)),
        radial-gradient(circle at 20% 20%, rgba(45, 212, 191, 0.12), transparent 42%),
        radial-gradient(circle at 80% 75%, rgba(240, 181, 109, 0.18), transparent 46%),
        linear-gradient(135deg, rgba(18, 12, 10, 0.95), rgba(58, 35, 24, 0.91));
      border: 1px solid rgba(255, 238, 220, 0.18);
      box-shadow:
        0 32px 72px rgba(0, 0, 0, 0.58),
        inset 0 1px 0 rgba(255, 255, 255, 0.13),
        inset 0 -1px 0 rgba(0, 0, 0, 0.22);
      backdrop-filter: blur(32px) saturate(160%);
      -webkit-backdrop-filter: blur(32px) saturate(160%);
      transform: scale(0.94) translateY(10px);
      transition: transform 0.32s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .elco-overlay.elco-show .elco-modal {
      transform: scale(1) translateY(0);
    }
    .elco-modal-close {
      position: absolute;
      top: 14px;
      right: 16px;
      width: 30px;
      height: 30px;
      border-radius: 999px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 246, 235, 0.08);
      border: 1px solid rgba(255, 238, 220, 0.14);
      color: rgba(255, 246, 235, 0.44);
      font-size: 15px;
      cursor: pointer;
      transition: background 0.18s, color 0.18s;
    }
    .elco-modal-close:hover {
      background: rgba(255, 123, 110, 0.18);
      color: #ff7b6e;
    }
    .elco-modal-icon-ring {
      width: 64px;
      height: 64px;
      border-radius: 999px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      font-size: 26px;
      line-height: 1;
    }
    .elco-modal-icon-ring.warn {
      background: rgba(240, 181, 109, 0.18);
      border: 1.5px solid rgba(240, 181, 109, 0.32);
      color: #f0b56d;
    }
    .elco-modal-icon-ring.success {
      background: rgba(45, 212, 191, 0.16);
      border: 1.5px solid rgba(45, 212, 191, 0.30);
      color: #2dd4bf;
    }
    .elco-modal-icon-ring.error {
      background: rgba(255, 123, 110, 0.16);
      border: 1.5px solid rgba(255, 123, 110, 0.28);
      color: #ff7b6e;
    }
    .elco-modal-divider {
      width: 38px;
      height: 2px;
      border-radius: 999px;
      background: linear-gradient(90deg, #2dd4bf, #f0b56d);
      margin: 0 auto 18px;
    }
    .elco-modal-title {
      margin: 0 0 8px;
      font-size: 18px;
      font-weight: 700;
      color: #fffdf8;
      line-height: 1.2;
      letter-spacing: 0;
    }
    .elco-modal-text {
      margin: 0 0 26px;
      font-size: 13.5px;
      color: rgba(255, 246, 235, 0.72);
      line-height: 1.65;
      letter-spacing: 0;
    }
    .elco-modal-actions {
      display: flex;
      gap: 10px;
    }
    .elco-modal-btn {
      flex: 1;
      min-height: 44px;
      border-radius: 999px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      border: 1px solid transparent;
      transition: filter 0.2s ease, transform 0.2s ease, background 0.2s ease;
      letter-spacing: 0;
    }
    .elco-modal-btn.elco-btn-cancel {
      background: rgba(255, 246, 235, 0.09);
      color: rgba(255, 246, 235, 0.72);
      border-color: rgba(255, 238, 220, 0.18);
    }
    .elco-modal-btn.elco-btn-cancel:hover {
      background: rgba(255, 246, 235, 0.16);
      color: #fffdf8;
    }
    .elco-modal-btn.elco-btn-confirm-warn {
      background: linear-gradient(135deg, #9a5c24, #c37a3d, #f0b56d);
      color: #fff;
      border-color: rgba(255, 238, 220, 0.22);
      box-shadow: 0 8px 22px rgba(0, 0, 0, 0.24);
    }
    .elco-modal-btn.elco-btn-confirm-warn:hover {
      filter: brightness(1.1);
      transform: translateY(-1px);
    }
    .elco-modal-btn.elco-btn-confirm-success {
      background: linear-gradient(135deg, #0f766e, #14b8a6);
      color: #fff;
      border-color: rgba(45, 212, 191, 0.28);
      box-shadow: 0 8px 22px rgba(0, 0, 0, 0.24);
    }
    .elco-modal-btn.elco-btn-confirm-success:hover {
      filter: brightness(1.1);
      transform: translateY(-1px);
    }
    .elco-modal-btn.elco-btn-confirm-error {
      background: linear-gradient(135deg, #b42318, #ef6358);
      color: #fff;
      border-color: rgba(255, 123, 110, 0.28);
      box-shadow: 0 8px 22px rgba(0, 0, 0, 0.24);
    }
    .elco-modal-btn.elco-btn-confirm-error:hover {
      filter: brightness(1.1);
      transform: translateY(-1px);
    }
  `;

  /* ── Inject CSS sekali ── */
  if (!document.getElementById('elco-alert-style')) {
    const tag = document.createElement('style');
    tag.id = 'elco-alert-style';
    tag.textContent = STYLE;
    document.head.appendChild(tag);
  }

  /* ── Buat overlay DOM sekali ── */
  let overlay = document.getElementById('elco-alert-overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.id = 'elco-alert-overlay';
    overlay.className = 'elco-overlay';
    overlay.innerHTML = `
      <div class="elco-modal" role="alertdialog" aria-modal="true">
        <button class="elco-modal-close" aria-label="Tutup">✕</button>
        <div class="elco-modal-icon-ring" id="elco-icon-ring"></div>
        <div class="elco-modal-divider"></div>
        <p class="elco-modal-title" id="elco-modal-title"></p>
        <p class="elco-modal-text"  id="elco-modal-text"></p>
        <div class="elco-modal-actions" id="elco-modal-actions"></div>
      </div>`;
    document.body.appendChild(overlay);

    /* Tutup saat klik backdrop */
    overlay.addEventListener('click', e => {
      if (e.target === overlay) closeElco();
    });

    /* Tutup via tombol × */
    overlay.querySelector('.elco-modal-close').addEventListener('click', closeElco);

    /* Tutup via Escape */
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && overlay.classList.contains('elco-show')) closeElco();
    });
  }

  /* ── Helper buka / tutup ── */
  function openElco({ ringClass, iconHtml, title, text, actionsHtml }) {
    const ring    = overlay.querySelector('#elco-icon-ring');
    const titleEl = overlay.querySelector('#elco-modal-title');
    const textEl  = overlay.querySelector('#elco-modal-text');
    const actions = overlay.querySelector('#elco-modal-actions');

    ring.className    = `elco-modal-icon-ring ${ringClass}`;
    ring.innerHTML    = iconHtml;
    titleEl.textContent = title;
    textEl.textContent  = text;
    actions.innerHTML   = actionsHtml;

    overlay.classList.add('elco-show');
  }

  function closeElco() {
    overlay.classList.remove('elco-show');
  }

  /* ── Icon SVG kecil ── */
  const SVG = {
    warning: `<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
    success:  `<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
    error:    `<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
  };

  /* ─────────────────────────────────────────────────────────────
     PUBLIC API
  ───────────────────────────────────────────────────────────── */

  /** Konfirmasi dua tombol */
  window.elcoConfirm = function ({
    title       = "Yakin?",
    text        = "",
    confirmText = "Ya, Lanjutkan",
    cancelText  = "Batal",
    type        = "warning",   // "warning" | "error"
    onConfirm,
    onCancel,
  } = {}) {

    const isErr      = type === "error";
    const ringClass  = isErr ? "error" : "warn";
    const btnClass   = isErr ? "elco-btn-confirm-error" : "elco-btn-confirm-warn";

    openElco({
      ringClass,
      iconHtml: SVG[isErr ? "error" : "warning"],
      title,
      text,
      actionsHtml: `
        <button class="elco-modal-btn elco-btn-cancel" id="elco-cancel-btn">${cancelText}</button>
        <button class="elco-modal-btn ${btnClass}"     id="elco-confirm-btn">${confirmText}</button>`,
    });

    overlay.querySelector('#elco-confirm-btn').onclick = () => {
      closeElco();
      if (typeof onConfirm === 'function') onConfirm();
    };
    overlay.querySelector('#elco-cancel-btn').onclick = () => {
      closeElco();
      if (typeof onCancel === 'function') onCancel();
    };
  };

  /** Notifikasi sukses */
  window.elcoSuccess = function (title, text = "") {
    openElco({
      ringClass: "success",
      iconHtml:  SVG.success,
      title,
      text,
      actionsHtml: `<button class="elco-modal-btn elco-btn-confirm-success" id="elco-ok-btn">OK</button>`,
    });
    overlay.querySelector('#elco-ok-btn').onclick = closeElco;
  };

  /** Notifikasi error */
  window.elcoError = function (title, text = "") {
    openElco({
      ringClass: "error",
      iconHtml:  SVG.error,
      title,
      text,
      actionsHtml: `<button class="elco-modal-btn elco-btn-confirm-error" id="elco-ok-btn">OK</button>`,
    });
    overlay.querySelector('#elco-ok-btn').onclick = closeElco;
  };

})();