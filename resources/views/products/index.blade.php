<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('title', 'Produits — QuincaApp')

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
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Page */
    .sp-page {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }

    /* Header */
    .sp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        animation: fadeUp 0.35s ease both;
    }

    .sp-header-l {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sp-hex {
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
    .sp-hex svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }

    .sp-title {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: var(--text);
    }
    .sp-title span {
        color: var(--orange);
        font-weight: 800;
    }
    .sp-sub {
        font-size: 13px;
        color: var(--text-3);
        margin-top: 4px;
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

    /* Alertes */
    .sp-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        animation: fadeUp 0.35s 0.07s ease both;
        border-left: 4px solid;
    }
    .sp-alert svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }
    .sp-alert-success {
        background: #f0fdf4;
        border-color: var(--success);
        color: #166534;
    }
    .sp-alert-info {
        background: #eff6ff;
        border-color: var(--info);
        color: #1e40af;
    }
    .sp-alert-warning {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }
    .sp-alert-error {
        background: #fef2f2;
        border-color: var(--danger);
        color: #991b1b;
    }

    /* Card */
    .sp-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .sp-card:hover {
        border-color: var(--orange-soft);
    }

    .sp-card-header {
        padding: 18px 24px;
        background: #fafbfd;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sp-card-header-l {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sp-card-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: var(--orange-pale);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sp-card-ico svg {
        width: 16px;
        height: 16px;
        stroke: var(--orange);
        fill: none;
    }
    .sp-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }

    .sp-card-body {
        padding: 24px;
    }

    /* Stats Grid */
    .sp-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .sp-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 580px)  { .sp-stats { grid-template-columns: 1fr; } }

    .sp-stat {
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
    .sp-stat:nth-child(2) { animation-delay:0.07s; }
    .sp-stat:nth-child(3) { animation-delay:0.14s; }
    .sp-stat:nth-child(4) { animation-delay:0.21s; }
    .sp-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--orange-soft);
    }

    .sp-stat::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
    }
    .sp-stat.c-a::before { background: var(--info); }
    .sp-stat.c-b::before { background: var(--success); }
    .sp-stat.c-c::before { background: var(--purple); }
    .sp-stat.c-d::before { background: var(--orange); }

    .sp-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .sp-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-3);
    }
    .sp-stat-ico {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .c-a .sp-stat-ico { background: #eff6ff; color: var(--info); }
    .c-b .sp-stat-ico { background: #f0fdf4; color: var(--success); }
    .c-c .sp-stat-ico { background: #f5f3ff; color: var(--purple); }
    .c-d .sp-stat-ico { background: var(--orange-pale); color: var(--orange); }
    .sp-stat:hover .sp-stat-ico {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .sp-stat-ico svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        fill: none;
    }
    .sp-stat-val {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 2px;
        color: var(--text);
    }
    .sp-stat-unit {
        font-size: 12px;
        color: var(--text-3);
    }
    .sp-stat-foot {
        font-size: 11px;
        color: var(--text-3);
        margin-top: 4px;
    }

    /* Search Bar */
    .sp-search {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .sp-search-wrapper {
        flex: 1;
        position: relative;
        min-width: 250px;
    }
    .sp-search-ico {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
    .sp-search-ico svg {
        width: 18px;
        height: 18px;
        stroke: var(--text-3);
        fill: none;
    }
    .sp-search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text);
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
    }
    .sp-search-input:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }
    .sp-search-clear {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-3);
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .sp-search-clear:hover {
        color: var(--danger);
        background: #f1f5f9;
    }

    /* Filter chips */
    .sp-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 16px 0;
    }
    .sp-filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #f1f5f9;
        border: 1px solid var(--border-light);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        text-decoration: none;
        transition: all 0.2s;
    }
    .sp-filter-chip svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
    }
    .sp-filter-chip:hover {
        background: var(--orange-pale);
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .sp-filter-chip.active {
        background: var(--orange-pale);
        border-color: var(--orange);
        color: var(--orange);
        font-weight: 600;
    }

    .sp-filter-select {
        padding: 6px 14px;
        background: #f1f5f9;
        border: 1px solid var(--border-light);
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-2);
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .sp-filter-select:hover {
        background: var(--orange-pale);
        border-color: var(--orange-soft);
        color: var(--orange);
    }

    .sp-filter-info {
        margin-top: 12px;
        padding: 12px 16px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sp-filter-info-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #1e40af;
    }
    .sp-filter-info-left svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }

    /* Table */
    .sp-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
    }
    .sp-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sp-table thead th {
        padding: 14px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #fff;
        background: #1e293b;
        border-bottom: 1px solid #334155;
    }
    .sp-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text-2);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .sp-table tbody tr:last-child td {
        border-bottom: none;
    }
    .sp-table tbody tr {
        transition: background 0.15s;
    }
    .sp-table tbody tr:hover td {
        background: var(--orange-pale);
    }

    /* ID Badge */
    .sp-id {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        color: var(--text-2);
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    tr:hover .sp-id {
        background: var(--orange-pale);
        color: var(--orange);
    }
    .sp-id.cumulated { background: #f5f3ff; color: var(--purple); }
    .sp-id.merged { background: #fce7f3; color: var(--pink); }

    /* Product */
    .sp-product {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sp-product-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }
    .sp-product-avatar.cumulated { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
    .sp-product-avatar.merged { background: linear-gradient(135deg, #f472b6, #db2777); }
    .sp-product-info {
        flex: 1;
    }
    .sp-product-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .sp-product-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 40px;
        font-size: 10px;
        font-weight: 600;
    }
    .badge-cumulated {
        background: #f5f3ff;
        color: var(--purple);
        border: 1px solid #ddd6fe;
    }
    .badge-merged {
        background: #fce7f3;
        color: var(--pink);
        border: 1px solid #fbcfe8;
    }
    .sp-product-ref {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Price */
    .sp-price {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }
    .sp-price-sub {
        font-size: 11px;
        color: var(--text-3);
    }

    /* Stock */
    .sp-stock {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
    }
    .stock-high {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .stock-medium {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
    }
    .stock-low {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Status */
    .sp-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-simple {
        background: #f1f5f9;
        color: var(--text-2);
        border: 1px solid var(--border);
    }
    .status-multiple {
        background: #f5f3ff;
        color: var(--purple);
        border: 1px solid #ddd6fe;
        cursor: pointer;
    }
    .status-cumulated {
        background: #f5f3ff;
        color: var(--purple);
        border: 1px solid #ddd6fe;
    }
    .status-merged {
        background: #fce7f3;
        color: var(--pink);
        border: 1px solid #fbcfe8;
    }

    /* Actions */
    .sp-actions {
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .sp-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        background: var(--card);
    }
    .sp-btn svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
        fill: none;
    }
    .sp-btn:hover {
        transform: scale(1.08);
        box-shadow: var(--shadow-sm);
    }
    .sp-btn-view {
        border-color: #bfdbfe;
        color: var(--info);
    }
    .sp-btn-view:hover {
        background: var(--info);
        border-color: var(--info);
        color: #fff;
    }
    .sp-btn-edit {
        border-color: var(--orange-soft);
        color: var(--orange);
    }
    .sp-btn-edit:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: #fff;
    }
    .sp-btn-delete {
        border-color: #fecaca;
        color: var(--danger);
    }
    .sp-btn-delete:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: #fff;
    }
    .sp-btn-uncumulate {
        border-color: #ddd6fe;
        color: var(--purple);
    }
    .sp-btn-uncumulate:hover {
        background: var(--purple);
        border-color: var(--purple);
        color: #fff;
    }

    /* Empty state */
    .sp-empty {
        padding: 64px 24px;
        text-align: center;
    }
    .sp-empty-ico {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .sp-empty-ico svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
        fill: none;
    }
    .sp-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .sp-empty p {
        font-size: 14px;
        color: var(--text-2);
        margin-bottom: 20px;
    }

    /* Tooltip */
    .sp-tooltip {
        position: relative;
    }
    .sp-tooltip:hover .sp-tooltip-content {
        display: block;
    }
    .sp-tooltip-content {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-bottom: 8px;
        background: #1e293b;
        color: #fff;
        font-size: 11px;
        padding: 8px 12px;
        border-radius: 8px;
        white-space: nowrap;
        z-index: 20;
    }
    .sp-tooltip-content::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: #1e293b transparent transparent transparent;
    }

    /* Pagination */
    .sp-pagination {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 22px;
        box-shadow: var(--shadow-sm);
        margin-top: 20px;
    }
    .sp-pagination nav { width: 100%; }
    .sp-pagination .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        list-style: none;
        flex-wrap: wrap;
    }
    .sp-pagination .page-item .page-link {
        padding: 7px 14px;
        border-radius: 9px;
        border: 1.5px solid var(--border);
        color: var(--text-2);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.18s;
        display: block;
        background: var(--card);
    }
    .sp-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border-color: var(--orange);
        color: #fff;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }
    .sp-pagination .page-item .page-link:hover {
        border-color: var(--orange);
        color: var(--orange);
        background: var(--orange-pale);
    }

    /* Modal */
    .sp-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .sp-modal.show {
        display: flex;
        animation: fadeUp 0.2s ease;
    }
    .sp-modal-content {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        width: 100%;
        max-width: 640px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: slideIn 0.3s ease;
    }
    .sp-modal-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        color: #fff;
        border-radius: var(--radius) var(--radius) 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .sp-modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sp-modal-header h3 svg {
        width: 22px;
        height: 22px;
        stroke: #fff;
        fill: none;
    }
    .sp-modal-close {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .sp-modal-close:hover {
        background: rgba(255,255,255,0.2);
    }
    .sp-modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }
    .sp-modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border);
        background: #fafbfd;
        border-radius: 0 0 var(--radius) var(--radius);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    /* Product selection */
    .sp-product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        max-height: 300px;
        overflow-y: auto;
        padding: 4px;
    }
    .sp-product-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        transition: all 0.2s;
    }
    .sp-product-item:hover {
        background: var(--orange-pale);
        border-color: var(--orange-soft);
    }
    .sp-product-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--orange);
    }
    .sp-product-item label {
        flex: 1;
        cursor: pointer;
    }
    .sp-product-item-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .sp-product-item-desc {
        font-size: 11px;
        color: var(--text-3);
        display: flex;
        gap: 8px;
    }

    .sp-preview {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--radius-sm);
        padding: 16px;
        margin: 16px 0;
    }
    .sp-preview-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .sp-preview-item {
        font-size: 13px;
        color: #166534;
    }
    .sp-preview-item strong {
        font-weight: 700;
        color: #14532d;
    }
