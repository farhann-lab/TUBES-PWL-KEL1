@extends('layouts.manager')

@push('styles')
<style>
    :root {
        /* Tema natural: latte, sand, dan sedikit sage. Sengaja dibuat lebih kalem
           supaya tidak terlalu terlihat seperti template AI/neon. */
        --bg-cream: #f7f3ec;
        --bg-soft: #eee6db;
        --bg-sand: #e4d5c4;
        --card-bg: rgba(255, 255, 255, 0.66);
        --card-bg-strong: rgba(255, 255, 255, 0.80);
        --coffee: #33251e;
        --coffee-2: #514037;
        --brown: #806143;
        --orange: #b86b2b;
        --orange-soft: rgba(184, 107, 43, 0.12);
        --green: #2f7d5c;
        --red: #b85a4b;
        --blue: #3f6f88;
        --purple: #6e5a8e;
        --muted: #786a60;
        --muted-soft: #9a8b80;
        --border: rgba(92, 73, 58, 0.15);
        --shadow: 0 16px 36px rgba(72, 55, 42, 0.10);
    }

    /* ===== GLASS SCENE ===== */
    .glass-scene {
        position: relative;
        min-height: 100vh;
        background:
            radial-gradient(circle at 88% 10%, rgba(184, 107, 43, 0.10), transparent 35%),
            radial-gradient(circle at 4% 92%, rgba(126, 146, 114, 0.10), transparent 30%),
            linear-gradient(135deg, #f7f3ec 0%, #efe7dc 46%, #e4d5c4 100%);
        overflow: hidden;
        color: var(--coffee);
    }
    .glass-scene::before {
        content: '';
        position: fixed;
        top: -100px; right: -80px;
        width: 420px; height: 420px;
        border-radius: 50%;
        background: #c69a6b;
        filter: blur(95px);
        opacity: 0.22;
        pointer-events: none;
        z-index: 0;
    }
    .glass-scene::after {
        content: '';
        position: fixed;
        bottom: -80px; left: -60px;
        width: 340px; height: 340px;
        border-radius: 50%;
        background: #d8c1aa;
        filter: blur(90px);
        opacity: 0.48;
        pointer-events: none;
        z-index: 0;
    }
    .orb-mid {
        position: fixed;
        top: 45%; left: 38%;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: #b8a48f;
        filter: blur(105px);
        opacity: 0.28;
        pointer-events: none;
        z-index: 0;
    }

    /* ===== BASE GLASS CARD ===== */
    .glass-card {
        position: relative;
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 24px;
        box-shadow:
            var(--shadow),
            inset 0 1px 0 rgba(255, 255, 255, 0.75);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        z-index: 1;
    }
    .glass-card:hover {
        transform: translateY(-3px);
        border-color: rgba(92, 73, 58, 0.22);
        box-shadow:
            0 18px 46px rgba(92, 73, 58, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    /* ===== STAT CARDS ===== */
    .stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
    border: 1.5px solid rgba(255, 244, 232, 0.55);
    box-shadow:
        0 0 14px rgba(255, 244, 232, 0.12),
        inset 0 1px 0 rgba(255,255,255,0.28);
    }

    .stat-icon--orange  {
        background: rgba(255, 244, 232, 0.14);
        color: #ff7b6e;
    }

    .stat-icon--emerald {
        background: rgba(52, 211, 153, 0.16);
        color: #34d399;
        border-color: rgba(167, 243, 208, 0.65);
        box-shadow:
            0 0 16px rgba(52, 211, 153, 0.18),
            inset 0 1px 0 rgba(255,255,255,0.28);
    }

    .stat-icon--red {
        background: rgba(251, 113, 133, 0.16);
        color: #fb7185;
        border-color: rgba(253, 164, 175, 0.65);
        box-shadow:
            0 0 16px rgba(251, 113, 133, 0.18),
            inset 0 1px 0 rgba(255,255,255,0.28);
    }

    .stat-label { font-size: 12px; color: var(--muted); margin-bottom: 6px; letter-spacing: 0.04em; }
    .stat-value { font-size: 22px; font-weight: 700; color: var(--coffee); letter-spacing: -0.4px; line-height: 1.2; }
    .stat-value span { font-size: 13px; font-weight: 500; color: var(--muted-soft); }

    /* ===== SECTION TITLE ===== */
    .section-title { font-size: 15px; font-weight: 700; color: var(--coffee); margin-bottom: 4px; }
    .section-sub   { font-size: 11px; color: var(--muted-soft); margin-bottom: 16px; }

    /* ===== CHART CARD ===== */
    .chart-canvas-wrap { height: 256px; width: 100%; }

    /* ===== REQUEST STOK ROWS ===== */
    .req-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 12px;
        border-radius: 16px;
        background: rgba(255,255,255,0.62);
        border: 1px solid rgba(92,73,58,0.10);
        margin-bottom: 10px;
        transition: background 0.2s, border-color 0.2s;
    }
    .req-row:hover { background: rgba(255,255,255,0.88); border-color: rgba(92,73,58,0.16); }
    .req-row:last-child { margin-bottom: 0; }

    .req-icon {
        width: 36px; height: 36px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 15px;
    }
    .req-icon--blue   { background: rgba(63,111,136,0.10); color: var(--blue); border: 1px solid rgba(63,111,136,0.15); }
    .req-icon--purple { background: rgba(110,90,142,0.10); color: var(--purple); border: 1px solid rgba(110,90,142,0.15); }

    .req-name  { font-size: 13px; font-weight: 700; color: var(--coffee); }
    .req-branch{ font-size: 11px; color: var(--muted); margin-top: 2px; }
    .req-qty   { font-size: 11px; color: var(--muted-soft); margin-top: 2px; }

    .req-action-btn {
        width: 28px; height: 28px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        cursor: pointer;
        border: none;
        transition: opacity 0.2s, transform 0.15s;
        flex-shrink: 0;
    }
    .req-action-btn:hover { opacity: 0.86; transform: scale(1.08); }
    .req-action-btn--approve { background: rgba(47,125,92,0.11); color: var(--green); border: 1px solid rgba(47,125,92,0.18); }
    .req-action-btn--reject  { background: rgba(184,90,75,0.10); color: var(--red); border: 1px solid rgba(184,90,75,0.18); }

    .req-empty { text-align: center; padding: 32px 0; color: var(--muted); font-size: 13px; }
    .req-empty i { font-size: 28px; color: var(--green); display: block; margin-bottom: 8px; }

    /* ===== PROMO CARD ===== */
    .glass-card--promo {
        background:
            radial-gradient(circle at 18% 0%, rgba(126,146,114,0.10), transparent 42%),
            linear-gradient(135deg, rgba(255,255,255,0.76), rgba(238,230,219,0.74));
        backdrop-filter: blur(22px);
        -webkit-backdrop-filter: blur(22px);
        border: 1px solid rgba(92,73,58,0.15);
        border-radius: 24px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow), inset 0 1px 0 rgba(255,255,255,0.82);
    }
    .promo-orb  { position: absolute; bottom: -30px; right: -20px; width: 130px; height: 130px; border-radius: 50%; background: rgba(184,107,43,0.10); filter: blur(28px); pointer-events: none; }
    .promo-orb2 { position: absolute; top: -20px; left: -10px; width: 80px; height: 80px; border-radius: 50%; background: rgba(126,146,114,0.12); filter: blur(20px); pointer-events: none; }

    .promo-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; position: relative; z-index: 1; }
    .promo-title  { font-size: 16px; font-weight: 800; color: var(--coffee); }
    .promo-kelola {
        background: rgba(255,255,255,0.62);
        backdrop-filter: blur(8px);
        color: var(--brown);
        font-size: 11px; font-weight: 700;
        padding: 5px 12px;
        border-radius: 99px;
        text-decoration: none;
        border: 1px solid rgba(92,73,58,0.14);
        transition: background 0.2s, color 0.2s;
    }
    .promo-kelola:hover { background: #fff; color: var(--orange); }

    .promo-item {
        padding: 12px;
        background: rgba(255,255,255,0.66);
        border: 1px solid rgba(92,73,58,0.10);
        border-radius: 14px;
        margin-bottom: 8px;
        position: relative; z-index: 1;
    }
    .promo-item-name   { font-size: 13px; font-weight: 700; color: var(--coffee); }
    .promo-item-detail { font-size: 11px; color: var(--muted); margin-top: 3px; }

    .promo-empty { font-size: 13px; color: var(--muted); margin-bottom: 14px; position: relative; z-index: 1; }

    .btn-promo-solid {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, #5c4538, #a86a3a);
        color: #fff;
        font-size: 12px; font-weight: 800;
        border-radius: 14px;
        text-decoration: none;
        transition: transform 0.15s, filter 0.2s;
        box-shadow: 0 8px 18px rgba(184,107,43,0.14);
        position: relative; z-index: 1;
        margin-top: 4px;
    }
    .btn-promo-solid:hover  { filter: brightness(1.04); color: #fff; }
    .btn-promo-solid:active { transform: scale(0.97); }

    /* ===== BRANCH ACTIVITY TABLE ===== */
    .glass-table th {
        font-size: 11px;
        color: var(--muted);
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0 16px 12px;
        border-bottom: 1px solid rgba(92,73,58,0.10);
    }
    .glass-table td {
        padding: 14px 16px;
        font-size: 13px;
        border-bottom: 1px solid rgba(92,73,58,0.07);
    }
    .glass-table tr:last-child td { border-bottom: none; }
    .glass-table tr:hover td { background: rgba(255,255,255,0.46); }
    .glass-table .td-name    { font-weight: 700; color: var(--coffee); }
    .glass-table .td-addr    { color: var(--muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .glass-table .td-trx     { color: var(--muted); }
    .glass-table .td-income  { font-weight: 800; color: var(--green); }

    .branch-icon {
        width: 32px; height: 32px;
        border-radius: 10px;
        background: rgba(184,107,43,0.11);
        color: #a85f28;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        border: 1px solid rgba(184,107,43,0.16);
    }

    .btn-detail {
        font-size: 11px; font-weight: 700;
        color: var(--brown);
        background: rgba(255,255,255,0.64);
        border: 1px solid rgba(92,73,58,0.14);
        padding: 6px 14px;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        white-space: nowrap;
    }
    .btn-detail:hover { background: #fff; color: var(--orange); }

    /* ===== MODAL REJECT ===== */
    .modal-glass {
        background: rgba(250,247,241,0.88);
        backdrop-filter: blur(28px);
        -webkit-backdrop-filter: blur(28px);
        border: 1px solid rgba(92,73,58,0.15);
        border-radius: 28px;
        padding: 32px;
        width: 100%;
        max-width: 420px;
        margin: 0 16px;
        box-shadow: 0 24px 60px rgba(72,55,42,0.18), inset 0 1px 0 rgba(255,255,255,0.88);
    }
    .modal-title { font-size: 17px; font-weight: 800; color: var(--coffee); margin-bottom: 6px; }
    .modal-sub   { font-size: 13px; color: var(--muted); margin-bottom: 20px; }
    .modal-textarea {
        width: 100%;
        padding: 12px 14px;
        border-radius: 14px;
        background: rgba(255,255,255,0.78);
        border: 1px solid rgba(92,73,58,0.15);
        color: var(--coffee);
        font-size: 13px;
        font-family: inherit;
        resize: none;
        outline: none;
        transition: border-color 0.2s;
        margin-bottom: 16px;
    }
    .modal-textarea::placeholder { color: var(--muted-soft); }
    .modal-textarea:focus { border-color: rgba(249,115,22,0.48); }

    .btn-modal-cancel {
        flex: 1; padding: 11px; border-radius: 14px;
        background: rgba(255,255,255,0.72);
        border: 1px solid rgba(92,73,58,0.14);
        color: var(--muted);
        font-size: 13px; font-weight: 700;
        cursor: pointer; transition: background 0.2s;
    }
    .btn-modal-cancel:hover { background: #fff; }
    .btn-modal-reject {
        flex: 1; padding: 11px; border-radius: 14px;
        background: rgba(184,90,75,0.10);
        border: 1px solid rgba(184,90,75,0.18);
        color: var(--red);
        font-size: 13px; font-weight: 800;
        cursor: pointer; transition: background 0.2s;
    }
    .btn-modal-reject:hover { background: rgba(184,90,75,0.16); }

    /* ===== LAYOUT ===== */
    .dash-wrap   { position: relative; z-index: 1; padding: 24px; }
    .dash-grid   { display: grid; grid-template-columns: 1fr 300px; gap: 20px; }
    @media (max-width: 1024px) { .dash-grid { grid-template-columns: 1fr; } }

    .stat-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 20px; }
    @media (max-width: 768px) { .stat-grid-3 { grid-template-columns: 1fr; } }

    .left-col  { display: flex; flex-direction: column; gap: 20px; }
    .right-col { display: flex; flex-direction: column; gap: 20px; }

    /* link lihat semua */
    .link-all { font-size: 11px; color: var(--muted); font-weight: 700; text-decoration: none; transition: color 0.2s; }
    .link-all:hover { color: var(--orange); }


    /* ===== UNIFIED GLASSMORPHISM OVERRIDE ===== */
    .glass-card,
    .glass-card--promo,
    .req-row,
    .promo-item,
    .modal-glass {
        background: linear-gradient(135deg, rgba(255,255,255,0.46), rgba(255,255,255,0.24)) !important;
        backdrop-filter: blur(22px) saturate(150%) !important;
        -webkit-backdrop-filter: blur(22px) saturate(150%) !important;
        border: 1px solid rgba(255,255,255,0.42) !important;
        box-shadow:
            0 14px 34px rgba(96, 76, 56, 0.12),
            inset 0 1px 0 rgba(255,255,255,0.78),
            inset 0 -1px 0 rgba(255,255,255,0.12) !important;
    }

    .glass-card:hover,
    .glass-card--promo:hover,
    .req-row:hover,
    .promo-item:hover {
        border-color: rgba(255,255,255,0.56) !important;
        box-shadow:
            0 18px 42px rgba(96, 76, 56, 0.16),
            inset 0 1px 0 rgba(255,255,255,0.86),
            inset 0 -1px 0 rgba(255,255,255,0.16) !important;
    }

    .glass-card::before,
    .glass-card--promo::before,
    .req-row::before,
    .promo-item::before,
    .modal-glass::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(145deg, rgba(255,255,255,0.20), rgba(255,255,255,0.03) 45%, rgba(255,255,255,0.12));
        pointer-events: none;
    }

    .glass-card > *,
    .glass-card--promo > *,
    .req-row > *,
    .promo-item > *,
    .modal-glass > * {
        position: relative;
        z-index: 1;
    }

    .glass-card--promo {
        overflow: hidden;
    }

    .req-row,
    .promo-item {
        border-radius: 18px;
    }

    .promo-kelola,
    .btn-detail,
    .btn-modal-cancel {
        background: rgba(255,255,255,0.34) !important;
        backdrop-filter: blur(14px) !important;
        -webkit-backdrop-filter: blur(14px) !important;
        border: 1px solid rgba(255,255,255,0.42) !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.65) !important;
    }

    /* ===== REVENUE STATUS COLOR ===== */
.revenue-card {
    overflow: hidden;
}

.revenue-icon {
    background: rgba(255,244,232,0.13) !important;
    border: 1px solid rgba(255,238,220,0.20) !important;
}
/* ===== INCOME VALUE COLOR ONLY ===== */
.income-value--red {
    color: #ff7b6e !important;
    text-shadow: 0 0 14px rgba(255, 123, 110, 0.20);
}

.income-value--yellow {
    color: #ffd28a !important;
    text-shadow: 0 0 14px rgba(255, 210, 138, 0.18);
}

.income-value--green {
    color: #8ff0bf !important;
    text-shadow: 0 0 14px rgba(143, 240, 191, 0.18);
}

.revenue-icon--red {
    color: #ff7b6e !important;
}

.revenue-icon--yellow {
    color: #ffd28a !important;
}

.revenue-icon--green {
    color: #8ff0bf !important;
}

/* ===== FIX KONTRAS TEKS DASHBOARD & REQUEST ===== */

/* Judul dan subtitle card kanan */
.manager-content .section-title,
.manager-content .promo-title,
.manager-content .glass-card p,
.manager-content .glass-card--promo p {
    color: #fff4e8 !important;
}

.manager-content .section-sub,
.manager-content .promo-empty,
.manager-content .req-empty,
.manager-content .req-branch,
.manager-content .req-qty {
    color: rgba(255, 244, 232, 0.82) !important;
}

/* Request stok kosong */
.manager-content .req-empty {
    font-weight: 700 !important;
}

.manager-content .req-empty i {
    color: #34d399 !important;
    opacity: 1 !important;
}

/* Link Lihat Semua */
.manager-content .link-all {
    color: #ffe2b5 !important;
    background: rgba(255, 244, 232, 0.12) !important;
    padding: 4px 8px;
    border-radius: 8px;
    border: 1px solid rgba(255, 238, 220, 0.18);
}

.manager-content .link-all:hover {
    color: #ffffff !important;
    background: rgba(240, 181, 109, 0.25) !important;
}

/* Button Kelola */
.manager-content .promo-kelola {
    color: #fff4e8 !important;
    background: rgba(255, 244, 232, 0.16) !important;
    border: 1px solid rgba(255, 238, 220, 0.24) !important;
}

/* Label card */
.manager-content .stat-label {
    color: rgba(255, 244, 232, 0.82) !important;
    font-weight: 700 !important;
}

/* Angka umum di card */
.manager-content .stat-value {
    color: #fff4e8 !important;
}

/* Khusus pendapatan tetap ikut warna kondisi */
.manager-content .income-value--red {
    color: #ff7b6e !important;
}

.manager-content .income-value--yellow {
    color: #ffd28a !important;
}

.manager-content .income-value--green {
    color: #8ff0bf !important;
}

</style>
@endpush


@php
    $totalIncome = (int) ($data['total_income'] ?? 0);

    if ($totalIncome < 5000000) {
        $incomeTone = 'red';
    } elseif ($totalIncome < 20000000) {
        $incomeTone = 'yellow';
    } else {
        $incomeTone = 'green';
    }
@endphp

@section('content')

<div class="glass-scene">
    <div class="orb-mid"></div>

    <div class="dash-wrap">

        {{-- ==================== MAIN GRID ==================== --}}
        <div class="dash-grid">

            {{-- ========== LEFT COLUMN ========== --}}
            <div class="left-col">

                {{-- Header --}}
                <div>
                    <h2 style="font-size:18px;font-weight:700;color:var(--coffee);">
                        Ringkasan Operasional
                        <span style="font-size:13px;font-weight:400;color:var(--muted-soft);margin-left:6px;">/ Bulan Ini</span>
                    </h2>
                </div>

                {{-- Stat Cards 3 col --}}
                <div class="stat-grid-3">

                    {{-- Total Pendapatan --}}
                <div class="glass-card revenue-card" style="padding:24px;">                    
                    <div class="stat-icon revenue-icon revenue-icon--{{ $incomeTone }}">
                        <i class="ph-fill ph-wallet"></i>
                    </div>

                    <p class="stat-label">Total Pendapatan</p>

                    <div class="stat-value income-value income-value--{{ $incomeTone }}">
                        Rp {{ number_format($data['total_income'], 0, ',', '.') }}
                    </div>
                </div>

                    {{-- Cabang Aktif --}}
                    <div class="glass-card" style="padding:24px;">
                        <div class="stat-icon stat-icon--emerald">
                            <i class="ph-fill ph-storefront"></i>
                        </div>
                        <p class="stat-label">Cabang Aktif</p>
                        <div class="stat-value">
                            {{ $data['total_branches'] }} <span>cabang</span>
                        </div>
                    </div>

                    {{-- Pengajuan Pending --}}
                    <div class="glass-card" style="padding:24px;">
                        <div class="stat-icon stat-icon--red">
                            <i class="ph-fill ph-package"></i>
                        </div>
                        <p class="stat-label">Pengajuan Pending</p>
                        <div class="stat-value">
                            {{ $data['pending_requests'] }} <span>permintaan</span>
                        </div>
                    </div>

                </div>

                {{-- Chart Pendapatan --}}
                <div class="glass-card" style="padding:24px;">
                    <p class="section-title">Grafik Pendapatan Bulan Ini</p>
                    <div class="chart-canvas-wrap">
                        <canvas 
                            id="revenueChart"
                            data-total-income="{{ $data['total_income'] ?? 0 }}">
                        </canvas>
                    </div>
                </div>

            </div>{{-- /left-col --}}

            {{-- ========== RIGHT COLUMN ========== --}}
            <div class="right-col">

                {{-- Request Stok --}}
                <div class="glass-card" style="padding:22px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                        <div>
                            <p class="section-title" style="margin-bottom:2px;">Request Stok</p>
                            <p class="section-sub">Pengajuan menunggu verifikasi</p>
                        </div>
                        <a href="{{ route('manager.stock-requests.index') }}" class="link-all">Lihat Semua</a>
                    </div>

                    @forelse($data['latest_requests'] as $req)
                    <div class="req-row">
                        <div style="display:flex;align-items:flex-start;gap:10px;flex:1;">
                            <div class="req-icon {{ $req->type === 'stock' ? 'req-icon--blue' : 'req-icon--purple' }}">
                                <i class="ph-fill {{ $req->type === 'stock' ? 'ph-package' : 'ph-wrench' }}"></i>
                            </div>
                            <div>
                                <p class="req-name">{{ $req->item_name }}</p>
                                <p class="req-branch">{{ $req->branch->name ?? '-' }}</p>
                                <p class="req-qty">{{ $req->quantity }} {{ $req->unit }}</p>
                            </div>
                        </div>
                        <div style="display:flex;gap:6px;margin-left:8px;flex-shrink:0;">
                            {{-- Approve --}}
                            <form id="approve-{{ $req->id }}"
                                  action="{{ route('manager.stock-requests.approve', $req) }}" method="POST">
                                @csrf
                                <button type="button" class="req-action-btn req-action-btn--approve"
                                    onclick="elcoConfirm({
                                        title: 'Setujui Pengajuan?',
                                        text: 'Stok akan bertambah otomatis setelah disetujui.',
                                        confirmText: 'Ya, Setujui',
                                        confirmColor: '#2f7d5c',
                                        icon: 'question',
                                        onConfirm: () => document.getElementById('approve-{{ $req->id }}').submit()
                                    })">
                                    <i class="ph ph-check"></i>
                                </button>
                            </form>
                            {{-- Reject --}}
                            <button type="button" class="req-action-btn req-action-btn--reject"
                                onclick="openRejectModal({{ $req->id }})">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="req-empty">
                        <i class="ph ph-check-circle"></i>
                        Tidak ada pengajuan pending
                    </div>
                    @endforelse
                </div>

                {{-- Promo Aktif --}}
                <div class="glass-card--promo">
                    <div class="promo-orb"></div>
                    <div class="promo-orb2"></div>

                    <div class="promo-header">
                        <p class="promo-title">Promo Aktif</p>
                        <a href="{{ route('manager.promotions.index') }}" class="promo-kelola">Kelola</a>
                    </div>

                    @if($data['active_promos']->count() > 0)
                        @foreach($data['active_promos'] as $promo)
                        <div class="promo-item">
                            <p class="promo-item-name">{{ $promo->name }}</p>
                            <p class="promo-item-detail">
                                Diskon {{ $promo->discount_label }} &bull;
                                s/d {{ $promo->end_date->format('d M Y') }}
                            </p>
                        </div>
                        @endforeach
                        <a href="{{ route('manager.promotions.create') }}" class="btn-promo-solid" style="margin-top:12px;">
                            <i class="ph ph-plus"></i> Tambah Promo
                        </a>
                    @else
                        <p class="promo-empty">Belum ada promo aktif saat ini.</p>
                        <a href="{{ route('manager.promotions.create') }}" class="btn-promo-solid">
                            <i class="ph ph-plus"></i> Buat Promo Sekarang
                        </a>
                    @endif
                </div>

            </div>{{-- /right-col --}}

        </div>{{-- /dash-grid --}}

        {{-- ========== TABEL AKTIVITAS CABANG (full width) ========== --}}
        <div class="glass-card" style="padding:24px;margin-top:20px;">
            <p class="section-title" style="margin-bottom:16px;">Aktivitas Cabang Hari Ini</p>
            <div style="overflow-x:auto;">
                <table class="glass-table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Nama Cabang</th>
                            <th style="text-align:left;">Alamat</th>
                            <th style="text-align:left;">Transaksi</th>
                            <th style="text-align:left;">Omset Hari Ini</th>
                            <th style="text-align:left;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['branch_activities'] as $branch)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="branch-icon">
                                        <i class="ph-fill ph-storefront"></i>
                                    </div>
                                    <span class="td-name">{{ $branch['name'] }}</span>
                                </div>
                            </td>
                            <td class="td-addr">{{ $branch['address'] }}</td>
                            <td class="td-trx">{{ $branch['trx'] }} transaksi</td>
                            <td class="td-income">Rp {{ number_format($branch['income'], 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('manager.reports.index') }}?branch_id={{ $branch['id'] }}"
                                   class="btn-detail">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:32px 0;color:var(--muted);font-size:13px;">
                                Belum ada cabang aktif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /dash-wrap --}}
