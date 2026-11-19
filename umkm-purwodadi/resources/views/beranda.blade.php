@extends('layouts.app')

@section('title', 'Beranda - UMKM Dusun 01 Purwodadi Trimurjo')

@section('styles')
<style>
/* ============================================================
   PALET WARNA — Diambil dari foto produk nyata
   • Kuning emas kelanting : #C8900A / #E5A812
   • Cokelat anyaman rotan : #C4A270 / #A07840
   • Cokelat kayu mangkuk  : #7C3D12 / #5C2A08
   • Cokelat tua pisang    : #4A1E0A
   • Krem goni / kain      : #F0E0C0 / #E8D5B0
   • Putih krem bersih     : #FFFDF5
   ============================================================ */
:root {
    --g-gold    : #C8900A;
    --g-gold-lt : #E5A812;
    --g-brown   : #7C3D12;
    --g-dark    : #4A1E0A;
    --g-rattan  : #A07840;
    --g-cream   : #F0E0C0;
    --g-light   : #FFFDF5;
    --g-green   : #064e3b;
}

/* ---- body bg ---- */
body { background: var(--g-light); }

/* ============================================================
   HERO — foto sebagai background + overlay gelap hangat
   ============================================================ */
.hero-section {
    position: relative;
    min-height: 88vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 0;
}

/* foto asli sebagai bg */
.hero-bg-photo {
    position: absolute;
    inset: 0;
    background-image: url("{{ asset('images/hero-cemilan.jpg') }}");
    background-size: cover;
    background-position: center 40%;
    background-repeat: no-repeat;
    transform: scale(1.04);
    transition: transform 8s ease;
}
.hero-section:hover .hero-bg-photo { transform: scale(1); }

/* multi-layer overlay: kiri gelap untuk teks, kanan tembus */
.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        105deg,
        rgba(74, 30, 10, 0.88) 0%,
        rgba(74, 30, 10, 0.75) 35%,
        rgba(74, 30, 10, 0.40) 65%,
        rgba(74, 30, 10, 0.10) 100%
    );
}

/* anyaman rotan pattern overlay subtle */
.hero-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.04;
    background-image:
        repeating-linear-gradient(45deg,  #C4A270 0, #C4A270 1px, transparent 0, transparent 50%),
        repeating-linear-gradient(-45deg, #C4A270 0, #C4A270 1px, transparent 0, transparent 50%);
    background-size: 12px 12px;
}

.hero-content {
    position: relative;
    z-index: 10;
    padding: 6rem 0 5rem;
    width: 100%;
}

/* badge di atas judul */
.hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(200, 144, 10, 0.25);
    border: 1.5px solid rgba(229, 168, 18, 0.55);
    backdrop-filter: blur(6px);
    color: #FFE08A;
    font-size: 0.84rem;
    font-weight: 700;
    padding: 0.4rem 1.1rem;
    border-radius: 50rem;
    letter-spacing: 0.4px;
    margin-bottom: 1.2rem;
}

.hero-title {
    font-size: 3.4rem;
    font-weight: 900;
    line-height: 1.12;
    letter-spacing: -1.5px;
    color: #FFFDF5;
    text-shadow: 0 3px 16px rgba(0,0,0,0.4);
}
.hero-title .hl {
    color: #FFD166;
    position: relative;
}
.hero-title .hl::after {
    content: '';
    position: absolute;
    bottom: -3px; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #E5A812, transparent);
    border-radius: 2px;
}
@media (max-width: 768px) { .hero-title { font-size: 2.2rem; } }