</style>
@endsection

@section('content')
<div class="sp-page">

    {{-- HEADER --}}
    <div class="sp-header">
        <div class="sp-header-l">
            <div class="sp-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <div class="sp-title">
                    Gestion des <span>produits</span>
                </div>
                <div class="sp-sub">Gérez votre inventaire et vos produits en toute simplicité</div>
            </div>
        </div>
        <div class="sp-header-actions" style="display: flex; gap: 8px;">
            <button onclick="openMergeModal()" class="btn-outline">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                </svg>
                Fusionner
            </button>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn-primary">
                        <svg viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau produit
                    </a>
                @endif
            @endauth
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="sp-alert sp-alert-success">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="sp-alert sp-alert-info">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('info') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="sp-alert sp-alert-warning">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="sp-alert sp-alert-error">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="sp-stats">
        <div class="sp-stat c-a">
            <div class="sp-stat-top">
                <span class="sp-stat-label">Total produits</span>
                <div class="sp-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="sp-stat-val">{{ $totalProductsGlobal }}</div>
            <div class="sp-stat-foot">{{ $products->total() }} filtrés</div>
        </div>

        <div class="sp-stat c-b">
            <div class="sp-stat-top">
                <span class="sp-stat-label">Stock total</span>
                <div class="sp-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="sp-stat-val">{{ number_format($totalStockGlobal, 0, ',', ' ') }}</div>
            <div class="sp-stat-foot">{{ number_format($totalStockFiltered, 0, ',', ' ') }} filtrés</div>
        </div>

        <div class="sp-stat c-c">
            <div class="sp-stat-top">
                <span class="sp-stat-label">Valeur totale</span>
                <div class="sp-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="sp-stat-val">{{ number_format($totalValueGlobal, 0, ',', ' ') }}</div>
            <div class="sp-stat-foot">CFA</div>
        </div>

        <div class="sp-stat c-d">
            <div class="sp-stat-top">
                <span class="sp-stat-label">Multiples lots</span>
                <div class="sp-stat-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                    </svg>
                </div>
            </div>
            <div class="sp-stat-val">{{ $productsWithMultipleBatches ?? 0 }}</div>
            <div class="sp-stat-foot">produits</div>
        </div>
    </div>

    {{-- SEARCH CARD --}}
    <div class="sp-card">
        <div class="sp-card-header">
            <div class="sp-card-header-l">
                <div class="sp-card-ico">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <span class="sp-card-title">Recherche et filtres</span>
            </div>
        </div>
        <div class="sp-card-body">
            <form action="{{ route('products.index') }}" method="GET" id="searchForm">
                <div class="sp-search">
                    <div class="sp-search-wrapper">
                        <span class="sp-search-ico">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" 
                               name="search" 
                               id="searchInput" 
                               value="{{ request('search', '') }}" 
                               placeholder="Rechercher un produit par nom, prix ou stock..."
                               class="sp-search-input"
                               autocomplete="off">
                        @if(request('search'))
                            <button type="button" id="clearSearch" class="sp-search-clear" onclick="clearSearch()">
                                <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    <button type="submit" class="btn-primary" id="searchButton">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Rechercher
                    </button>
                </div>

                <div class="sp-filters">
                    <a href="{{ route('products.index') }}" 
                       class="sp-filter-chip {{ !request('filter') && !request('search') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Tous
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'low_stock']) }}" 
                       class="sp-filter-chip {{ request('filter') == 'low_stock' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Stock faible
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'out_of_stock']) }}" 
                       class="sp-filter-chip {{ request('filter') == 'out_of_stock' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Rupture
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'available']) }}" 
                       class="sp-filter-chip {{ request('filter') == 'available' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Disponibles
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'multiple_batches']) }}" 
                       class="sp-filter-chip {{ request('filter') == 'multiple_batches' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                        </svg>
                        Multiples lots
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'cumulated']) }}" 
                       class="sp-filter-chip {{ request('filter') == 'cumulated' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        Cumulés
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'non_cumulated']) }}" 
                       class="sp-filter-chip {{ request('filter') == 'non_cumulated' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Simples
                    </a>
                    
                    <select name="sort_by" onchange="this.form.submit()" class="sp-filter-select">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tri par date</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Stock (croissant)</option>
                        <option value="sale_price" {{ request('sort_by') == 'sale_price' ? 'selected' : '' }}>Prix (croissant)</option>
                        <option value="profit_margin" {{ request('sort_by') == 'profit_margin' ? 'selected' : '' }}>Marge %</option>
                    </select>
                </div>

                @if(request('search') || request('filter') || request('sort_by') != 'created_at')
                    <div class="sp-filter-info">
                        <div class="sp-filter-info-left">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>
                                @if(request('search'))
                                    Recherche : "<strong>{{ request('search') }}</strong>" • 
                                @endif
                                @if(request('filter'))
                                    @php
                                        $filterLabels = [
                                            'low_stock' => 'Stock faible',
                                            'out_of_stock' => 'Rupture',
                                            'available' => 'Disponibles',
                                            'multiple_batches' => 'Multiples lots',
                                            'cumulated' => 'Cumulés',
                                            'non_cumulated' => 'Simples'
                                        ];
                                    @endphp
                                    Filtre : <strong>{{ $filterLabels[request('filter')] ?? request('filter') }}</strong> • 
                                @endif
                                <strong>{{ $products->total() }}</strong> résultat(s) trouvé(s)
                            </span>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn-outline" style="padding:4px 12px;">
                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Effacer
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="sp-card" style="margin-bottom:24px;">
        <div class="sp-card-body" style="padding:16px 24px;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size:13px; font-weight:600; color:var(--text-2);">Actions rapides :</span>
                </div>
                <a href="{{ route('reports.grouped-stocks') }}" class="btn-outline">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                    </svg>
                    Stocks groupés
                </a>
                <a href="{{ route('reports.products') }}" class="btn-outline">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Rapport
                </a>
                <button onclick="openMergeModal()" class="btn-outline">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                    </svg>
                    Fusionner
                </button>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('products.create') }}" class="btn-outline">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouveau
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="sp-card">
        <div class="sp-table-wrap">
            <table class="sp-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produit</th>
                        <th>Prix vente</th>
                        @if(Auth::user() && Auth::user()->role === 'admin')
                            <th>Prix achat</th>
                        @endif
                        <th>Stock</th>
                        <th>Valeur stock</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $stock = $product->stock ?? 0;
                            $salePrice = $product->sale_price ?? 0;
                            $purchasePrice = $product->purchase_price ?? 0;
                            $totalValue = $stock * $salePrice;
                            
                            $stockClass = $stock > 10 ? 'high' : ($stock > 0 ? 'medium' : 'low');
                            $hasMultipleBatches = $product->has_multiple_batches ?? false;
                            $isCumulated = $product->is_cumulated ?? false;
                            $hasBeenCumulated = $product->has_been_cumulated ?? false;
                            
                            $idClass = '';
                            $avatarClass = '';
                            if ($isCumulated) { $idClass = 'cumulated'; $avatarClass = 'cumulated'; }
                            elseif ($hasBeenCumulated) { $idClass = 'merged'; $avatarClass = 'merged'; }
                        @endphp
                        <tr class="{{ $hasBeenCumulated ? 'opacity-75' : '' }}">
                            <td>
                                <span class="sp-id {{ $idClass }}">{{ $product->id }}</span>
                            </td>
                            <td>
                                <div class="sp-product">
                                    <div class="sp-product-avatar {{ $avatarClass }}">
                                        {{ substr($product->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="sp-product-info">
                                        <div class="sp-product-name">
                                            {{ $product->name }}
                                            @if($isCumulated)
                                                <span class="sp-product-badge badge-cumulated">
                                                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:10px;height:10px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2" />
                                                    </svg>
                                                    Cumulé
                                                </span>
                                            @endif
                                            @if($hasBeenCumulated)
                                                <span class="sp-product-badge badge-merged">
                                                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:10px;height:10px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Fusionné
                                                </span>
                                            @endif
                                        </div>
                                        <div class="sp-product-ref">#{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="sp-price">{{ number_format($salePrice, 0, ',', ' ') }}</div>
                                <div class="sp-price-sub">CFA</div>
                            </td>
                            @if(Auth::user() && Auth::user()->role === 'admin')
                                <td>
                                    <div class="sp-price">{{ number_format($purchasePrice, 0, ',', ' ') }}</div>
                                    <div class="sp-price-sub">CFA</div>
                                </td>
                            @endif
                            <td>
                                <span class="sp-stock stock-{{ $stockClass }}">
                                    <span style="width:8px;height:8px;border-radius:50%;background:currentColor;"></span>
                                    {{ $stock }}
                                </span>
                            </td>
                            <td>
                                <div class="sp-price">{{ number_format($totalValue, 0, ',', ' ') }}</div>
                                <div class="sp-price-sub">CFA</div>
                            </td>
                            <td>
                                @if($isCumulated)
                                    <span class="sp-status status-cumulated">
                                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2" />
                                        </svg>
                                        Cumulé
                                    </span>
                                @elseif($hasBeenCumulated)
                                    <span class="sp-status status-merged">
                                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Fusionné
                                    </span>
                                @elseif($hasMultipleBatches)
                                    <div class="sp-tooltip">
                                        <span class="sp-status status-multiple">
                                            <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                                            </svg>
                                            {{ $batchesCount ?? 0 }} lot(s)
                                        </span>
                                        <div class="sp-tooltip-content">
                                            Cliquez pour voir les détails
                                        </div>
                                    </div>
                                @else
                                    <span class="sp-status status-simple">
                                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:12px;height:12px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Simple
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:4px; font-size:12px; color:var(--text-2);">
                                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $product->created_at?->format('d/m/Y') ?? 'N/A' }}
                                </div>
                                <div style="font-size:11px; color:var(--text-3);">{{ $product->created_at?->format('H:i') ?? '' }}</div>
                            </td>
                            <td>
                                <div class="sp-actions">
                                    <a href="{{ route('products.show', $product->id) }}" class="sp-btn sp-btn-view" title="Voir">
                                        <svg viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if(Auth::user() && Auth::user()->role === 'admin')
                                        @if(!$hasBeenCumulated)
                                            <a href="{{ route('products.edit', $product->id) }}" class="sp-btn sp-btn-edit" title="Modifier">
                                                <svg viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                                              onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ?')" 
                                              style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="sp-btn sp-btn-delete" title="Supprimer" 
                                                    @if($hasBeenCumulated || ($isCumulated && $originalCount > 0)) disabled @endif>
                                                <svg viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @if($isCumulated)
                                            <form action="{{ route('products.uncumulate', $product->id) }}" method="POST" 
                                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir défaire ce cumul ?')"
                                                  style="display:inline;">
                                                @csrf
                                                <button type="submit" class="sp-btn sp-btn-uncumulate" title="Défaire le cumul">
                                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user() && Auth::user()->role === 'admin' ? 9 : 8 }}">
                                <div class="sp-empty">
                                    <div class="sp-empty-ico">
                                        <svg viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <h3>Aucun produit trouvé</h3>
                                    <p>
                                        @if(request('search') || request('filter'))
                                            Essayez de modifier vos critères de recherche
                                        @else
                                            Commencez par créer votre premier produit
                                        @endif
                                    </p>
                                    @if(request('search') || request('filter'))
                                        <a href="{{ route('products.index') }}" class="btn-outline">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Voir tous
                                        </a>
                                    @else
                                        <a href="{{ route('products.create') }}" class="btn-primary">
                                            <svg viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Créer un produit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($products->hasPages() || $products->total() > 0)
        <div class="sp-pagination">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="font-size:13px; color:var(--text-2);">
                    Page {{ $products->currentPage() }} sur {{ $products->lastPage() }} • 
                    {{ $products->total() }} produit(s)
                </div>
                <div>{{ $products->appends(request()->except('page'))->links() }}</div>
            </div>
        </div>
    @endif