</div>{{-- /glass-scene --}}

{{-- ========== MODAL REJECT ========== --}}
<div id="rejectModal" class="hidden" style="position:fixed;inset:0;background:rgba(51,37,30,0.25);backdrop-filter:blur(6px);z-index:50;display:none;align-items:center;justify-content:center;">
    <div class="modal-glass">
        <p class="modal-title">Tolak Pengajuan</p>
        <p class="modal-sub">Berikan alasan penolakan.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_note" rows="3" required
                placeholder="Alasan penolakan..."
                class="modal-textarea"></textarea>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeRejectModal()" class="btn-modal-cancel">Batal</button>
                <button type="submit" class="btn-modal-reject">Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/manager/stock-requests/${id}/reject`;
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('revenueChart');
    if (!canvas || !window.Chart) return;

    const totalIncome = Number(canvas.dataset.totalIncome || 0);

    let lineColor = '#ff7b6e';

    if (totalIncome >= 20000000) {
        lineColor = '#8ff0bf';
    } else if (totalIncome >= 5000000) {
        lineColor = '#ffd28a';
    }

    const ctx = canvas.getContext('2d');

    let gradientStart = 'rgba(255, 123, 110, 0.32)';

if (totalIncome >= 20000000) {
    gradientStart = 'rgba(143, 240, 191, 0.32)';
} else if (totalIncome >= 5000000) {
    gradientStart = 'rgba(255, 210, 138, 0.32)';
}

const gradient = ctx.createLinearGradient(0, 0, 0, 320);
gradient.addColorStop(0, gradientStart);
gradient.addColorStop(1, 'rgba(255, 244, 232, 0.02)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, totalIncome],
                borderColor: lineColor,
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff4e8',
                pointBorderColor: lineColor,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1c100b',
                    titleColor: '#fff4e8',
                    bodyColor: '#fff4e8',
                    borderColor: 'rgba(255, 238, 220, 0.18)',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function (context) {
                            return 'Pendapatan: Rp ' + Number(context.raw).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 244, 232, 0.24)'
                    },
                    ticks: {
                        color: 'rgba(255, 244, 232, 0.92)',
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        callback: function (value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: 'rgba(255, 244, 232, 0.92)',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush