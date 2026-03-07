@extends('layouts.app')

@section('title', 'Détails de la catégorie : ' . $category->name . ' — QuincaApp')

@section('styles')
<style>
    :root {
        --orange:        #f97316;
        --orange-dark:   #ea580c;
        --orange-pale:   #fff7ed;
        --orange-soft:   #fed7aa;
        --bg:            #f1f5f9;
        --card:          #ffffff;
        --border:        #e2e8f0;
        --border-light:  #f1f5f9;
        --text:          #0f172a;
        --text-2:        #475569;
        --text-3:        #94a3b8;
        --success:       #16a34a;
        --danger:        #dc2626;
        --info:          #2563eb;
        --purple:        #7c3aed;
        --pink:          #db2777;
        --indigo:        #6366f1;
        --yellow:        #eab308;
        --shadow-sm:     0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
        --shadow-md:     0 4px 16px rgba(15,23,42,.08);
        --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
        --radius:        20px;
        --radius-sm:     12px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: var(--bg);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
    }

    /* Animations */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Page */
    .scd-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .scd-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .scd-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .scd-hex {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-orange);
    }
    .scd-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .scd-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .scd-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .scd-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
    }

    .scd-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: var(--orange-pale);
        border: 1px solid var(--orange-soft);
        border-radius: 40px;
        font-size: 12px;
        color: var(--orange);
        margin-left: 8px;
    }

    /* Boutons */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border: none;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        cursor: pointer;
        box-shadow: var(--shadow-orange);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .btn-primary svg {
        width: 16px;
        height: 16px;
        stroke: #fff;
        fill: none;
    }
    .btn-primary::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s;
    }
    .btn-primary:hover::after { transform: translateX(100%); }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(249,115,22,0.4);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-secondary svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .btn-secondary:hover {
        border-color: var(--orange);
        color: var(--orange);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: transparent;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-outline svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }
    .btn-outline:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: #fee2e2;
        border: 1.5px solid #fecaca;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        color: var(--danger);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-danger svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }
    .btn-danger:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.3);
    }

    /* Card */
    .scd-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .scd-card:hover {
        border-color: var(--orange-soft);
    }

    .scd-card-header {
        padding: 18px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .scd-card-header-l {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .scd-card-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--orange-pale);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .scd-card-ico svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        fill: none;
    }
    .scd-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }
    .scd-card-badge {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        background: #f1f5f9;
        border-radius: 100px;
        padding: 4px 12px;
    }

    .scd-card-body {
        padding: 24px;
    }

    /* Category Header */
    .scd-category-header {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-radius: var(--radius);
        padding: 32px;
        margin-bottom: 28px;
        color: white;
        position: relative;
        overflow: hidden;
        animation: fadeUp 0.35s ease both;
    }

    .scd-category-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .scd-category-header-content {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        z-index: 2;
    }

    .scd-category-info {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .scd-category-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .scd-category-icon svg {
        width: 40px;
        height: 40px;
        stroke: white;
        fill: none;
    }

    .scd-category-name {
        font-size: 32px;
        font-weight: 800;
        color: white;
        margin-bottom: 4px;
    }

    .scd-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
    }

    .scd-category-desc {
        font-size: 14px;
        color: rgba(255,255,255,0.9);
        max-width: 500px;
    }

    .scd-category-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .scd-category-meta {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 32px;
    }
    @media (max-width: 700px) {
        .scd-category-meta { grid-template-columns: repeat(2, 1fr); }
    }

    .scd-meta-item {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(4px);
        border-radius: var(--radius-sm);
        padding: 16px;
    }
    .scd-meta-label {
        font-size: 11px;
        font-weight: 600;
        color: rgba(255,255,255,0.7);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .scd-meta-value {
        font-size: 18px;
        font-weight: 700;
        color: white;
    }

    /* Hierarchy */
    .scd-hierarchy {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .scd-hierarchy svg {
        width: 16px;
        height: 16px;
        stroke: var(--text-3);
    }
    .scd-hierarchy a {
        color: var(--info);
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s;
    }
    .scd-hierarchy a:hover {
        color: var(--orange);
        text-decoration: underline;
    }
    .scd-hierarchy span {
        color: var(--text-2);
        font-size: 13px;
    }
    .scd-hierarchy strong {
        color: var(--text);
        font-weight: 600;
    }

    /* Stats Cards */
    .scd-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .scd-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .scd-stats { grid-template-columns: 1fr; } }

    .scd-stat {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 22px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
        animation: fadeUp 0.35s ease both;
    }
    .scd-stat:nth-child(2) { animation-delay:0.07s; }
    .scd-stat:nth-child(3) { animation-delay:0.14s; }
    .scd-stat:nth-child(4) { animation-delay:0.21s; }
    .scd-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .scd-stat::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
    }
    .scd-stat.c-a::before { background: var(--info); }
    .scd-stat.c-b::before { background: var(--purple); }
    .scd-stat.c-c::before { background: var(--success); }
    .scd-stat.c-d::before { background: var(--yellow); }

    .scd-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .scd-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-3);
    }
    .scd-stat-ico {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .c-a .scd-stat-ico { background: #eff6ff; color: var(--info); }
    .c-b .scd-stat-ico { background: #f5f3ff; color: var(--purple); }
    .c-c .scd-stat-ico { background: #f0fdf4; color: var(--success); }
    .c-d .scd-stat-ico { background: #fef3c7; color: var(--yellow); }
    .scd-stat:hover .scd-stat-ico {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .scd-stat-ico svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
    }
    .scd-stat-val {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 2px;
        color: var(--text);
    }
    .scd-stat-unit {
        font-size: 12px;
        color: var(--text-3);
    }

    /* Alert Cards */
    .scd-alert-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 700px) {
        .scd-alert-grid { grid-template-columns: 1fr; }
    }

    .scd-alert-card {
        border-radius: var(--radius-sm);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-left: 4px solid;
    }
    .scd-alert-danger {
        background: #fef2f2;
        border-color: var(--danger);
    }
    .scd-alert-warning {
        background: #fef3c7;
        border-color: var(--yellow);
    }
    .scd-alert-success {
        background: #f0fdf4;
        border-color: var(--success);
    }
    .scd-alert-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .scd-alert-icon svg {
        width: 24px;
        height: 24px;
        stroke: currentColor;
        fill: none;
    }
    .scd-alert-content h4 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .scd-alert-content .value {
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
    }
    .scd-alert-content .unit {
        font-size: 11px;
        font-weight: 500;
    }

    /* Product Grid */
    .scd-product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 900px) {
        .scd-product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 500px) {
        .scd-product-grid { grid-template-columns: 1fr; }
    }

    .scd-product-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 20px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .scd-product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .scd-product-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
    }
    .scd-product-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }
    .scd-product-info {
        flex: 1;
    }
    .scd-product-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 2px;
    }
    .scd-product-id {
        font-size: 11px;
        color: var(--text-3);
    }

    .scd-stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        margin-left: auto;
    }
    .stock-ok {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .stock-low {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
    }
    .stock-empty {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .scd-product-prices {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }
    .scd-price-label {
        font-size: 10px;
        color: var(--text-3);
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .scd-price-value {
        font-size: 14px;
        font-weight: 600;
    }
    .price-purchase { color: var(--text-2); }
    .price-sale { color: var(--success); }

    .scd-product-desc {
        font-size: 12px;
        color: var(--text-2);
        margin-bottom: 12px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .scd-product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border-light);
    }
    .scd-product-link {
        font-size: 12px;
        font-weight: 600;
        color: var(--info);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .scd-product-date {
        font-size: 10px;
        color: var(--text-3);
    }

    /* Subcategories Grid */
    .scd-subcat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 900px) {
        .scd-subcat-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 500px) {
        .scd-subcat-grid { grid-template-columns: 1fr; }
    }

    .scd-subcat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 16px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .scd-subcat-card:hover {
        transform: translateY(-2px);
        border-color: var(--purple);
        box-shadow: var(--shadow-sm);
    }

    .scd-subcat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .scd-subcat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--purple), #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .scd-subcat-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }
    .scd-subcat-count {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 2px;
    }

    .scd-subcat-desc {
        font-size: 12px;
        color: var(--text-2);
        margin-bottom: 12px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .scd-subcat-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
    }
    .scd-subcat-link {
        color: var(--purple);
        font-weight: 600;
    }
    .scd-subcat-date {
        color: var(--text-3);
    }

    /* Tableau faible stock */
    .scd-table {
        width: 100%;
        border-collapse: collapse;
    }
    .scd-table thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-2);
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .scd-table tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
    }
    .scd-table tbody tr:hover td {
        background: var(--orange-pale);
    }
    .scd-table .btn-small {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-restock {
        background: #eff6ff;
        color: var(--info);
        border: 1px solid #bfdbfe;
    }
    .btn-view {
        background: #f1f5f9;
        color: var(--text-2);
        border: 1px solid var(--border);
    }

    /* Info grid */
    .scd-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    @media (max-width: 700px) {
        .scd-info-grid { grid-template-columns: 1fr; }
    }

    .scd-info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
    }
    .scd-info-label {
        font-size: 13px;
        color: var(--text-3);
    }
    .scd-info-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    /* History */
    .scd-history-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #fafbfd;
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
    }
    .scd-history-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #eff6ff;
        color: var(--info);
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="scd-page">

    {{-- HEADER --}}
    <div class="scd-header">
        <div class="scd-header-l">
            <div class="scd-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="scd-title">
                    {{ $category->name }} <span>#{{ $category->id }}</span>
                </div>
                <div class="scd-sub">Détails complets de la catégorie</div>
            </div>
        </div>
        <a href="{{ route('categories.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux catégories
        </a>
    </div>

    {{-- HIERARCHY PATH --}}
    @if($category->parent || $category->children->count() > 0)
        <div class="scd-hierarchy">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            <a href="{{ route('categories.index') }}">Catégories</a>
            
            @if($category->parent)
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('categories.show', $category->parent->id) }}">{{ $category->parent->name }}</a>
            @endif
            
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <strong>{{ $category->name }}</strong>
            
            @if($category->children->count() > 0)
                <span class="scd-card-badge" style="margin-left:auto;">
                    {{ $category->children->count() }} sous-catégorie(s)
                </span>
            @endif
        </div>
    @endif

    {{-- CATEGORY HEADER --}}
    <div class="scd-category-header">
        <div class="scd-category-header-content">
            <div class="scd-category-info">
                <div class="scd-category-icon">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h1 class="scd-category-name">{{ $category->name }}</h1>
                    @if($category->parent)
                        <span class="scd-category-badge">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            Sous-catégorie de {{ $category->parent->name }}
                        </span>
                    @else
                        <span class="scd-category-badge">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1" />
                            </svg>
                            Catégorie principale
                        </span>
                    @endif
                    @if($category->description)
                        <p class="scd-category-desc">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
            
            <div class="scd-category-actions">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn-outline" style="background:rgba(255,255,255,0.2); border-color:rgba(255,255,255,0.3); color:white;">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                    
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" 
                          onsubmit="return confirmDelete()" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="background:rgba(220,38,38,0.3); border-color:rgba(220,38,38,0.5); color:white;">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- META INFO --}}
        <div class="scd-category-meta">
            <div class="scd-meta-item">
                <div class="scd-meta-label">ID Catégorie</div>
                <div class="scd-meta-value">#{{ $category->id }}</div>
            </div>
            <div class="scd-meta-item">
                <div class="scd-meta-label">Date création</div>
                <div class="scd-meta-value">{{ $category->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="scd-meta-item">
                <div class="scd-meta-label">Dernière mise à jour</div>
                <div class="scd-meta-value">{{ $category->updated_at->format('d/m/Y') }}</div>
            </div>
            <div class="scd-meta-item">
                <div class="scd-meta-label">Statut</div>
                <div class="scd-meta-value">
                    @if($stats['total_products'] > 0)
                        Active ({{ $stats['total_products'] }} produits)
                    @else
                        Vide
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="scd-stats">
        <div class="scd-stat c-a">
            <div class="scd-stat-top">
                <span class="scd-stat-label">Total Produits</span>
                <div class="scd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="scd-stat-val">{{ $stats['total_products'] ?? 0 }}</div>
        </div>

        <div class="scd-stat c-b">
            <div class="scd-stat-top">
                <span class="scd-stat-label">Sous-catégories</span>
                <div class="scd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="scd-stat-val">{{ $stats['total_subcategories'] ?? 0 }}</div>
        </div>

        <div class="scd-stat c-c">
            <div class="scd-stat-top">
                <span class="scd-stat-label">Valeur Stock</span>
                <div class="scd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="scd-stat-val">{{ number_format($stats['total_value'] ?? 0, 0, ',', ' ') }}</div>
            <div class="scd-stat-unit">CFA</div>
        </div>

        <div class="scd-stat c-d">
            <div class="scd-stat-top">
                <span class="scd-stat-label">Revenu Potentiel</span>
                <div class="scd-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="scd-stat-val">{{ number_format($stats['potential_revenue'] ?? 0, 0, ',', ' ') }}</div>
            <div class="scd-stat-unit">CFA</div>
        </div>
    </div>

    {{-- ALERT CARDS --}}
    @if($stats['out_of_stock'] > 0 || $stats['low_stock'] > 0)
        <div class="scd-alert-grid">
            @if($stats['out_of_stock'] > 0)
                <div class="scd-alert-card scd-alert-danger">
                    <div class="scd-alert-icon" style="background:#fee2e2; color:var(--danger);">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="scd-alert-content">
                        <h4 style="color:var(--danger);">Produits en Rupture</h4>
                        <div class="value" style="color:var(--danger);">{{ $stats['out_of_stock'] }}</div>
                        <div class="unit">À réapprovisionner</div>
                    </div>
                </div>
            @endif

            @if($stats['low_stock'] > 0)
                <div class="scd-alert-card scd-alert-warning">
                    <div class="scd-alert-icon" style="background:#fef3c7; color:var(--yellow);">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="scd-alert-content">
                        <h4 style="color:#854d0e;">Stock Faible</h4>
                        <div class="value" style="color:#854d0e;">{{ $stats['low_stock'] }}</div>
                        <div class="unit">Stock ≤ 5</div>
                    </div>
                </div>
            @endif

            @if($stats['in_stock'] > 0)
                <div class="scd-alert-card scd-alert-success">
                    <div class="scd-alert-icon" style="background:#f0fdf4; color:var(--success);">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="scd-alert-content">
                        <h4 style="color:#166534;">Stock Suffisant</h4>
                        <div class="value" style="color:#166534;">{{ $stats['in_stock'] }}</div>
                        <div class="unit">Stock > 5</div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- SUBCATEGORIES --}}
    @if($category->children->count() > 0)
        <div class="scd-card">
            <div class="scd-card-header">
                <div class="scd-card-header-l">
                    <div class="scd-card-ico">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="scd-card-title">Sous-catégories</span>
                </div>
                <span class="scd-card-badge">{{ $category->children->count() }} sous-catégorie(s)</span>
            </div>
            <div class="scd-card-body">
                <div class="scd-subcat-grid">
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->id) }}" class="scd-subcat-card">
                            <div class="scd-subcat-header">
                                <div class="scd-subcat-icon">
                                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="scd-subcat-name">{{ $child->name }}</div>
                                    <div class="scd-subcat-count">{{ $child->products_count ?? 0 }} produits</div>
                                </div>
                            </div>
                            @if($child->description)
                                <div class="scd-subcat-desc">{{ Str::limit($child->description, 80) }}</div>
                            @endif
                            <div class="scd-subcat-footer">
                                <span class="scd-subcat-link">Voir détails →</span>
                                <span class="scd-subcat-date">{{ $child->created_at->format('d/m/Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- PRODUCTS --}}
    <div class="scd-card">
        <div class="scd-card-header">
            <div class="scd-card-header-l">
                <div class="scd-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="scd-card-title">Produits de cette catégorie</span>
            </div>
            <div style="display:flex; gap:8px;">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="btn-outline">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter
                    </a>
                @endif
                <a href="{{ route('products.index') }}?category_id={{ $category->id }}" class="btn-outline">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Voir tous
                </a>
            </div>
        </div>
        <div class="scd-card-body">
            @if($category->products->count() > 0)
                <div class="scd-product-grid">
                    @foreach($category->products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="scd-product-card">
                            <div class="scd-product-header">
                                <div class="scd-product-avatar">
                                    {{ substr($product->name, 0, 1) }}
                                </div>
                                <div class="scd-product-info">
                                    <div class="scd-product-name">{{ $product->name }}</div>
                                    <div class="scd-product-id">#{{ $product->id }}</div>
                                </div>
                                <span class="scd-stock-badge {{ $product->stock > 10 ? 'stock-ok' : ($product->stock > 0 ? 'stock-low' : 'stock-empty') }}">
                                    {{ $product->stock }}
                                </span>
                            </div>

                            <div class="scd-product-prices">
                                <div>
                                    <div class="scd-price-label">Achat</div>
                                    <div class="scd-price-value price-purchase">{{ number_format($product->purchase_price, 0, ',', ' ') }} CFA</div>
                                </div>
                                <div>
                                    <div class="scd-price-label">Vente</div>
                                    <div class="scd-price-value price-sale">{{ number_format($product->sale_price, 0, ',', ' ') }} CFA</div>
                                </div>
                            </div>

                            @if($product->description)
                                <div class="scd-product-desc">{{ Str::limit($product->description, 80) }}</div>
                            @endif

                            <div class="scd-product-footer">
                                <span class="scd-product-link">
                                    Voir détails
                                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                                <span class="scd-product-date">{{ $product->created_at->format('d/m/Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="scd-empty">
                    <div class="scd-empty-ico">
                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3>Aucun produit dans cette catégorie</h3>
                    <p>Cette catégorie ne contient pas encore de produits.</p>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="btn-primary">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un produit
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- LOW STOCK TABLE --}}
    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
        <div class="scd-card" style="border-color:var(--danger-soft);">
            <div class="scd-card-header" style="background:linear-gradient(135deg, #fee2e2, #fecaca);">
                <div class="scd-card-header-l">
                    <div class="scd-card-ico" style="background:rgba(220,38,38,0.2);">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="stroke:var(--danger);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="scd-card-title" style="color:var(--danger);">Produits à faible stock</span>
                </div>
                <span class="scd-card-badge" style="background:#fee2e2; color:var(--danger);">
                    {{ $lowStockProducts->count() }} produit(s)
                </span>
            </div>
            <div class="scd-card-body">
                <div class="scd-table-wrap">
                    <table class="scd-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Stock Actuel</th>
                                <th>Seuil</th>
                                <th>Prix Vente</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <div style="width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg, var(--orange), var(--orange-dark)); color:white; display:flex; align-items:center; justify-content:center; font-weight:700;">
                                                {{ substr($product->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:600;">{{ $product->name }}</div>
                                                <div style="font-size:11px; color:var(--text-3);">#{{ $product->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="scd-stock-badge {{ $product->stock == 0 ? 'stock-empty' : 'stock-low' }}">
                                            {{ $product->stock }} unités
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->stock == 0)
                                            <span style="color:var(--danger); font-weight:600;">RUPTURE</span>
                                        @else
                                            <span style="color:var(--yellow); font-weight:600;">{{ $product->stock }}/5</span>
                                        @endif
                                    </td>
                                    <td style="font-weight:600; color:var(--success);">
                                        {{ number_format($product->sale_price, 0, ',', ' ') }} CFA
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:4px;">
                                            <a href="{{ route('products.restock', $product->id) }}" class="btn-small btn-restock">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Réappro
                                            </a>
                                            <a href="{{ route('products.show', $product->id) }}" class="btn-small btn-view">
                                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- TECHNICAL INFO --}}
    <div class="scd-card">
        <div class="scd-card-header">
            <div class="scd-card-header-l">
                <div class="scd-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="scd-card-title">Informations techniques</span>
            </div>
        </div>
        <div class="scd-card-body">
            <div class="scd-info-grid">
                <div>
                    <h3 style="font-size:14px; font-weight:600; margin-bottom:12px;">Métadonnées</h3>
                    <div class="scd-info-item">
                        <span class="scd-info-label">ID de la catégorie</span>
                        <span class="scd-info-value">#{{ $category->id }}</span>
                    </div>
                    <div class="scd-info-item">
                        <span class="scd-info-label">Type</span>
                        <span class="scd-info-value">
                            @if($category->parent_id)
                                <span style="color:var(--purple);">Sous-catégorie</span>
                            @else
                                <span style="color:var(--success);">Catégorie principale</span>
                            @endif
                        </span>
                    </div>
                    <div class="scd-info-item">
                        <span class="scd-info-label">Date de création</span>
                        <span class="scd-info-value">{{ $category->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="scd-info-item">
                        <span class="scd-info-label">Dernière modification</span>
                        <span class="scd-info-value">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="scd-info-item">
                        <span class="scd-info-label">Produits associés</span>
                        <span class="scd-info-value">{{ $category->products->count() }}</span>
                    </div>
                    @if($category->parent)
                        <div class="scd-info-item">
                            <span class="scd-info-label">Catégorie parente</span>
                            <span class="scd-info-value">
                                <a href="{{ route('categories.show', $category->parent->id) }}" style="color:var(--info); text-decoration:none;">
                                    {{ $category->parent->name }}
                                </a>
                            </span>
                        </div>
                    @endif
                    @if($category->children->count() > 0)
                        <div class="scd-info-item">
                            <span class="scd-info-label">Sous-catégories</span>
                            <span class="scd-info-value">{{ $category->children->count() }}</span>
                        </div>
                    @endif
                </div>
                
                <div>
                    <h3 style="font-size:14px; font-weight:600; margin-bottom:12px;">Historique</h3>
                    <div class="scd-history-item">
                        <div class="scd-history-icon">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:13px;">Création de la catégorie</div>
                            <div style="font-size:11px; color:var(--text-3);">{{ $category->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    
                    @if($category->created_at != $category->updated_at)
                        <div class="scd-history-item">
                            <div class="scd-history-icon" style="background:#f0fdf4; color:var(--success);">
                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:13px;">Dernière modification</div>
                                <div style="font-size:11px; color:var(--text-3);">{{ $category->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    const hasProducts = {{ $category->products->count() }} > 0;
    const hasSubcategories = {{ $category->children->count() }} > 0;
    
    let message = "Êtes-vous sûr de vouloir supprimer cette catégorie ?\n\n";
    
    if (hasProducts) {
        message += "⚠️ Cette catégorie contient {{ $category->products->count() }} produit(s).\n";
    }
    
    if (hasSubcategories) {
        message += "⚠️ Cette catégorie contient {{ $category->children->count() }} sous-catégorie(s).\n";
    }
    
    if (hasProducts || hasSubcategories) {
        message += "\nSi vous la supprimez :\n";
        if (hasProducts) message += "- Les produits seront sans catégorie\n";
        if (hasSubcategories) message += "- Les sous-catégories deviendront des catégories principales\n";
        message += "\nVoulez-vous vraiment continuer ?";
    } else {
        message += "Voulez-vous vraiment continuer ?";
    }
    
    return confirm(message);
}
</script>
@endsection