</div>

{{-- MODAL DE FUSION --}}
<div id="mergeModal" class="sp-modal">
    <div class="sp-modal-content">
        <div class="sp-modal-header">
            <h3>
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                </svg>
                Fusionner des produits
            </h3>
            <button type="button" onclick="closeMergeModal()" class="sp-modal-close">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="sp-modal-body">
            <form action="{{ route('products.merge') }}" method="POST" id="mergeForm">
                @csrf
                
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:var(--text-2); margin-bottom:8px;">
                        Produits à fusionner <span style="color:var(--danger);">*</span>
                    </label>
                    <div class="sp-product-grid" id="productsSelection">
                        @foreach($products as $product)
                            @if(!($product->has_been_cumulated ?? false))
                                <div class="sp-product-item">
                                    <input type="checkbox" 
                                           name="product_ids[]" 
                                           value="{{ $product->id }}" 
                                           id="product_{{ $product->id }}"
                                           onchange="updateMergeSelection()">
                                    <label for="product_{{ $product->id }}">
                                        <div class="sp-product-item-name">{{ $product->name }}</div>
                                        <div class="sp-product-item-desc">
                                            <span>Stock: {{ $product->stock }}</span>
                                            <span>{{ number_format($product->sale_price,0,',',' ') }} CFA</span>
                                        </div>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div id="selectionCount" style="margin-top:8px; font-size:12px; color:var(--purple); display:none;">
                        <span id="selectedCount">0</span> produit(s) sélectionné(s)
                    </div>
                </div>

                <div style="background: #fafbfd; border:1px solid var(--border-light); border-radius:var(--radius-sm); padding:20px; margin-bottom:20px;">
                    <h4 style="display:flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:var(--text); margin-bottom:12px;">
                        <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px; stroke:var(--orange);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informations du produit fusionné
                    </h4>
                    
                    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:16px;">
                        <div>
                            <label style="font-size:12px; font-weight:600; color:var(--text-2); margin-bottom:4px; display:block;">
                                Nom <span style="color:var(--danger);">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="mergeName"
                                   style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:var(--radius-sm);"
                                   placeholder="Produit fusionné"
                                   required>
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600; color:var(--text-2); margin-bottom:4px; display:block;">
                                Catégorie <span style="color:var(--danger);">*</span>
                            </label>
                            <select name="category_id" 
                                    id="mergeCategorySelect"
                                    style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:var(--radius-sm);"
                                    required>
                                <option value="">Chargement...</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600; color:var(--text-2); margin-bottom:4px; display:block;">
                                Fournisseur <span style="color:var(--danger);">*</span>
                            </label>
                            <select name="supplier_id" 
                                    id="mergeSupplierSelect"
                                    style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:var(--radius-sm);"
                                    required>
                                <option value="">Chargement...</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600; color:var(--text-2); margin-bottom:4px; display:block;">
                                Référence lot
                            </label>
                            <input type="text" 
                                   name="batch_reference" 
                                   style="width:100%; padding:10px; border:1.5px solid var(--border); border-radius:var(--radius-sm);"
                                   placeholder="MERGE-{{ date('Ymd-His') }}"
                                   value="MERGE-{{ date('Ymd-His') }}">
                        </div>
                    </div>
                </div>

                <div id="mergePreview" style="display:none;">
                    <h4 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:12px;">Aperçu de la fusion</h4>
                    <div class="sp-preview">
                        <div class="sp-preview-grid">
                            <div class="sp-preview-item">
                                <strong>Stock total:</strong> <span id="previewTotalStock">0</span>
                            </div>
                            <div class="sp-preview-item">
                                <strong>Valeur totale:</strong> <span id="previewTotalValue">0 CFA</span>
                            </div>
                            <div class="sp-preview-item">
                                <strong>Produits:</strong> <span id="previewProductCount">0</span>
                            </div>
                            <div class="sp-preview-item">
                                <strong>Prix moyen:</strong> <span id="previewAvgPrice">0 CFA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="sp-modal-footer">
            <button type="button" onclick="closeMergeModal()" class="btn-outline">
                Annuler
            </button>
            <button type="submit" form="mergeForm" id="mergeSubmitBtn" disabled class="btn-primary">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8m-8-4h8m-4 8h8M8 8h8" />
                </svg>
                Fusionner
            </button>
        </div>
    </div>