.hero-desc {
    color: rgba(255,253,245,0.85);
    font-size: 1.08rem;
    line-height: 1.72;
    max-width: 540px;
}
.hero-desc strong { color: #FFD166; }

/* CTA buttons */
.btn-gold {
    background: linear-gradient(135deg, #E5A812, #C8900A);
    color: #3D1A00;
    font-weight: 800;
    font-size: 1rem;
    padding: 0.9rem 2rem;
    border: none;
    border-radius: 0.75rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 6px 24px rgba(200,144,10,0.5);
    transition: all 0.25s ease;
}
.btn-gold:hover {
    background: linear-gradient(135deg, #FFD166, #E5A812);
    color: #2A0E00;
    transform: translateY(-3px);
    box-shadow: 0 10px 32px rgba(200,144,10,0.6);
}

.btn-ghost-white {
    background: rgba(255,255,255,0.12);
    color: #FFFDF5;
    font-weight: 700;
    font-size: 1rem;
    padding: 0.9rem 1.8rem;
    border: 2px solid rgba(255,255,255,0.4);
    border-radius: 0.75rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(4px);
    transition: all 0.25s ease;
}
.btn-ghost-white:hover {
    background: rgba(255,255,255,0.22);
    color: #fff;
    border-color: #fff;
    transform: translateY(-3px);
}

/* trust row in hero */
.hero-trust {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem 1.4rem;
    margin-top: 1.5rem;
    padding-top: 1.4rem;
    border-top: 1px solid rgba(255,253,245,0.18);
}
.hero-trust-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.84rem;
    font-weight: 600;
    color: rgba(255,253,245,0.85);
}
.hero-trust-item i { color: #FFD166; }

/* hero card kanan */
.hero-card {
    background: rgba(255, 253, 245, 0.97);
    border-radius: 1.4rem;
    box-shadow: 0 28px 70px rgba(74, 30, 10, 0.5);
    padding: 1.75rem;
    border: 2px solid rgba(200, 144, 10, 0.25);
    animation: slideInRight 0.7s ease 0.2s both;
}
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: translateX(0); }
}

.hero-menu-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.55rem 0.8rem;
    border-radius: 0.85rem;
    margin-bottom: 0.55rem;
    background: #FFF8E8;
    border: 1.5px solid #F0D080;
    transition: all 0.22s ease;
    text-decoration: none;
    color: inherit;
}
.hero-menu-item:hover {
    transform: translateX(6px);
    box-shadow: 0 6px 18px rgba(200,144,10,0.22);
    border-color: #C8900A;
    background: #FFF3D0;
    color: inherit;
}
.hero-menu-thumb {
    width: 48px;
    height: 48px;
    border-radius: 0.65rem;
    object-fit: cover;
    border: 1.5px solid #F0D080;
    background: #FFE8A3;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(74, 30, 10, 0.12);
}
.hero-menu-thumb-fallback {
    width: 48px;
    height: 48px;
    border-radius: 0.65rem;
    background: linear-gradient(135deg, #FFF3D0, #F0D090);
    border: 1.5px solid #F0D080;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
}
.hero-price-badge {
    background: linear-gradient(135deg, #C8900A, #7C3D12);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.28rem 0.65rem;
    border-radius: 50rem;
    white-space: nowrap;
}

/* wave separator bawah hero */
.hero-wave {
    position: absolute;
    bottom: -2px; left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    z-index: 5;
}

/* ============================================================
   STATS BAR
   ============================================================ */
.stat-bar {
    background: #fff;
    border-radius: 1.25rem;
    box-shadow: 0 10px 40px rgba(74, 30, 10, 0.12);
    border: 2px solid #F0D090;
    overflow: hidden;
    position: relative;
    z-index: 20;
    margin-top: -2.5rem;
}
.stat-bar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #C8900A, #7C3D12, #C8900A);
}
.stat-item {
    padding: 1.5rem 1rem;
    text-align: center;
    border-right: 1px solid #F5E8C0;
    transition: background 0.2s;
}
.stat-item:last-child { border-right: none; }
.stat-item:hover { background: #FFF8E8; }
.stat-num {
    display: block;
    font-size: 2.1rem;
    font-weight: 900;
    background: linear-gradient(135deg, #C8900A, #7C3D12);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
}
.stat-lbl {
    font-size: 0.78rem;
    font-weight: 700;
    color: #7C3D12;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.3rem;
}

/* ============================================================
   SECTION HELPERS
   ============================================================ */
.sec-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #FFF3D0;
    color: #7C3D12;
    font-size: 0.8rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0.32rem 1rem;
    border-radius: 50rem;
    border: 1.5px solid #F0D090;
    margin-bottom: 0.65rem;
}
.sec-title {
    font-size: 2rem;
    font-weight: 900;
    color: #2D1206;
    letter-spacing: -0.5px;
}
.sec-title .ul-gold {
    position: relative;
    display: inline-block;
}
.sec-title .ul-gold::after {
    content: '';
    position: absolute;
    bottom: -4px; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #C8900A, #E5A812);
    border-radius: 2px;
}

/* ============================================================
   CATEGORY CHIPS
   ============================================================ */
.chip-row {
    display: flex;
    gap: 0.55rem;
    flex-wrap: wrap;
}
.chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.48rem 1.1rem;
    border-radius: 50rem;
    font-size: 0.87rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.22s ease;
    border: 2px solid transparent;
}
.chip-gold   { background:#FFF3D0; color:#7C3D12; border-color:#F0D090; }
.chip-gold:hover { background:#C8900A; color:#fff; border-color:#C8900A; transform:translateY(-2px); box-shadow:0 6px 16px rgba(200,144,10,.35); }
.chip-brown  { background:#FAF0E0; color:#4A1E0A; border-color:#E0C090; }
.chip-brown:hover { background:#7C3D12; color:#fff; border-color:#7C3D12; transform:translateY(-2px); box-shadow:0 6px 16px rgba(124,61,18,.35); }
.chip-green  { background:#ecfdf5; color:#064e3b; border-color:#a7f3d0; }
.chip-green:hover { background:#064e3b; color:#fff; border-color:#064e3b; transform:translateY(-2px); box-shadow:0 6px 16px rgba(6,78,59,.3); }
.chip-dark   { background:#2D1206; color:#FFD166; border-color:#2D1206; }
.chip-dark:hover { background:#4A1E0A; color:#FFD166; transform:translateY(-2px); }

/* ============================================================
   SECTION: 3 CAMILAN IKONIK
   ============================================================ */
.iconic-section {
    padding: 5.5rem 0;
    background: #FFFDF5;
    position: relative;
}
.iconic-section::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #F0D090, transparent);
}

.icon-card {
    background: #fff;
    border-radius: 1.3rem;
    border: 2px solid #F5EAC0;
    padding: 2rem 1.6rem;
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.35s ease;
    position: relative;
    overflow: hidden;
}
.icon-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, #C8900A, #E5A812);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.35s ease;
}
.icon-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(200,144,10,0.15);
    border-color: #E5A812;
}
.icon-card:hover::before { transform: scaleX(1); }

.icon-card-img {
    width: 104px;
    height: 104px;
    border-radius: 50%;
    object-fit: cover;
    border: 3.5px solid #F0D090;
    box-shadow: 0 4px 16px rgba(124, 61, 18, 0.18);
    margin-bottom: 1.1rem;
    transition: all 0.35s ease;
    background: #FFF3D0;
}
.icon-card:hover .icon-card-img {
    transform: scale(1.1) rotate(-3deg);
    border-color: #C8900A;
    box-shadow: 0 8px 24px rgba(200, 144, 10, 0.3);
}

.emoji-ring {
    width: 92px; height: 92px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 3.2rem;
    margin-bottom: 1.2rem;
    transition: transform 0.35s ease;
}
.icon-card:hover .emoji-ring { transform: scale(1.12) rotate(-6deg); }
.er-gold   { background: linear-gradient(135deg, #FFF3D0, #F0D090); }
.er-brown  { background: linear-gradient(135deg, #FAF0E0, #E0C090); }
.er-green  { background: linear-gradient(135deg, #ecfdf5, #a7f3d0); }

/* ============================================================
   SECTION: PRODUK UNGGULAN
   ============================================================ */
.produk-section {
    background: linear-gradient(180deg, #FFF8F0 0%, #FFFDF5 100%);
    padding: 5.5rem 0;
}

.produk-card {
    background: #fff;
    border-radius: 1.25rem;
    border: 2px solid #F5EAC0;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.35s ease;
}
.produk-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 60px rgba(200,144,10,0.14);
    border-color: #C8900A;
}

.produk-img-wrap {
    position: relative;
    height: 205px;
    overflow: hidden;
    background: linear-gradient(135deg, #FFF3D0, #F5EAC0);
}
.produk-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.produk-card:hover .produk-img-wrap img { transform: scale(1.08); }
.produk-emoji-fallback {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 5rem;
    background: linear-gradient(135deg, #FFF3D0, #F0D090);
    transition: transform 0.5s ease;
}
.produk-card:hover .produk-emoji-fallback { transform: scale(1.1) rotate(-3deg); }

.stok-badge {
    position: absolute;
    top: 0.7rem; right: 0.7rem;
    background: rgba(66, 27, 7, 0.92);
    border: 1px solid rgba(200, 144, 10, 0.5);
    color: #FFD166;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.28rem 0.65rem;
    border-radius: 50rem;
    backdrop-filter: blur(4px);
    z-index: 2;
}
.stok-badge.low { background: rgba(220,38,38,0.9); }

.hot-badge {
    position: absolute;
    top: 0.7rem; left: 0.7rem;
    background: linear-gradient(135deg, #C8900A, #E5A812);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    padding: 0.28rem 0.65rem;
    border-radius: 50rem;
    z-index: 2;
}

.produk-body { padding: 1.2rem; display: flex; flex-direction: column; flex-grow: 1; }
.produk-name { font-size: 1rem; font-weight: 800; color: #2D1206; margin-bottom: 0.4rem; line-height: 1.3; }
.produk-desc { font-size: 0.82rem; color: #78716c; line-height: 1.55; flex-grow: 1; margin-bottom: 0.9rem; }
.produk-price { font-size: 1.3rem; font-weight: 900; color: #C8900A; }
.produk-price-sub { font-size: 0.74rem; color: #a8a29e; font-weight: 600; }

.btn-cart {
    background: linear-gradient(135deg, #C8900A, #7C3D12);
    color: #fff;
    font-weight: 800;
    border: none;
    border-radius: 0.6rem;
    padding: 0.55rem 0.75rem;
    font-size: 0.85rem;
    width: 100%;
    transition: all 0.25s ease;
    cursor: pointer;
}
.btn-cart:hover {
    background: linear-gradient(135deg, #E5A812, #C8900A);
    color: #2A0E00;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(200,144,10,0.4);
}
.btn-detail {
    background: #FFF3D0;
    color: #7C3D12;
    font-weight: 700;
    border: 2px solid #F0D090;
    border-radius: 0.6rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    text-decoration: none;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.22s ease;
}
.btn-detail:hover { background: #F0D090; color: #4A1E0A; border-color: #C8900A; }

/* ============================================================
   SECTION: RATTAN / WHY SECTION
   background mirip anyaman bambu dari foto
   ============================================================ */
.why-section {
    position: relative;
    padding: 5.5rem 0;
    overflow: hidden;
    background-color: #4A1E0A;
    background-image:
        repeating-linear-gradient( 45deg, rgba(200,144,10,0.07) 0, rgba(200,144,10,0.07) 2px, transparent 0, transparent 50%),
        repeating-linear-gradient(-45deg, rgba(200,144,10,0.07) 0, rgba(200,144,10,0.07) 2px, transparent 0, transparent 50%);
    background-size: 20px 20px;
}
.why-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(74,30,10,0.95) 0%, rgba(124,61,18,0.88) 50%, rgba(74,30,10,0.95) 100%);
}
.why-card {
    position: relative;
    z-index: 1;
    background: rgba(255,253,245,0.08);
    backdrop-filter: blur(8px);
    border: 1.5px solid rgba(229,168,18,0.25);
    border-radius: 1.25rem;
    padding: 2rem 1.5rem;
    text-align: center;
    color: #FFFDF5;
    height: 100%;
    transition: all 0.3s ease;
}
.why-card:hover {
    background: rgba(255,253,245,0.14);
    border-color: rgba(229,168,18,0.5);
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.3);
}
.why-icon { font-size: 3rem; display: block; margin-bottom: 1rem; }
.why-title { font-weight: 800; font-size: 1.08rem; margin-bottom: 0.5rem; color: #FFD166; }
.why-desc  { font-size: 0.86rem; line-height: 1.65; color: rgba(255,253,245,0.78); }

/* ============================================================
   STEPS SECTION
   ============================================================ */
.steps-section {
    background: #FFF8F0;
    padding: 5.5rem 0;
    position: relative;
}
.steps-section::before,
.steps-section::after {
    content: '';
    position: absolute;
    left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, #F0D090, transparent);
}
.steps-section::before { top: 0; }
.steps-section::after  { bottom: 0; }

.step-card {
    background: #fff;
    border: 2px solid #F5EAC0;
    border-radius: 1.25rem;
    padding: 2rem 1.4rem;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}
.step-card:hover {
    border-color: #C8900A;
    box-shadow: 0 12px 32px rgba(200,144,10,0.13);
    transform: translateY(-5px);
}
.step-num {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #C8900A, #7C3D12);
    color: #fff;
    font-size: 1.5rem;
    font-weight: 900;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 0.8rem;
    box-shadow: 0 6px 18px rgba(200,144,10,0.4);
}
.step-emoji { font-size: 2rem; display: block; margin-bottom: 0.6rem; }
.step-title { font-weight: 800; font-size: 1rem; color: #2D1206; margin-bottom: 0.5rem; }
.step-desc  { font-size: 0.83rem; color: #78716c; line-height: 1.55; }

/* ============================================================
   TRUST BADGES
   ============================================================ */
.trust-section { background: #FFFDF5; padding: 4.5rem 0; }
.trust-card {
    background: #FFF3D0;
    border: 2px solid #F0D090;
    border-radius: 1rem;
    padding: 1.5rem 1rem;
    text-align: center;
    transition: all 0.25s ease;
}
.trust-card:hover {
    border-color: #C8900A;
    box-shadow: 0 8px 24px rgba(200,144,10,0.14);
    transform: translateY(-4px);
}
.trust-icon  { font-size: 2.6rem; display: block; margin-bottom: 0.6rem; }
.trust-title { font-weight: 800; color: #4A1E0A; font-size: 0.9rem; }
.trust-sub   { font-size: 0.75rem; color: #7C3D12; margin-top: 0.2rem; }

/* ============================================================
   CTA BANNER
   ============================================================ */
.cta-banner {
    border-radius: 1.75rem;
    overflow: hidden;
    position: relative;
    color: #FFFDF5;
    padding: 4rem 2.5rem;
    background: linear-gradient(105deg, #3d1906 0%, #52230b 50%, #683011 100%);
    border: 2px solid #C8900A;
    box-shadow: 0 16px 48px rgba(61, 25, 6, 0.25);
}
.cta-banner::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
}
.cta-deco {
    position: absolute;
    top: -1rem; right: 2rem;
    font-size: 6rem;
    opacity: 0.07;
    white-space: nowrap;
    letter-spacing: 1rem;
}
.btn-wa {
    background: #25d366;
    color: #fff;
    font-weight: 800;
    padding: 1rem 2.2rem;
    border-radius: 0.85rem;
    border: none;
    font-size: 1.05rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.5rem;
    box-shadow: 0 6px 20px rgba(37,211,102,0.4);
    transition: all 0.25s ease;
}
.btn-wa:hover {
    background: #128c40; color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(37,211,102,0.5);
}
.btn-katalog-cta {
    background: rgba(255,255,255,0.14);
    color: #FFFDF5;
    font-weight: 700;
    padding: 1rem 2rem;
    border-radius: 0.85rem;
    border: 2px solid rgba(255,255,255,0.4);
    font-size: 1rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.5rem;
    transition: all 0.25s ease;
}
.btn-katalog-cta:hover {
    background: rgba(255,255,255,0.26);
    color: #fff; border-color: #fff;
    transform: translateY(-3px);
}

/* ============================================================
   SCROLL REVEAL
   ============================================================ */
.reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.reveal.visible { opacity: 1; transform: none; }

.reveal-s > * {
    opacity: 0; transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.reveal-s.visible > *:nth-child(1) { opacity:1;transform:none;transition-delay:.00s }
.reveal-s.visible > *:nth-child(2) { opacity:1;transform:none;transition-delay:.10s }
.reveal-s.visible > *:nth-child(3) { opacity:1;transform:none;transition-delay:.20s }
.reveal-s.visible > *:nth-child(4) { opacity:1;transform:none;transition-delay:.30s }
.reveal-s.visible > *:nth-child(5) { opacity:1;transform:none;transition-delay:.40s }
.reveal-s.visible > *:nth-child(6) { opacity:1;transform:none;transition-delay:.50s }
</style>
@endsection

@section('content')

{{-- ==================== HERO — FOTO PRODUK NYATA ==================== --}}
<section class="hero-section">
    <div class="hero-bg-photo"></div>
    <div class="hero-overlay"></div>
    <div class="hero-pattern"></div>

    <div class="container hero-content">
        <div class="row align-items-center g-5">

            {{-- KIRI: Teks & CTA --}}
            <div class="col-lg-6">
                <div class="hero-pill">
                    <i class="bi bi-geo-alt-fill"></i>
                    Dusun 01 Purwodadi · Trimurjo · Lampung Tengah
                </div>

                <h1 class="hero-title mb-4">
                    Camilan <span class="hl">Tradisional</span><br>
                    Renyah &amp; Gurih<br>
                    <span style="color:#FFD166;">Asli Lampung</span>
                </h1>

                <p class="hero-desc mb-4">
                    Diproduksi langsung oleh tangan-tangan terampil warga UMKM Dusun 01 Purwodadi.
                    Nikmati kelezatan autentik <strong>Kelanting</strong>, <strong>Untir-untir</strong>,
                    <strong>Keripik Pisang</strong> &amp; masih banyak lagi —
                    tanpa pengawet, 100% bahan lokal segar.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-2">
                    <a href="{{ route('katalog.index') }}" class="btn-gold">
                        <i class="bi bi-bag-heart-fill"></i> Belanja Sekarang
                    </a>
                    <a href="{{ route('booking.tracking') }}" class="btn-ghost-white">
                        <i class="bi bi-search"></i> Cek Pesanan
                    </a>
                </div>

                <div class="hero-trust">
                    <div class="hero-trust-item"><i class="bi bi-patch-check-fill"></i> 100% Halal &amp; Alami</div>
                    <div class="hero-trust-item"><i class="bi bi-lightning-charge-fill"></i> Bayar QRIS / VA</div>
                    <div class="hero-trust-item"><i class="bi bi-truck"></i> Siap Kirim ke Rumah</div>
                    <div class="hero-trust-item"><i class="bi bi-award-fill"></i> UMKM Terpercaya</div>
                </div>
            </div>

            {{-- KANAN: Showcase Card --}}
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge fw-bold px-3 py-2 rounded-pill"
                              style="background:#FFF3D0; color:#7C3D12; font-size:.8rem;">
                            <i class="bi bi-stars me-1"></i>Menu Terlaris
                        </span>
                        <small class="text-muted fw-semibold">Dusun 01 Purwodadi ✨</small>
                    </div>

                    <p class="fw-bold mb-3" style="color:#2D1206; font-size:.95rem;">
                        🏆 Favorit Pelanggan Setia Kami:
                    </p>

                    @php
                    $emojiMap = [
                        'kelanting'=>'🥨','untir'=>'🍩','singkong'=>'🥔','keripik'=>'🥔',
                        'pisang'=>'🍌','peyek'=>'🥜','emping'=>'🫘','tempe'=>'🌶️',
                        'kacang'=>'🥜','rengginang'=>'🍘','kerupuk'=>'🧆',
                        'ubi'=>'🍠','talas'=>'🌿','jagung'=>'🌽','ikan'=>'🐟',
                    ];
                    @endphp

                    @forelse($produkUnggulan->take(4) as $p)
                        @php
                            $namaP = strtolower($p->merk ?? '');
                            $emP = '🍿';
                            foreach ($emojiMap as $key => $em) {
                                if (str_contains($namaP, $key)) { $emP = $em; break; }
                            }
                        @endphp
                        <a href="{{ route('katalog.detail', $p->id_produk) }}" class="hero-menu-item">
                            <div class="d-flex align-items-center gap-3" style="min-width: 0;">
                                @if($p->gambar && file_exists(public_path('uploads/produk/' . $p->gambar)))
                                    <img src="{{ asset('uploads/produk/' . $p->gambar) }}" alt="{{ $p->merk }}" class="hero-menu-thumb">
                                @else
                                    <div class="hero-menu-thumb-fallback">{{ $emP }}</div>
                                @endif
                                <div style="min-width: 0;">
                                    <strong style="font-size:.9rem; color:#2D1206; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $p->merk }}
                                    </strong>
                                    <small class="text-muted" style="display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ \Illuminate\Support\Str::limit($p->deskripsi ?? 'Camilan khas Dusun 01 Purwodadi.', 42) }}
                                    </small>
                                </div>
                            </div>
                            <span class="hero-price-badge ms-2">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                        </a>
                    @empty
                        <p class="text-muted small">Belum ada produk unggulan di database.</p>
                    @endforelse

                    <a href="{{ route('katalog.index') }}"
                       class="btn-gold d-flex justify-content-center mt-3"
                       style="border-radius:.7rem; font-size:.95rem;">
                        Lihat Semua Produk di Katalog <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Wave bawah --}}
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80"
             preserveAspectRatio="none" style="height:80px;width:100%;">
            <path fill="#FFFDF5" d="M0,40 C240,80 480,0 720,40 C960,80 1200,0 1440,40 L1440,80 L0,80 Z"/>
        </svg>
    </div>
</section>

{{-- ==================== STATS BAR ==================== --}}
<div class="container" style="margin-top:-0.5rem;">
    <div class="stat-bar">
        <div class="row g-0 text-center">
            <div class="col-6 col-md-3 stat-item">
                <span class="stat-num">2010</span>
                <div class="stat-lbl">🏡 Merintis Sejak</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <span class="stat-num">{{ $totalProduk ?? '15' }}+</span>
                <div class="stat-lbl">🍟 Varian Camilan</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <span class="stat-num">100%</span>
                <div class="stat-lbl">🌿 Bahan Alami</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <span class="stat-num">QRIS</span>
                <div class="stat-lbl">💳 Payment Tersedia</div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== CHIPS KATEGORI ==================== --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4 reveal">
            <div class="sec-label">🔍 Jelajahi Kategori</div>
            <h2 class="sec-title">Temukan <span class="ul-gold">Camilan</span> Favoritmu</h2>
            <p class="text-muted mt-2" style="max-width:580px; margin:0 auto; font-size:.95rem;">
                Pilih kategori camilan khas UMKM Purwodadi — langsung diarahkan ke produk terkait!
            </p>
        </div>
        <div class="chip-row justify-content-center reveal">
            <a href="{{ route('katalog.index', ['cari' => 'kelanting']) }}"        class="chip chip-gold">🥨 Kelanting</a>
            <a href="{{ route('katalog.index', ['cari' => 'untir']) }}"            class="chip chip-brown">🍩 Untir-Untir</a>
            <a href="{{ route('katalog.index', ['cari' => 'keripik singkong']) }}" class="chip chip-gold">🥔 Keripik Singkong</a>
            <a href="{{ route('katalog.index', ['cari' => 'keripik pisang']) }}"   class="chip chip-brown">🍌 Keripik Pisang</a>
            <a href="{{ route('katalog.index', ['cari' => 'peyek']) }}"            class="chip chip-green">🥜 Peyek Kacang</a>
            <a href="{{ route('katalog.index', ['cari' => 'emping']) }}"           class="chip chip-gold">🫘 Emping Melinjo</a>
            <a href="{{ route('katalog.index', ['cari' => 'tempe']) }}"            class="chip chip-brown">🌶️ Keripik Tempe</a>
            <a href="{{ route('katalog.index', ['cari' => 'kacang']) }}"           class="chip chip-green">🥜 Kacang Goreng</a>
            <a href="{{ route('katalog.index', ['cari' => 'rengginang']) }}"       class="chip chip-gold">🍘 Rengginang</a>
            <a href="{{ route('katalog.index', ['cari' => 'kerupuk']) }}"          class="chip chip-brown">🧆 Kerupuk</a>
            <a href="{{ route('katalog.index') }}"                                 class="chip chip-dark">🛒 Semua Produk</a>
        </div>
    </div>
</section>

{{-- ==================== 3 CAMILAN IKONIK ==================== --}}
<section class="iconic-section">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="sec-label">⭐ Produk Unggulan</div>
            <h2 class="sec-title">3 Camilan <span class="ul-gold">Ikonik</span> Dusun 01</h2>
            <p class="text-muted mt-2" style="max-width:640px; margin:0 auto; font-size:.95rem;">
                Tiga pilar produksi olahan tangan warga lokal yang telah menjadi kebanggaan dan
                favorit pelanggan Lampung Tengah sejak bertahun-tahun.
            </p>
        </div>
        <div class="row g-4 reveal-s">
            {{-- 1. Kelanting --}}
            @php $pK = $produkIkonik['kelanting'] ?? null; @endphp
            <div class="col-md-4">
                <div class="icon-card">
                    @if($pK && $pK->gambar && file_exists(public_path('uploads/produk/' . $pK->gambar)))
                        <img src="{{ asset('uploads/produk/' . $pK->gambar) }}" alt="{{ $pK->merk }}" class="icon-card-img">
                    @else
                        <div class="emoji-ring er-gold">🥨</div>
                    @endif
                    <span class="badge rounded-pill fw-bold mb-2 px-3"
                          style="background:#FFF3D0; color:#7C3D12;">⭐ Favorit Gurih</span>
                    <h4 class="fw-bold mb-2 text-center" style="color:#2D1206; font-size:1.15rem;">
                        {{ $pK->merk ?? 'Kelanting Bumbu Bawang Gurih' }}
                    </h4>
                    <p class="text-muted small mb-3 text-center" style="line-height:1.65;">
                        {{ \Illuminate\Support\Str::limit($pK->deskripsi ?? 'Olahan pati singkong berkualitas dengan racikan bumbu bawang putih & rempah alami pilihan, digoreng hingga renyah garing sempurna.', 110) }}
                    </p>
                    <div class="pt-3 border-top w-100 mt-auto text-center">
                        <div class="fw-bold mb-2" style="color:#C8900A; font-size:1.1rem;">
                            Rp {{ number_format($pK->harga ?? 15000, 0, ',', '.') }}
                        </div>
                        @if($pK)
                            <a href="{{ route('katalog.detail', $pK->id_produk) }}"
                               class="btn btn-sm w-100 fw-bold"
                               style="background:#FFF3D0; color:#7C3D12; border:1.5px solid #F0D090; border-radius:.6rem; padding:.5rem;">
                                <i class="bi bi-eye me-1"></i>Lihat Detail Produk
                            </a>
                        @else
                            <a href="{{ route('katalog.index', ['cari' => 'kelanting']) }}"
                               class="btn btn-sm w-100 fw-bold"
                               style="background:#FFF3D0; color:#7C3D12; border:1.5px solid #F0D090; border-radius:.6rem; padding:.5rem;">
                                <i class="bi bi-search me-1"></i>Lihat Produk Kelanting
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Untir-Untir --}}
            @php $pU = $produkIkonik['untir'] ?? null; @endphp
            <div class="col-md-4">
                <div class="icon-card">
                    @if($pU && $pU->gambar && file_exists(public_path('uploads/produk/' . $pU->gambar)))
                        <img src="{{ asset('uploads/produk/' . $pU->gambar) }}" alt="{{ $pU->merk }}" class="icon-card-img">
                    @else
                        <div class="emoji-ring er-brown">🍩</div>
                    @endif
                    <span class="badge rounded-pill fw-bold mb-2 px-3"
                          style="background:#FAF0E0; color:#4A1E0A;">🍬 Manis Gurih</span>
                    <h4 class="fw-bold mb-2 text-center" style="color:#2D1206; font-size:1.15rem;">
                        {{ $pU->merk ?? 'Untir-Untir Manis Wijen Renyah' }}
                    </h4>
                    <p class="text-muted small mb-3 text-center" style="line-height:1.65;">
                        {{ \Illuminate\Support\Str::limit($pU->deskripsi ?? 'Kue tambang legendaris untir-untir goreng dengan rasa manis gurih dan tekstur renyah empuk yang pas.', 110) }}
                    </p>
                    <div class="pt-3 border-top w-100 mt-auto text-center">
                        <div class="fw-bold mb-2" style="color:#C8900A; font-size:1.1rem;">
                            Rp {{ number_format($pU->harga ?? 12000, 0, ',', '.') }}
                        </div>
                        @if($pU)
                            <a href="{{ route('katalog.detail', $pU->id_produk) }}"
                               class="btn btn-sm w-100 fw-bold"
                               style="background:#FAF0E0; color:#4A1E0A; border:1.5px solid #E0C090; border-radius:.6rem; padding:.5rem;">
                                <i class="bi bi-eye me-1"></i>Lihat Detail Produk
                            </a>
                        @else
                            <a href="{{ route('katalog.index', ['cari' => 'untir']) }}"
                               class="btn btn-sm w-100 fw-bold"
                               style="background:#FAF0E0; color:#4A1E0A; border:1.5px solid #E0C090; border-radius:.6rem; padding:.5rem;">
                                <i class="bi bi-search me-1"></i>Lihat Produk Untir-untir
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. Keripik Pisang --}}
            @php $pP = $produkIkonik['pisang'] ?? null; @endphp
            <div class="col-md-4">
                <div class="icon-card">
                    @if($pP && $pP->gambar && file_exists(public_path('uploads/produk/' . $pP->gambar)))
                        <img src="{{ asset('uploads/produk/' . $pP->gambar) }}" alt="{{ $pP->merk }}" class="icon-card-img">
                    @else
                        <div class="emoji-ring er-brown">🍌</div>
                    @endif
                    <span class="badge rounded-pill fw-bold mb-2 px-3"
                          style="background:#FFF3D0; color:#7C3D12;">🌿 Renyah Alami</span>
                    <h4 class="fw-bold mb-2 text-center" style="color:#2D1206; font-size:1.15rem;">
                        {{ $pP->merk ?? 'Keripik Pisang Cokelat Khas Lampung' }}
                    </h4>
                    <p class="text-muted small mb-3 text-center" style="line-height:1.65;">
                        {{ \Illuminate\Support\Str::limit($pP->deskripsi ?? 'Potongan pisang pilihan khas Lampung dengan balutan bumbu gurih krispy yang menggugah selera.', 110) }}
                    </p>
                    <div class="pt-3 border-top w-100 mt-auto text-center">
                        <div class="fw-bold mb-2" style="color:#C8900A; font-size:1.1rem;">
                            Rp {{ number_format($pP->harga ?? 13000, 0, ',', '.') }}
                        </div>
                        @if($pP)
                            <a href="{{ route('katalog.detail', $pP->id_produk) }}"
                               class="btn btn-sm w-100 fw-bold"
                               style="background:#FFF3D0; color:#7C3D12; border:1.5px solid #F0D090; border-radius:.6rem; padding:.5rem;">
                                <i class="bi bi-eye me-1"></i>Lihat Detail Produk
                            </a>
                        @else
                            <a href="{{ route('katalog.index', ['cari' => 'pisang']) }}"
                               class="btn btn-sm w-100 fw-bold"
                               style="background:#FFF3D0; color:#7C3D12; border:1.5px solid #F0D090; border-radius:.6rem; padding:.5rem;">
                                <i class="bi bi-search me-1"></i>Lihat Produk Keripik Pisang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== PRODUK TERSEDIA GRID ==================== --}}
<section class="produk-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 reveal">
            <div>
                <div class="sec-label">🛒 Siap Dipesan</div>
                <h2 class="sec-title mb-1">Produk <span class="ul-gold">Tersedia</span> Hari Ini</h2>
                <p class="text-muted mb-0">Stok terkini, pesan online langsung tanpa perlu datang ke toko.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('katalog.index') }}" class="btn-gold text-decoration-none"
                   style="border-radius:.75rem; padding:.7rem 1.6rem;">
                    Lihat Semua Katalog <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        @php
        $emojiMap = [
            'kelanting'=>'🥨','untir'=>'🍩','singkong'=>'🥔','keripik'=>'🥔',
            'pisang'=>'🍌','peyek'=>'🥜','emping'=>'🫘','tempe'=>'🌶️',
            'kacang'=>'🥜','rengginang'=>'🍘','kerupuk'=>'🧆',
            'ubi'=>'🍠','talas'=>'🌿','jagung'=>'🌽','ikan'=>'🐟',
        ];
        @endphp

        <div class="row g-4 reveal-s">
            @forelse($produkUnggulan as $i => $p)
                @php
                    $nama  = strtolower($p->merk ?? '');
                    $emoji = '🍿';
                    foreach ($emojiMap as $key => $em) {
                        if (str_contains($nama, $key)) { $emoji = $em; break; }
                    }
                    $isLow = ($p->stok ?? 10) <= 5;
                @endphp
                <div class="col-xl-4 col-md-6">
                    <div class="produk-card">
                        <div class="produk-img-wrap">
                            @if($p->gambar && file_exists(public_path('uploads/produk/' . $p->gambar)))
                                <img src="{{ asset('uploads/produk/' . $p->gambar) }}"
                                     alt="{{ $p->merk }}" loading="lazy">
                            @else
                                <div class="produk-emoji-fallback">{{ $emoji }}</div>
                            @endif
                            <div class="stok-badge {{ $isLow ? 'low' : '' }}">
                                <i class="bi bi-box-seam me-1"></i>Stok: {{ $p->stok }}
                            </div>
                            @if($i < 3)
                                <div class="hot-badge">🔥 Terlaris</div>
                            @endif
                        </div>
                        <div class="produk-body">
                            <h5 class="produk-name">{{ $emoji }} {{ $p->merk }}</h5>
                            <p class="produk-desc">
                                {{ \Illuminate\Support\Str::limit($p->deskripsi ?? 'Camilan khas Dusun 01 Purwodadi, lezat dan tanpa pengawet.', 75) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <div class="produk-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                                    <div class="produk-price-sub">per kemasan</div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt-fill me-1" style="color:#C8900A;"></i>Purwodadi
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('katalog.detail', $p->id_produk) }}"
                                   class="btn-detail" style="flex: 0 0 42%;">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                                <form action="{{ route('keranjang.tambah') }}" method="POST" style="flex:1;">
                                    @csrf
                                    <input type="hidden" name="id_produk" value="{{ $p->id_produk }}">
                                    <input type="hidden" name="jumlah"    value="1">
                                    <button type="submit" class="btn-cart">
                                        <i class="bi bi-cart-plus me-1"></i>+ Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div style="font-size:4rem;">🍿</div>
                    <p class="text-muted mt-3">Belum ada produk terdaftar. Silakan hubungi admin.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ==================== WHY CHOOSE US — RATTAN DARK ==================== --}}
<section class="why-section">
    <div class="container" style="position:relative; z-index:1;">
        <div class="text-center mb-5 reveal">
            <div class="sec-label" style="background:rgba(255,243,208,.12);color:#FFD166;border-color:rgba(229,168,18,.3);">
                💡 Kenapa Pilih Kami?
            </div>
            <h2 class="sec-title" style="color:#FFFDF5;">
                Jaminan <span style="color:#FFD166; text-decoration:underline; text-underline-offset:6px;">Kualitas</span> Terbaik
            </h2>
            <p style="color:rgba(255,253,245,.72); max-width:580px; margin:.6rem auto 0; font-size:.95rem;">
                Kami berkomitmen menghadirkan camilan berkualitas yang aman, lezat, &amp;
                diproduksi dengan standar kebersihan tinggi.
            </p>
        </div>
        <div class="row g-4 reveal-s">
            <div class="col-md-3 col-sm-6">
                <div class="why-card">
                    <span class="why-icon">✅</span>
                    <div class="why-title">100% Halal</div>
                    <div class="why-desc">Semua produk menggunakan bahan halal tanpa campuran bahan meragukan.</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="why-card">
                    <span class="why-icon">🌿</span>
                    <div class="why-title">Tanpa Pengawet</div>
                    <div class="why-desc">Dibuat dari bahan segar alami pilihan petani lokal Trimurjo, bebas kimia tambahan.</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="why-card">
                    <span class="why-icon">💰</span>
                    <div class="why-title">Harga Terjangkau</div>
                    <div class="why-desc">Harga langsung dari produsen, lebih hemat dibanding membeli di minimarket.</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="why-card">
                    <span class="why-icon">📦</span>
                    <div class="why-title">Dikemas Higienis</div>
                    <div class="why-desc">Dikemas rapi menggunakan plastik food-grade yang bersih dan aman.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== CARA PEMESANAN ==================== --}}
<section class="steps-section">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="sec-label">📋 Panduan Pemesanan</div>
            <h2 class="sec-title">Cara <span class="ul-gold">Pesan</span> Online</h2>
            <p class="text-muted mt-2" style="max-width:580px; margin:0 auto; font-size:.95rem;">
                Hanya 4 langkah mudah untuk menikmati camilan khas Purwodadi sampai di pintu rumahmu!
            </p>
        </div>
        <div class="row g-3 g-md-0 align-items-center reveal">
            <div class="col-md-3 col-sm-6">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <span class="step-emoji">🔍</span>
                    <div class="step-title">Pilih Camilan</div>
                    <div class="step-desc">Jelajahi katalog online dengan live search instan &amp; filter kategori produk.</div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center" style="padding-top:2rem;">
                <i class="bi bi-arrow-right-circle-fill" style="color:#C8900A; font-size:1.9rem;"></i>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="step-card">
                    <div class="step-num">2</div>
                    <span class="step-emoji">🛒</span>
                    <div class="step-title">Isi Keranjang</div>
                    <div class="step-desc">Pilih jumlah produk yang diinginkan, lakukan checkout multi-item sekaligus.</div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center" style="padding-top:2rem;">
                <i class="bi bi-arrow-right-circle-fill" style="color:#C8900A; font-size:1.9rem;"></i>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="step-card">
                    <div class="step-num">3</div>
                    <span class="step-emoji">📍</span>
                    <div class="step-title">Isi Alamat</div>
                    <div class="step-desc">Lengkapi nama, nomor WhatsApp aktif, dan alamat pengiriman pesananmu.</div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center" style="padding-top:2rem;">
                <i class="bi bi-arrow-right-circle-fill" style="color:#C8900A; font-size:1.9rem;"></i>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="step-card">
                    <div class="step-num">4</div>
                    <span class="step-emoji">💳</span>
                    <div class="step-title">Bayar Praktis</div>
                    <div class="step-desc">QRIS / Virtual Account / Transfer Bank — pesanan langsung diproses!</div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5 reveal">
            <a href="{{ route('katalog.index') }}" class="btn-gold text-decoration-none"
               style="border-radius:.85rem; padding:1rem 2.5rem; font-size:1.05rem;">
                <i class="bi bi-bag-heart-fill me-2"></i>Mulai Belanja Sekarang!
            </a>
        </div>
    </div>
</section>

{{-- ==================== TRUST BADGES ==================== --}}
<section class="trust-section">
    <div class="container">
        <div class="text-center mb-4 reveal">
            <div class="sec-label">🏅 Kepercayaan Pelanggan</div>
            <h2 class="sec-title">Sudah <span class="ul-gold">Dipercaya</span> Ratusan Pembeli</h2>
        </div>
        <div class="row g-3 justify-content-center reveal-s">
            <div class="col-6 col-md-2">
                <div class="trust-card">
                    <span class="trust-icon">🏅</span>
                    <div class="trust-title">UMKM Terpercaya</div>
                    <div class="trust-sub">Sejak 2010</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="trust-card">
                    <span class="trust-icon">✅</span>
                    <div class="trust-title">Produk Halal</div>
                    <div class="trust-sub">100% Terjamin</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="trust-card">
                    <span class="trust-icon">📱</span>
                    <div class="trust-title">QRIS Ready</div>
                    <div class="trust-sub">Bayar Instan</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="trust-card">
                    <span class="trust-icon">🌿</span>
                    <div class="trust-title">Natural Food</div>
                    <div class="trust-sub">Tanpa Pengawet</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="trust-card">
                    <span class="trust-icon">🚚</span>
                    <div class="trust-title">Siap Kirim</div>
                    <div class="trust-sub">Ke Seluruh Indonesia</div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="trust-card">
                    <span class="trust-icon">💬</span>
                    <div class="trust-title">CS Responsif</div>
                    <div class="trust-sub">Chat via WhatsApp</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== CTA BANNER ==================== --}}
<section class="py-5">
    <div class="container">
        <div class="cta-banner reveal">
            <div class="cta-deco">🥨🍩🍌🥔🥜</div>
            <div class="row align-items-center g-4" style="position:relative; z-index:2;">
                <div class="col-lg-8">
                    <div class="mb-2">
                        <span class="badge fw-bold px-3 py-2 rounded-pill"
                              style="background:rgba(200, 144, 10, 0.25); border:1px solid rgba(255, 209, 102, 0.4); color:#FFD166; font-size:.8rem;">
                            🎉 Harga Spesial Pembelian Partai / Grosir
                        </span>
                    </div>
                    <h2 class="fw-900 mb-2" style="font-size:1.9rem; font-weight:900;">
                        Mau Pesan dalam Jumlah Besar?
                    </h2>
                    <p class="mb-0" style="color:rgba(255,255,255,.82); font-size:.97rem; line-height:1.65;">
                        Kami melayani pemesanan grosir untuk oleh-oleh pesta, hajatan, reseller toko, &amp;
                        pengiriman luar kota. Dapatkan harga spesial langsung dari produsen!
                    </p>
                </div>
                <div class="col-lg-4 d-flex flex-column gap-3 align-items-lg-end">
                    @php
                        $waRaw = $infoweb->telp ?? '081377889900';
                        $waNum = preg_replace('/[^0-9]/', '', $waRaw);
                        if (str_starts_with($waNum, '0')) { $waNum = '62' . substr($waNum, 1); }
                    @endphp
                    <a href="https://wa.me/{{ $waNum }}?text=Halo%20UMKM%20Purwodadi,%20saya%20ingin%20pesan%20camilan%20dalam%20jumlah%20banyak!"
                       target="_blank" class="btn-wa">
                        <i class="bi bi-whatsapp"></i> Chat WhatsApp
                    </a>
                    <a href="{{ route('katalog.index') }}" class="btn-katalog-cta">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Lihat Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// --- Scroll Reveal ---
(function(){
    var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); }
        });
    },{ threshold:0.1, rootMargin:'0px 0px -40px 0px' });
    document.querySelectorAll('.reveal,.reveal-s').forEach(function(el){ io.observe(el); });
})();

// --- Cart flash feedback ---
document.querySelectorAll('form[action*="keranjang"]').forEach(function(form){
    form.addEventListener('submit', function(){
        var btn = form.querySelector('button[type="submit"]');
        if(!btn) return;
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Ditambahkan!';
        btn.disabled = true;
        setTimeout(function(){ btn.innerHTML=orig; btn.disabled=false; }, 1800);
    });
});
</script>
@endpush