</div>

<script>
let productsData = {};

function initializeMergeData() {
    productsData = {};
    @foreach($products as $product)
        @if(!($product->has_been_cumulated ?? false))
            productsData[{{ $product->id }}] = {
                name: '{{ addslashes($product->name) }}',
                stock: {{ $product->stock }},
                salePrice: {{ $product->sale_price }},
                purchasePrice: {{ $product->purchase_price }},
                categoryId: {{ $product->category_id ?? 0 }},
                supplierId: {{ $product->supplier_id ?? 0 }}
            };
        @endif
    @endforeach
}

async function loadModalData() {
    const categorySelect = document.getElementById('mergeCategorySelect');
    const supplierSelect = document.getElementById('mergeSupplierSelect');
    
    try {
        const catRes = await fetch('{{ route("api.modal.categories") }}');
        const catData = await catRes.json();
        if (catData.success && catData.data) {
            categorySelect.innerHTML = '<option value="">Sélectionner une catégorie</option>';
            catData.data.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.name;
                categorySelect.appendChild(option);
            });
        }
    } catch (e) {
        categorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
    }
    
    try {
        const supRes = await fetch('{{ route("api.modal.suppliers") }}');
        const supData = await supRes.json();
        if (supData.success && supData.data) {
            supplierSelect.innerHTML = '<option value="">Sélectionner un fournisseur</option>';
            supData.data.forEach(sup => {
                const option = document.createElement('option');
                option.value = sup.id;
                option.textContent = sup.name;
                supplierSelect.appendChild(option);
            });
        }
    } catch (e) {
        supplierSelect.innerHTML = '<option value="">Erreur de chargement</option>';
    }
}

function openMergeModal() {
    document.getElementById('mergeModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    document.getElementById('mergeForm').reset();
    document.querySelectorAll('input[name="product_ids[]"]').forEach(cb => cb.checked = false);
    initializeMergeData();
    loadModalData();
    updateMergeSelection();
}

function closeMergeModal() {
    document.getElementById('mergeModal').classList.remove('show');
    document.body.style.overflow = '';
}

function updateMergeSelection() {
    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    const count = checkboxes.length;
    const countEl = document.getElementById('selectedCount');
    const previewEl = document.getElementById('mergePreview');
    const submitBtn = document.getElementById('mergeSubmitBtn');
    const selectionCountEl = document.getElementById('selectionCount');
    
    if (countEl) countEl.textContent = count;
    if (previewEl) previewEl.style.display = count >= 2 ? 'block' : 'none';
    if (submitBtn) submitBtn.disabled = count < 2;
    if (selectionCountEl) selectionCountEl.style.display = count > 0 ? 'block' : 'none';
    
    if (count >= 2) updateMergePreview(checkboxes);
    if (count >= 1) preselectFirstProduct(checkboxes[0]?.value);
}

function updateMergePreview(checkboxes) {
    let totalStock = 0, totalValue = 0, totalSale = 0, count = 0;
    
    checkboxes.forEach(cb => {
        const p = productsData[cb.value];
        if (p) {
            totalStock += p.stock;
            totalValue += p.stock * p.salePrice;
            totalSale += p.salePrice;
            count++;
        }
    });
    
    document.getElementById('previewTotalStock').textContent = totalStock;
    document.getElementById('previewTotalValue').textContent = totalValue.toLocaleString('fr-FR') + ' CFA';
    document.getElementById('previewProductCount').textContent = count;
    document.getElementById('previewAvgPrice').textContent = (count ? Math.round(totalSale / count) : 0).toLocaleString('fr-FR') + ' CFA';
}

function preselectFirstProduct(productId) {
    const product = productsData[productId];
    if (!product) return;
    
    const categorySelect = document.getElementById('mergeCategorySelect');
    const supplierSelect = document.getElementById('mergeSupplierSelect');
    
    if (product.categoryId) {
        for (let i = 0; i < categorySelect.options.length; i++) {
            if (categorySelect.options[i].value == product.categoryId) {
                categorySelect.value = product.categoryId;
                break;
            }
        }
    }
    
    if (product.supplierId) {
        for (let i = 0; i < supplierSelect.options.length; i++) {
            if (supplierSelect.options[i].value == product.supplierId) {
                supplierSelect.value = product.supplierId;
                break;
            }
        }
    }
    
    if (!document.getElementById('mergeName').value) {
        document.getElementById('mergeName').value = product.name + ' (Fusionné)';
    }
}

function clearSearch() {
    window.location.href = "{{ route('products.index') }}";
}

document.addEventListener('DOMContentLoaded', function() {
    initializeMergeData();
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', () => clearTimeout(timeout));
    }
    
    document.getElementById('searchForm')?.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<svg viewBox="0 0 24 24" stroke-width="2" style="width:16px;height:16px; animation:spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Recherche...';
        btn.disabled = true;
    });
});
</script>
@endsection