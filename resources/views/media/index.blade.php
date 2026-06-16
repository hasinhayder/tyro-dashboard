@extends('tyro-dashboard::layouts.app')

@section('title', 'Media Library')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Media Library</span>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    .media-library-shell {
        display: grid;
        gap: 1rem;
    }
    .upload-zone {
        border: 2px dashed var(--border);
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        background: var(--muted);
        color: var(--muted-foreground);
        position: relative;
    }
    .upload-zone.drag-over {
        border-color: var(--primary);
        background: color-mix(in srgb, var(--primary) 8%, transparent);
    }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer;
    }
    .upload-zone svg { margin: 0 auto 0.75rem; display: block; }
    .upload-zone .upload-title { font-weight: 600; margin-bottom: 0.25rem; color: var(--foreground); }
    .upload-zone .upload-hint { font-size: 0.8125rem; }
    .media-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .media-toolbar > form {
        flex: 1;
        min-width: min(100%, 680px);
    }
    .view-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem;
        border: 1px solid var(--border);
        border-radius: 999px;
        background: var(--muted);
    }
    .view-toggle-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-width: 5.5rem;
        padding: 0.55rem 0.85rem;
        border-radius: 999px;
        color: var(--muted-foreground);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 700;
        transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
    }
    .view-toggle-link:hover {
        color: var(--foreground);
        transform: translateY(-1px);
    }
    .view-toggle-link.is-active {
        background: var(--card);
        color: var(--foreground);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
    }
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    @media (min-width: 1280px) {
        .media-grid { grid-template-columns: repeat(5, 1fr); }
    }
    @media (min-width: 1600px) {
        .media-grid { grid-template-columns: repeat(6, 1fr); }
    }
    .media-card {
        border: 1px solid var(--border);
        border-radius: 0.875rem;
        overflow: hidden;
        background: var(--card);
        transition: box-shadow 0.22s ease, transform 0.22s ease, border-color 0.22s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .media-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.10), 0 2px 8px rgba(15, 23, 42, 0.06);
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--primary) 30%, var(--border));
    }
    .media-card-figure {
        position: relative;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background:
            radial-gradient(circle at 30% 20%, color-mix(in srgb, var(--primary) 12%, transparent), transparent 50%),
            linear-gradient(145deg, color-mix(in srgb, var(--muted) 70%, white), var(--muted));
    }
    .media-card-figure[data-lightbox-trigger] {
        cursor: pointer;
    }
    .media-card-figure[data-lightbox-trigger]:focus-visible {
        outline: 2px solid color-mix(in srgb, var(--primary) 70%, white);
        outline-offset: -2px;
    }
    .media-bulk-check {
        width: 1.45rem;
        height: 1.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.4rem;
        background: color-mix(in srgb, var(--foreground) 72%, transparent);
        border: 1px solid color-mix(in srgb, var(--background) 45%, transparent);
        backdrop-filter: blur(8px);
        flex-shrink: 0;
    }
    .media-card-actions > .media-bulk-check {
        margin-right: auto;
        background: color-mix(in srgb, var(--muted) 82%, var(--card));
        border-color: var(--border);
    }
    .media-bulk-check input,
    .media-table-select input {
        width: 0.95rem;
        height: 0.95rem;
        margin: 0;
        accent-color: var(--destructive);
        cursor: pointer;
    }
    .media-table-select {
        width: 3rem;
        text-align: center;
    }
    .media-card-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: var(--muted);
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .media-card:hover .media-card-thumb {
        transform: scale(1.06);
    }
    .ii-star-toggle {
        appearance: none;
        border: 0;
        position: absolute;
        top: 0.6rem;
        right: 0.6rem;
        z-index: 2;
        width: 2rem;
        height: 2rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.62);
        color: rgba(255, 255, 255, 0.86);
        backdrop-filter: blur(8px);
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.15s ease, color 0.15s ease;
    }
    .ii-star-toggle:hover {
        transform: translateY(-1px);
        background: rgba(15, 23, 42, 0.78);
        color: #fff;
    }
    .ii-star-toggle.is-starred {
        background: rgba(245, 158, 11, 0.92);
        color: #fff;
    }
    .ii-star-toggle svg {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }
    .media-card-icon {
        width: 100%;
        height: 100%;
        display: flex; align-items: center; justify-content: center;
        background: var(--muted); color: var(--muted-foreground);
    }
    .media-card-overlay {
        position: absolute;
        inset: auto 0 0 0;
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.65rem;
        background: linear-gradient(180deg, transparent 20%, rgba(15, 23, 42, 0.75));
        opacity: 0;
        transition: opacity 0.22s ease;
    }
    .media-card:hover .media-card-overlay,
    .media-card:focus-within .media-card-overlay {
        opacity: 1;
    }
    .media-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        align-items: center;
    }
    .media-card-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.22rem 0.5rem;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(8px);
    }
    .media-card-dims {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.22rem 0.5rem;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        color: rgba(255,255,255,0.92);
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(8px);
    }
    .media-card-preview {
        appearance: none;
        border: 0;
        border-radius: 999px;
        padding: 0.4rem 0.7rem;
        background: rgba(255, 255, 255, 0.92);
        color: #0f172a;
        font-size: 0.7rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .media-card-preview:hover {
        transform: translateY(-1px);
        background: #fff;
    }
    .media-card-body {
        padding: 0.65rem 0.75rem 0.7rem;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        flex: 1;
    }
    .media-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .media-card-name {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--foreground);
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.35;
    }
    .media-card-extension {
        flex-shrink: 0;
        border-radius: 0.35rem;
        padding: 0.2rem 0.4rem;
        background: color-mix(in srgb, var(--primary) 12%, var(--muted));
        color: var(--primary);
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .media-card-meta {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.35rem;
        font-size: 0.68rem;
        color: var(--muted-foreground);
    }
    .media-card-meta span {
        border-radius: 999px;
        padding: 0.18rem 0.4rem;
        background: color-mix(in srgb, var(--muted) 84%, transparent);
    }
    .media-card-actions {
        display: flex;
        gap: 0.3rem;
        margin-top: auto;
        justify-content: flex-end;
        padding-top: 0.35rem;
        border-top: 1px solid color-mix(in srgb, var(--border) 60%, transparent);
    }
    .media-card-actions .btn {
        font-size: 0.7rem;
        padding: 0.3rem;
        width: 1.7rem;
        height: 1.7rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 0.4rem;
        transition: background-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
    }
    .media-card-actions .btn:hover {
        transform: translateY(-1px);
    }
    .media-card-actions .btn svg {
        width: 13px;
        height: 13px;
        flex-shrink: 0;
    }
    .media-card-alt {
        display: grid;
        gap: 0.25rem;
    }
    .media-card-alt label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted-foreground);
    }
    .media-card-alt .form-input {
        font-size: 0.72rem;
        padding: 0.4rem 0.55rem;
        border-radius: 0.5rem;
    }
    .gallery-empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--muted-foreground);
    }
    .gallery-empty-state svg {
        opacity: 0.25;
        margin-bottom: 1.25rem;
    }
    .gallery-empty-state p:first-of-type {
        font-weight: 600;
        color: var(--foreground);
        margin-bottom: 0.35rem;
        font-size: 1rem;
    }
    .gallery-empty-state p:last-of-type {
        font-size: 0.85rem;
        max-width: 320px;
        margin: 0 auto;
        line-height: 1.5;
    }
    .media-table-wrap {
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 0.875rem;
        background: var(--card);
    }
    .media-table-wrap::-webkit-scrollbar {
        height: 6px;
    }
    .media-table-wrap::-webkit-scrollbar-track {
        background: transparent;
    }
    .media-table-wrap::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 99px;
    }
    .media-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 980px;
    }
    .media-table thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        text-align: left;
        padding: 0.8rem 1rem;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--muted-foreground);
        background: color-mix(in srgb, var(--muted) 90%, var(--card));
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .media-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid color-mix(in srgb, var(--border) 50%, transparent);
        vertical-align: middle;
    }
    .media-table tbody tr:last-child td {
        border-bottom: 0;
    }
    .media-table tbody tr {
        transition: background-color 0.12s ease;
    }
    .media-table tbody tr:nth-child(even) {
        background: color-mix(in srgb, var(--muted) 25%, transparent);
    }
    .media-table tbody tr:hover {
        background: color-mix(in srgb, var(--primary) 6%, var(--muted) 30%, transparent);
    }
    .media-table-file {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 280px;
    }
    .media-table-thumb {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.65rem;
        overflow: hidden;
        flex-shrink: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 30% 20%, color-mix(in srgb, var(--primary) 12%, transparent), transparent 50%),
            linear-gradient(145deg, color-mix(in srgb, var(--muted) 70%, white), var(--muted));
        color: var(--muted-foreground);
        border: 1px solid color-mix(in srgb, var(--border) 50%, transparent);
    }
    .media-table-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .media-table-details {
        min-width: 0;
        display: grid;
        gap: 0.2rem;
    }
    .media-table-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--foreground);
        line-height: 1.35;
        word-break: break-word;
    }
    .media-table-filename {
        font-size: 0.72rem;
        color: var(--muted-foreground);
        line-height: 1.4;
        word-break: break-word;
    }
    .media-table-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 0.35rem;
        padding: 0.25rem 0.5rem;
        background: color-mix(in srgb, var(--primary) 10%, var(--muted));
        color: var(--primary);
        font-size: 0.68rem;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
    .media-table-size,
    .media-table-date,
    .media-table-muted {
        font-size: 0.78rem;
        color: var(--muted-foreground);
        white-space: nowrap;
    }
    .media-table-dims {
        font-size: 0.75rem;
        color: var(--muted-foreground);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .media-table-alt {
        min-width: 200px;
        display: grid;
        gap: 0.3rem;
    }
    .media-table-alt label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted-foreground);
    }
    .media-table-alt .form-input {
        min-width: 200px;
        font-size: 0.75rem;
        padding: 0.45rem 0.6rem;
        border-radius: 0.5rem;
    }
    .media-table-actions {
        justify-content: flex-start;
        margin-top: 0;
        flex-wrap: wrap;
        min-width: 160px;
        gap: 0.25rem;
        padding-top: 0;
        border-top: none;
    }
    .media-table-actions .btn {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.4rem;
    }
    .media-table-actions .btn svg {
        width: 13px;
        height: 13px;
    }
    .dashboard-lightbox {
        position: fixed;
        inset: 0;
        z-index: 260;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .dashboard-lightbox.open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .dashboard-lightbox__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.88);
        backdrop-filter: blur(10px);
    }
    .dashboard-lightbox__dialog {
        position: relative;
        z-index: 1;
        width: min(96vw, 1200px);
        max-height: 94vh;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .dashboard-lightbox__toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .dashboard-lightbox__meta {
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.82rem;
        line-height: 1.5;
    }
    .dashboard-lightbox__close {
        width: 2.5rem;
        height: 2.5rem;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .dashboard-lightbox__media {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 0;
    }
    .dashboard-lightbox__image {
        max-width: 100%;
        max-height: 82vh;
        object-fit: contain;
        border-radius: 1.25rem;
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.38);
        background: rgba(255, 255, 255, 0.03);
    }
    @media (max-width: 640px) {
        .media-toolbar {
            align-items: stretch;
        }
        .media-toolbar > form {
            min-width: 100%;
        }
        .view-toggle {
            width: 100%;
            justify-content: space-between;
        }
        .view-toggle-link {
            flex: 1;
        }
        .media-grid {
            grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
            gap: 0.75rem;
        }
        .media-card-body {
            padding: 0.55rem;
        }
        .media-card-actions .btn {
            width: 1.6rem;
            height: 1.6rem;
        }
        .dashboard-lightbox__toolbar {
            flex-direction: column-reverse;
            align-items: stretch;
        }
    }
    .upload-progress {
        display: none;
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 0.625rem; padding: 1rem 1.25rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        min-width: 280px;
    }
    .upload-progress.show { display: block; }
    .progress-bar-track { background: var(--muted); border-radius: 99px; height: 6px; overflow: hidden; margin-top: 0.5rem; }
    .progress-bar-fill { background: var(--primary); height: 100%; width: 0%; transition: width 0.2s; border-radius: 99px; }
    .stats-bar { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .stat-item { font-size: 0.875rem; color: var(--muted-foreground); }
    .stat-item strong { color: var(--foreground); }
    .copy-toast {
        position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
        background: var(--foreground); color: var(--background);
        padding: 0.5rem 1rem; border-radius: 999px;
        font-size: 0.875rem; z-index: 9999;
        opacity: 0; transition: opacity 0.2s; pointer-events: none;
    }
    .copy-toast.show { opacity: 1; }
    .upload-panel {
        display: none;
    }
    .upload-panel.is-open {
        display: block;
    }
    /* ── Crop/Resize Modal ──────────────────────────────────────────── */
    .cr-overlay {
        position: fixed; inset: 0; z-index: 200;
        display: flex; align-items: center; justify-content: center; padding: 1rem;
        opacity: 0; visibility: hidden; pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .cr-overlay.open { opacity: 1; visibility: visible; pointer-events: auto; }
    .cr-backdrop {
        position: absolute; inset: 0;
        background: rgba(15,23,42,0.88); backdrop-filter: blur(8px); cursor: pointer;
    }
    .cr-dialog {
        position: relative; z-index: 1;
        width: min(96vw, 860px); max-height: 94vh;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 1.25rem; display: flex; flex-direction: column;
        overflow: hidden; box-shadow: 0 32px 80px rgba(0,0,0,0.22);
    }
    .cr-header {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem 1rem; border-bottom: 1px solid var(--border); flex-shrink: 0;
    }
    .cr-title { font-size: 1.1rem; font-weight: 700; color: var(--foreground); margin: 0 0 0.2rem; }
    .cr-subtitle { font-size: 0.8rem; color: var(--muted-foreground); margin: 0; max-width: 500px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cr-close {
        width: 2.25rem; height: 2.25rem; border: 1px solid var(--border); border-radius: 999px;
        background: var(--muted); color: var(--foreground); cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .cr-tabs { display: flex; padding: 0.75rem 1.5rem 0; border-bottom: 1px solid var(--border); flex-shrink: 0; }
    .cr-tab {
        padding: 0.5rem 1.1rem; border: 0; border-bottom: 2px solid transparent;
        background: transparent; color: var(--muted-foreground);
        font-size: 0.875rem; font-weight: 600; cursor: pointer; margin-bottom: -1px;
        transition: color 0.15s, border-color 0.15s;
    }
    .cr-tab.cr-tab--active { color: var(--primary); border-bottom-color: var(--primary); }
    .cr-body { flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem; min-height: 0; }
    .cr-image-wrap {
        background: var(--muted); border-radius: 0.75rem; overflow: hidden;
        max-height: 420px; display: flex; align-items: center; justify-content: center;
    }
    .cr-image-wrap img { display: block; max-width: 100%; }
    .cr-crop-info { margin-top: 0.65rem; font-size: 0.8rem; color: var(--muted-foreground); text-align: center; }
    .cr-resize-area { display: flex; flex-direction: column; gap: 1.25rem; }
    .cr-resize-preview { text-align: center; background: var(--muted); border-radius: 0.75rem; padding: 1rem; }
    .cr-resize-preview img { max-height: 200px; max-width: 100%; border-radius: 0.5rem; object-fit: contain; }
    .cr-orig-dims { font-size: 0.8rem; color: var(--muted-foreground); margin: 0 0 0.75rem; }
    .cr-resize-row { display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; }
    .cr-resize-field { display: flex; flex-direction: column; gap: 0.35rem; flex: 1; min-width: 100px; }
    .cr-resize-field label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted-foreground); }
    .cr-lock-label { display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; font-weight: 600; color: var(--foreground); cursor: pointer; padding-bottom: 0.35rem; white-space: nowrap; }
    .cr-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 0.85rem; flex-shrink: 0; }
    .cr-filename-row { display: flex; flex-direction: column; gap: 0.35rem; }
    .cr-filename-row label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted-foreground); }
    .cr-footer-actions { display: flex; align-items: center; gap: 0.75rem; justify-content: flex-end; }
    .cr-status { font-size: 0.8rem; color: var(--muted-foreground); margin-right: auto; }

    /* ── Image Importer Modal ──────────────────────────────────────── */
    .ii-overlay {
        position: fixed; inset: 0; z-index: 200;
        display: flex; align-items: center; justify-content: center; padding: 1rem;
        opacity: 0; visibility: hidden; pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .ii-overlay.open { opacity: 1; visibility: visible; pointer-events: auto; }
    .ii-backdrop {
        position: absolute; inset: 0;
        background: rgba(15,23,42,0.88); backdrop-filter: blur(8px); cursor: pointer;
    }
    .ii-dialog {
        position: relative; z-index: 1;
        width: min(96vw, 1060px); max-height: 92vh;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 1.25rem; display: flex; flex-direction: column;
        overflow: hidden; box-shadow: 0 32px 80px rgba(0,0,0,0.22);
    }
    .ii-header {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 1.25rem 1.5rem 1rem; border-bottom: 1px solid var(--border); flex-shrink: 0;
    }
    .ii-header-left { display: flex; flex-direction: column; gap: 0.2rem; }
    .ii-title { font-size: 1.1rem; font-weight: 700; color: var(--foreground); margin: 0; }
    .ii-subtitle { font-size: 0.8rem; color: var(--muted-foreground); margin: 0; }
    .ii-close {
        width: 2.25rem; height: 2.25rem; border: 1px solid var(--border); border-radius: 999px;
        background: var(--muted); color: var(--foreground); cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
        transition: background 0.15s, color 0.15s;
    }
    .ii-close:hover { background: var(--destructive); color: #fff; border-color: var(--destructive); }
    .ii-tabs {
        display: flex; padding: 0 1.5rem; border-bottom: 1px solid var(--border); flex-shrink: 0;
        gap: 0.25rem;
    }
    .ii-tab {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.25rem; border: 0; border-bottom: 2px solid transparent;
        background: transparent; color: var(--muted-foreground);
        font-size: 0.875rem; font-weight: 600; cursor: pointer; margin-bottom: -1px;
        transition: color 0.15s, border-color 0.15s; white-space: nowrap;
    }
    .ii-tab svg { flex-shrink: 0; }
    .ii-tab.ii-tab--active { color: var(--primary); border-bottom-color: var(--primary); }
    .ii-tab:hover:not(.ii-tab--active) { color: var(--foreground); }
    .ii-tab--starred svg { color: #f59e0b; }
    .ii-body { flex: 1; overflow-y: auto; min-height: 0; display: flex; flex-direction: column; }
    .ii-search-bar {
        display: flex; gap: 0.75rem; padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border); flex-shrink: 0; align-items: center;
    }
    .ii-search-bar .search-box { flex: 1; }
    .ii-search-bar .search-box input { width: 100%; }
    .ii-results {
        flex: 1; padding: 1.25rem 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.9rem;
        align-content: start;
    }
    .ii-no-key {
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 3rem 2rem; gap: 1rem; text-align: center; color: var(--muted-foreground);
    }
    .ii-no-key-icon {
        width: 56px; height: 56px; border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        color: var(--primary); flex-shrink: 0;
    }
    .ii-no-key h3 { font-size: 1rem; font-weight: 700; color: var(--foreground); margin: 0; }
    .ii-no-key p { font-size: 0.85rem; line-height: 1.6; margin: 0; max-width: 380px; }
    .ii-no-key code {
        display: inline-block; background: var(--muted); border-radius: 0.4rem;
        padding: 0.25rem 0.55rem; font-size: 0.8rem; font-family: monospace;
        color: var(--foreground); margin-top: 0.25rem;
    }
    .ii-state {
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 3rem 2rem; gap: 0.75rem; text-align: center; color: var(--muted-foreground);
        font-size: 0.875rem;
    }
    .ii-state svg { opacity: 0.35; }
    .ii-spinner {
        width: 36px; height: 36px; border: 3px solid var(--border);
        border-top-color: var(--primary); border-radius: 50%;
        animation: ii-spin 0.7s linear infinite;
    }
    @keyframes ii-spin { to { transform: rotate(360deg); } }
    .ii-img-card {
        border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden;
        background: var(--muted); cursor: pointer; position: relative;
        transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
        aspect-ratio: 4/3; display: flex; align-items: stretch;
    }
    .ii-img-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 24px rgba(0,0,0,0.14);
        transform: translateY(-2px);
    }
    .ii-img-card img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.3s ease;
    }
    .ii-img-card:hover img { transform: scale(1.06); }
    .ii-img-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(15,23,42,0.82));
        opacity: 0; transition: opacity 0.2s ease;
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 0.65rem 0.7rem; gap: 0.4rem;
    }
    .ii-img-card:hover .ii-img-overlay,
    .ii-img-card:focus-visible .ii-img-overlay,
    .ii-img-card:focus-within .ii-img-overlay { opacity: 1; }
    .ii-img-caption {
        font-size: 0.7rem; color: rgba(255,255,255,0.75); line-height: 1.3;
        overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    }
    .ii-preview-hint {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        align-self: flex-start;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        color: #fff;
        font-size: 0.68rem;
        font-weight: 700;
        backdrop-filter: blur(8px);
        pointer-events: none;
    }
    .ii-import-btn {
        appearance: none; border: 0; border-radius: 999px;
        padding: 0.45rem 0.9rem; font-size: 0.73rem; font-weight: 700;
        background: var(--primary); color: #fff; cursor: pointer; align-self: flex-start;
        transition: transform 0.15s, opacity 0.15s;
        display: flex; align-items: center; gap: 0.35rem;
    }
    .ii-import-btn:hover { transform: translateY(-1px); }
    .ii-import-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .ii-import-btn.done { background: #16a34a; }
    /* import progress bar */
    .ii-import-progress {
        padding: 0 1.5rem 0.85rem; flex-shrink: 0;
        display: none;
    }
    .ii-import-progress.show { display: block; }
    .ii-import-progress-label {
        display: flex; align-items: center; gap: 0.6rem;
        font-size: 0.8rem; font-weight: 600; color: var(--foreground); margin-bottom: 0.5rem;
    }
    .ii-import-progress-label .ii-spinner {
        width: 14px; height: 14px; border-width: 2px; flex-shrink: 0;
    }
    .ii-import-progress-track {
        background: var(--muted); border-radius: 99px; height: 5px; overflow: hidden;
    }
    .ii-import-progress-fill {
        background: var(--primary); height: 100%; width: 0%;
        border-radius: 99px; transition: width 0.25s ease;
    }
    .ii-footer {
        padding: 0.9rem 1.5rem; border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        flex-shrink: 0;
    }
    .ii-pagination { display: flex; gap: 0.5rem; align-items: center; }
    .ii-page-btn {
        appearance: none; border: 1px solid var(--border); border-radius: 0.5rem;
        background: var(--muted); color: var(--foreground); font-size: 0.8rem; font-weight: 600;
        padding: 0.4rem 0.75rem; cursor: pointer; transition: background 0.15s, border-color 0.15s;
    }
    .ii-page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .ii-page-btn:hover:not(:disabled) { background: var(--border); }
    .ii-page-info { font-size: 0.8rem; color: var(--muted-foreground); white-space: nowrap; }
    .ii-results-count { font-size: 0.8rem; color: var(--muted-foreground); }
    .ii-toast {
        position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
        background: #16a34a; color: #fff;
        padding: 0.5rem 1.1rem; border-radius: 999px;
        font-size: 0.875rem; font-weight: 600; z-index: 9999;
        opacity: 0; transition: opacity 0.25s; pointer-events: none; white-space: nowrap;
    }
    .ii-toast.show { opacity: 1; }
    @media (max-width: 640px) {
        .ii-results { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
        .ii-tabs { overflow-x: auto; }
    }</style>
@endpush

@section('content')
<div class="media-library-shell">
<script type="application/json" id="dashboardMediaConfig">
    @php
        $dashboardMediaConfig = [
            'csrfToken' => csrf_token(),
            'uploadUrl' => route($dashboardRoute::name('media.upload')),
            'searchUrl' => route($dashboardRoute::name('media.image-search')),
            'importUrl' => route($dashboardRoute::name('media.image-import')),
            'pickerUrl' => route($dashboardRoute::name('media.picker')),
            'starredStoreUrl' => route($dashboardRoute::name('media.starred-images.store')),
            'starredDeleteUrl' => route($dashboardRoute::name('media.starred-images.destroy')),
            'mediaUrl' => route($dashboardRoute::name('media')),
            'settingsUrl' => route($dashboardRoute::name('settings.system.index')),
            'importerKeys' => $importerKeys,
            'starredImages' => $starredImages,
        ];
    @endphp
    {!! json_encode($dashboardMediaConfig) !!}
</script>
<form id="bulk-media-delete-form" action="{{ route($dashboardRoute::name('media.bulk-destroy')) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    @foreach(request()->except(['_token', '_method', 'selected_ids']) as $key => $value)
        @if(is_scalar($value))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
</form>
<div class="page-header">
    <div class="page-header-row">
        </div>
        <div style="display:flex;gap:0.5rem;flex-shrink:0;">
            <button type="button" class="btn btn-primary" id="toggleUploadForm" style="white-space:nowrap;">
                Add Media
            </button>
            <button type="button" class="btn btn-secondary" id="openImageImporter" style="white-space:nowrap;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;display:inline;vertical-align:-2px;margin-right:4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                </svg>
                Import Images
            </button>
            <button type="button" class="btn btn-destructive" id="bulk-media-delete-btn" onclick="submitBulkMediaDelete()" style="white-space:nowrap;display:none;">
                Delete Selected
            </button>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <div class="stats-bar">
            <div class="stat-item">Total files: <strong>{{ $totalCount }}</strong></div>
            <div class="stat-item">Total size: <strong>{{ $totalCount > 0 ? formatBytes($totalSize) : '0 B' }}</strong></div>
        </div>
    </div>
</div>

@php
function formatBytes(int $bytes): string {
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
    return round($bytes / 1048576, 1) . ' MB';
}
$adminRoles = config('tyro-dashboard.admin_roles', ['admin', 'super-admin']);
$canDeleteMedia = !session()->has('impersonator_id') && !empty(array_intersect(array_merge($adminRoles, ['editor']), auth()->user()?->tyroRoleSlugs() ?? []));
$authUserId = auth()->id();
@endphp

<!-- Upload Zone -->
<div class="card upload-panel" id="uploadPanel" style="margin-bottom: 1.5rem;">
    <div class="card-header"><h3 class="card-title">Upload Files</h3></div>
    <div class="card-body">
        <div class="upload-zone" id="uploadZone">
            <input type="file" id="fileInput" multiple
                   accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,audio/mpeg,video/mp4,application/zip">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;opacity:0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
            </svg>
            <div class="upload-title">Drag & drop files here, or click to browse</div>
            <div class="upload-hint">Images (JPG, PNG, GIF, WebP, SVG), PDF, Word, MP3, MP4, ZIP — max 20 MB each</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <div class="media-toolbar">
            <form action="{{ route($dashboardRoute::name('media')) }}" method="GET">
                <input type="hidden" name="view" value="{{ $mediaView }}">
                <div class="filters-bar">
                    <div class="search-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" class="form-input" placeholder="Search files…" value="{{ request('search') }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Type:</label>
                        <select name="type" class="form-select" style="min-width:130px;">
                            <option value="">All Types</option>
                            <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                            <option value="application" {{ request('type') === 'application' ? 'selected' : '' }}>Documents</option>
                            <option value="audio" {{ request('type') === 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Date:</label>
                        <select name="date" class="form-select" style="min-width:175px;">
                            <option value="">All Dates</option>
                            @foreach($uploadDates as $date)
                                <option value="{{ $date->format('Y-m-d') }}" {{ request('date') === $date->format('Y-m-d') ? 'selected' : '' }}>
                                    {{ $date->format('F j, Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                    @if(request()->hasAny(['search', 'type', 'date']))
                        <a href="{{ route($dashboardRoute::name('media'), ['view' => $mediaView]) }}" class="btn btn-primary">Clear</a>
                    @endif
                </div>
            </form>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <form action="{{ route($dashboardRoute::name('media')) }}" method="GET" style="margin: 0;">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                    @if(request('date')) <input type="hidden" name="date" value="{{ request('date') }}"> @endif
                    <input type="hidden" name="view" value="{{ $mediaView }}">
                    <select name="per_page" class="form-select" style="min-width:70px; padding-top:0.6rem; padding-bottom:0.6rem; font-size:0.8rem;" onchange="this.form.submit()">
                        <option value="12" {{ $mediaPerPage == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ $mediaPerPage == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ $mediaPerPage == 48 ? 'selected' : '' }}>48</option>
                        <option value="96" {{ $mediaPerPage == 96 ? 'selected' : '' }}>96</option>
                    </select>
                </form>
                <div class="view-toggle" role="tablist" aria-label="Media layout">
                <a
                    href="{{ route($dashboardRoute::name('media'), array_merge(request()->query(), ['view' => 'grid'])) }}"
                    class="view-toggle-link {{ $mediaView === 'grid' ? 'is-active' : '' }}"
                    role="tab"
                    aria-selected="{{ $mediaView === 'grid' ? 'true' : 'false' }}"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h6.75v6.75H3.75V4.5Zm9.75 0h6.75v6.75H13.5V4.5Zm-9.75 9.75h6.75V21H3.75v-6.75Zm9.75 0h6.75V21H13.5v-6.75Z" />
                    </svg>
                    Grid
                </a>
                <a
                    href="{{ route($dashboardRoute::name('media'), array_merge(request()->query(), ['view' => 'list'])) }}"
                    class="view-toggle-link {{ $mediaView === 'list' ? 'is-active' : '' }}"
                    role="tab"
                    aria-selected="{{ $mediaView === 'list' ? 'true' : 'false' }}"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.008v.008H3.75V6.75Zm0 5.25h.008v.008H3.75V12Zm0 5.25h.008v.008H3.75v-.008Z" />
                    </svg>
                    List
                </a>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Media Results -->
@if($media->count())
@if($mediaView === 'grid')
<div class="media-grid" id="mediaGrid">
    @foreach($media as $file)
    <div class="media-card" id="media-{{ $file->id }}" data-media-entry>
        <div class="media-card-figure" @if($file->is_image) data-lightbox-trigger data-image-src="{{ Storage::url($file->url) }}" data-image-alt="{{ $file->alt_text ?: $file->filename }}" data-image-name="{{ $file->filename }}" data-image-meta="{{ $file->formatted_size }} · {{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}" role="button" tabindex="0" aria-label="Preview {{ $file->alt_text ?: $file->filename }}" title="Preview image" @endif>
            @if($file->is_image)
                <img src="{{ Storage::url($file->thumbnail_url) }}" alt="{{ $file->alt_text ?: $file->filename }}" class="media-card-thumb" loading="lazy">
                <div class="media-card-overlay">
                    <div class="media-card-badges">
                        <span class="media-card-badge">Image</span>
                        @if($file->width && $file->height)
                            <span class="media-card-dims">{{ $file->width }}&times;{{ $file->height }}</span>
                        @endif
                    </div>
                    <button
                        type="button"
                        class="media-card-preview"
                        data-lightbox-trigger
                        data-image-src="{{ $file->url }}"
                        data-image-alt="{{ $file->alt_text ?: $file->filename }}"
                        data-image-name="{{ $file->filename }}"
                        data-image-meta="{{ $file->formatted_size }} · {{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}"
                    >
                        Preview
                    </button>
                </div>
            @else
                <div class="media-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="media-card-overlay">
                    <div class="media-card-badges">
                        <span class="media-card-badge">{{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION) ?: strtok($file->mime_type, '/')) }}</span>
                    </div>
                </div>
            @endif
        </div>
        <div class="media-card-body">
            <div class="media-card-top">
                <div class="media-card-name" data-media-name title="{{ pathinfo($file->filename, PATHINFO_FILENAME) }}">{{ pathinfo($file->filename, PATHINFO_FILENAME) }}</div>
                <span class="media-card-extension">{{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION) ?: 'FILE') }}</span>
            </div>
            <div class="media-card-meta">
                <span>{{ $file->formatted_size }}</span>
                @if($file->width && $file->height)
                <span>{{ $file->width }}&times;{{ $file->height }}</span>
                @endif
                    <span style="margin-left:auto;font-size:0.75rem;font-weight:500;color:var(--muted-foreground);white-space:nowrap;">
                        ID: {{ $file->id }}
                    </span>
            </div>
            @if($file->is_image)
            <div class="media-card-alt">
                <input type="text" id="alt-{{ $file->id }}" class="form-input"
                    placeholder="Alt text…" value="{{ $file->alt_text }}"
                    onchange="saveAlt({{ $file->id }}, this.value)"
                    onblur="saveAlt({{ $file->id }}, this.value)"
                    title="Alt text for accessibility and SEO">
            </div>
            @endif
            <div class="media-card-actions">
                @if($canDeleteMedia || $file->user_id === $authUserId)
                    <label class="media-bulk-check" title="Select {{ $file->filename }}">
                        <input type="checkbox" class="media-bulk-checkbox" value="{{ $file->id }}" aria-label="Select {{ $file->filename }}">
                    </label>
                @endif
                <a href="{{ Storage::url($file->url) }}" target="_blank" rel="noopener noreferrer"
                        class="btn btn-secondary"
                        title="View in new tab">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                </a>
                @if($file->source_url)
                <a href="{{ $file->source_url }}" target="_blank" rel="noopener noreferrer"
                        class="btn btn-secondary"
                        title="Open source page">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H18a4.5 4.5 0 1 1 0 9h-4.5m-3-15H6a4.5 4.5 0 1 0 0 9h4.5m-3 0h9"/>
                    </svg>
                </a>
                @endif
                <button type="button" class="btn btn-secondary"
                        onclick="showCopyUrlModal('{{ url(Storage::url($file->url)) }}', '{{ $file->webp_url ? url(Storage::url($file->webp_url)) : '' }}', '{{ url(Storage::url($file->thumbnail_url)) }}')"
                        title="Copy URL">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary"
                        data-media-id="{{ $file->id }}"
                        data-media-rename
                        data-filename="{{ e($file->filename) }}"
                        data-extension="{{ e(pathinfo($file->filename, PATHINFO_EXTENSION)) }}"
                        onclick="renameMedia({{ $file->id }}, this)"
                        title="Rename file">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                    </svg>
                </button>
                @if($file->is_image)
                <button type="button" class="btn btn-secondary cr-edit-btn"
                        data-media-id="{{ $file->id }}"
                        data-url="{{ e(Storage::url($file->url)) }}"
                        data-filename="{{ e($file->filename) }}"
                        title="Crop or Resize">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122"/>
                    </svg>
                </button>
                @endif
                @if($canDeleteMedia || $file->user_id === $authUserId)
                <button type="button" class="btn btn-ghost"
                        onclick="deleteMedia({{ $file->id }}, this)"
                        style="color:var(--destructive);"
                        title="Delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="media-table-wrap">
    <table class="media-table">
        <thead>
            <tr>
                <th scope="col" class="media-table-select">
                    <input type="checkbox" id="select-all-media" aria-label="Select all media on this page">
                </th>
                <th scope="col">File</th>
                <th scope="col">Type</th>
                <th scope="col">Size</th>
                <th scope="col">Dimensions</th>
                <th scope="col">Uploaded</th>
                <th scope="col">Alt Text</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($media as $file)
            <tr id="media-{{ $file->id }}" data-media-entry>
                <td class="media-table-select">
                    @if($canDeleteMedia || $file->user_id === $authUserId)
                        <input type="checkbox" class="media-bulk-checkbox" value="{{ $file->id }}" aria-label="Select {{ $file->filename }}">
                    @endif
                </td>
                <td>
                    <div class="media-table-file">
                        <div class="media-table-thumb" @if($file->is_image) data-lightbox-trigger data-image-src="{{ Storage::url($file->url) }}" data-image-alt="{{ $file->alt_text ?: $file->filename }}" data-image-name="{{ $file->filename }}" data-image-meta="{{ $file->formatted_size }} · {{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}" role="button" tabindex="0" aria-label="Preview {{ $file->alt_text ?: $file->filename }}" title="Preview image" @endif>
                            @if($file->is_image)
                                <img src="{{ Storage::url($file->thumbnail_url) }}" alt="{{ $file->alt_text ?: $file->filename }}" loading="lazy">
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:28px;height:28px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            @endif
                        </div>
                        <div class="media-table-details">
                            <div class="media-table-name" data-media-name title="{{ pathinfo($file->filename, PATHINFO_FILENAME) }}">{{ pathinfo($file->filename, PATHINFO_FILENAME) }}</div>
                            <div class="media-table-filename">{{ $file->filename }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="media-table-badge">{{ ucfirst(strtok($file->mime_type, '/')) ?: 'File' }}</span>
                </td>
                <td>
                    <span class="media-table-size">{{ $file->formatted_size }}</span>
                </td>
                <td>
                    @if($file->is_image && $file->width && $file->height)
                        <span class="media-table-dims">{{ $file->width }}&times;{{ $file->height }}</span>
                    @else
                        <span class="media-table-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="media-table-date">{{ optional($file->created_at)->format('M j, Y') }}</span>
                </td>
                <td>
                    @if($file->is_image)
                    <div class="media-table-alt">
                        <input type="text" id="alt-list-{{ $file->id }}" class="form-input"
                            placeholder="Describe this image…" value="{{ $file->alt_text }}"
                            onchange="saveAlt({{ $file->id }}, this.value)"
                            onblur="saveAlt({{ $file->id }}, this.value)"
                            title="Alt text for accessibility and SEO">
                    </div>
                    @else
                    <span class="media-table-muted">Not applicable</span>
                    @endif
                </td>
                <td>
                    <div class="media-card-actions media-table-actions">
                        @if($file->is_image)
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-lightbox-trigger
                            data-image-src="{{ $file->url }}"
                            data-image-alt="{{ $file->alt_text ?: $file->filename }}"
                            data-image-name="{{ $file->filename }}"
                            data-image-meta="{{ $file->formatted_size }} · {{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}"
                            title="Preview image"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.433 0 .644C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/>
                            </svg>
                        </button>
                        @endif
                        <a href="{{ Storage::url($file->url) }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-secondary"
                            title="View in new tab">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                        </a>
                        @if($file->source_url)
                        <a href="{{ $file->source_url }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-secondary"
                            title="Open source page">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H18a4.5 4.5 0 1 1 0 9h-4.5m-3-15H6a4.5 4.5 0 1 0 0 9h4.5m-3 0h9"/>
                            </svg>
                        </a>
                        @endif
                        <button type="button" class="btn btn-secondary"
                            onclick="showCopyUrlModal('{{ url(Storage::url($file->url)) }}', '{{ $file->webp_url ? url(Storage::url($file->webp_url)) : '' }}', '{{ url(Storage::url($file->thumbnail_url)) }}')"
                            title="Copy URL">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-secondary"
                            data-media-id="{{ $file->id }}"
                            data-media-rename
                            data-filename="{{ e($file->filename) }}"
                            data-extension="{{ e(pathinfo($file->filename, PATHINFO_EXTENSION)) }}"
                            onclick="renameMedia({{ $file->id }}, this)"
                            title="Rename file">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                            </svg>
                        </button>
                        @if($file->is_image)
                        <button type="button" class="btn btn-secondary cr-edit-btn"
                            data-media-id="{{ $file->id }}"
                            data-url="{{ e(Storage::url($file->url)) }}"
                            data-filename="{{ e($file->filename) }}"
                            title="Crop or Resize">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122"/>
                            </svg>
                        </button>
                        @endif
                        @if($canDeleteMedia || $file->user_id === $authUserId)
                        <button type="button" class="btn btn-ghost"
                            onclick="deleteMedia({{ $file->id }}, this)"
                            style="color:var(--destructive);"
                            title="Delete">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{ $media->links('pagination.tyro') }}
@else
<div class="card">
    <div class="card-body gallery-empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;margin:0 auto 1rem;display:block;opacity:0.4;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        <p style="font-weight:500;margin-bottom:0.5rem;">No files found</p>
        <p style="font-size:0.875rem;">Upload your first file using the drag & drop area above.</p>
    </div>
</div>
@endif

<!-- Image Importer Modal -->
<div class="ii-overlay" id="iiModal" aria-hidden="true">
    <div class="ii-backdrop" id="iiBackdrop"></div>
    <div class="ii-dialog" role="dialog" aria-modal="true" aria-label="Import Images">
        <div class="ii-header">
            <div class="ii-header-left">
                <h2 class="ii-title">Image Importer</h2>
                <p class="ii-subtitle">Search and import stock photos directly into your media library.</p>
            </div>
            <button type="button" class="ii-close" onclick="closeIIModal()" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="ii-tabs">
            <button type="button" class="ii-tab ii-tab--active" data-ii-provider="freepik" onclick="iiSwitchTab('freepik')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                </svg>
                Freepik
            </button>
            <button type="button" class="ii-tab" data-ii-provider="pixabay" onclick="iiSwitchTab('pixabay')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z"/>
                </svg>
                Pixabay
            </button>
            <button type="button" class="ii-tab" data-ii-provider="unsplash" onclick="iiSwitchTab('unsplash')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                </svg>
                Unsplash
            </button>
            <button type="button" class="ii-tab" data-ii-provider="pexels" onclick="iiSwitchTab('pexels')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                </svg>
                Pexels
            </button>
            <button type="button" class="ii-tab ii-tab--starred" data-ii-provider="starred" onclick="iiSwitchTab('starred')">
                <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.2" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.75.75 0 0 1 1.04 0l2.76 2.666a.75.75 0 0 0 .53.216h3.813a.75.75 0 0 1 .438 1.358l-3.084 2.17a.75.75 0 0 0-.273.84l1.167 3.666a.75.75 0 0 1-1.152.84l-3.152-2.172a.75.75 0 0 0-.86 0l-3.152 2.172a.75.75 0 0 1-1.152-.84l1.167-3.666a.75.75 0 0 0-.273-.84L4.892 7.739a.75.75 0 0 1 .438-1.358h3.813a.75.75 0 0 0 .53-.216l2.76-2.666Z"/>
                </svg>
                Starred
            </button>
        </div>

        <div id="iiPanel" style="flex:1;display:flex;flex-direction:column;min-height:0;overflow:hidden;">
            <div id="iiNoKey" class="ii-no-key" style="display:none;">
                <div class="ii-no-key-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:28px;height:28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                </div>
                <h3 id="iiNoKeyTitle">API Key Required</h3>
                <p id="iiNoKeyMsg">No API key found for this provider. Add the key in Dashboard Settings to enable searching.</p>
            </div>

            <div id="iiSearchBar" class="ii-search-bar" style="display:none;">
                <div class="search-box" style="flex:1;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="iiSearchInput" class="form-input" placeholder="Search images…" autocomplete="off"
                           onkeydown="if(event.key==='Enter'){iiSearch(1);}">
                </div>
                <button type="button" class="btn btn-primary" onclick="iiSearch(1)" style="flex-shrink:0;">Search</button>
            </div>

            <div id="iiResultsWrap" style="flex:1;overflow-y:auto;min-height:0;display:none;">
                <div id="iiResults" class="ii-results"></div>
            </div>

            <div id="iiIdle" class="ii-state" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Enter a keyword above to search images.</span>
            </div>

            <div id="iiLoading" class="ii-state" style="display:none;">
                <div class="ii-spinner"></div>
                <span>Searching…</span>
            </div>

            <div id="iiEmpty" class="ii-state" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/>
                </svg>
                <span id="iiEmptyMsg">No images found. Try a different keyword.</span>
            </div>

            <div id="iiError" class="ii-state" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;color:var(--destructive);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <span id="iiErrorMsg">Search failed. Please try again.</span>
            </div>
        </div>

        <div class="ii-import-progress" id="iiImportProgress">
            <div class="ii-import-progress-label">
                <div class="ii-spinner"></div>
                <span id="iiImportProgressText">Downloading image…</span>
            </div>
            <div class="ii-import-progress-track">
                <div class="ii-import-progress-fill" id="iiImportProgressFill"></div>
            </div>
        </div>
        <div class="ii-footer">
            <span class="ii-results-count" id="iiResultsCount"></span>
            <div class="ii-pagination">
                <button type="button" class="ii-page-btn" id="iiPrevBtn" onclick="iiSearch(iiCurrentPage - 1)" disabled>&larr; Prev</button>
                <span class="ii-page-info" id="iiPageInfo"></span>
                <button type="button" class="ii-page-btn" id="iiNextBtn" onclick="iiSearch(iiCurrentPage + 1)" disabled>Next &rarr;</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Import success toast -->
<div class="ii-toast" id="iiSuccessToast">Image imported to Media Library!</div>

<!-- Crop/Resize Modal -->
<div class="cr-overlay" id="crModal" aria-hidden="true">
    <div class="cr-backdrop" id="crBackdrop"></div>
    <div class="cr-dialog" role="dialog" aria-modal="true" aria-label="Crop & Resize Image">
        <div class="cr-header">
            <div>
                <h2 class="cr-title">Crop / Resize Image</h2>
                <p class="cr-subtitle" id="crSourceName"></p>
            </div>
            <button type="button" class="cr-close" onclick="closeCrModal()" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="cr-tabs">
            <button type="button" class="cr-tab cr-tab--active" id="tabCrop" onclick="setCrMode('crop')">Crop</button>
            <button type="button" class="cr-tab" id="tabResize" onclick="setCrMode('resize')">Resize</button>
        </div>
        <div class="cr-body">
            <div id="crCropPanel">
                <div class="cr-image-wrap">
                    <img id="crImage" src="" alt="Image to crop">
                </div>
                <p class="cr-crop-info">Selection: <strong id="crCropDims">–</strong></p>
            </div>
            <div id="crResizePanel" style="display:none;">
                <div class="cr-resize-area">
                    <div class="cr-resize-preview">
                        <img id="crResizePreview" src="" alt="Image to resize">
                    </div>
                    <div>
                        <p class="cr-orig-dims" id="crOrigDims"></p>
                        <div class="cr-resize-row">
                            <div class="cr-resize-field">
                                <label for="crResizeW">Width (px)</label>
                                <input type="number" id="crResizeW" class="form-input" min="1" max="10000" placeholder="Width">
                            </div>
                            <div class="cr-resize-field">
                                <label for="crResizeH">Height (px)</label>
                                <input type="number" id="crResizeH" class="form-input" min="1" max="10000" placeholder="Height">
                            </div>
                            <label class="cr-lock-label">
                                <input type="checkbox" id="crAspectLock" checked>
                                Lock ratio
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cr-footer">
            <div class="cr-filename-row" id="crFilenameRow">
                <label for="crNewName">Save as new file named</label>
                <input type="text" id="crNewName" class="form-input" placeholder="edited-image.jpg" maxlength="200">
            </div>
            <div class="cr-footer-actions">
                <span class="cr-status" id="crStatus"></span>
                <button type="button" class="btn btn-ghost" onclick="closeCrModal()">Cancel</button>
                <button type="button" class="btn btn-secondary" id="crReplaceBtn" onclick="saveCropResize(true)">Save and Replace</button>
                <button type="button" class="btn btn-primary" id="crSaveBtn" onclick="saveCropResize(false)">Save as New Image</button>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-lightbox" id="dashboardMediaLightbox" aria-hidden="true">
    <div class="dashboard-lightbox__backdrop" data-lightbox-close></div>
    <div class="dashboard-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Media preview">
        <div class="dashboard-lightbox__toolbar">
            <div class="dashboard-lightbox__meta">
                <div id="dashboardLightboxName"></div>
                <div id="dashboardLightboxMeta"></div>
            </div>
            <button type="button" class="dashboard-lightbox__close" data-lightbox-close aria-label="Close media preview">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="dashboard-lightbox__media">
            <img id="dashboardLightboxImage" class="dashboard-lightbox__image" alt="">
        </div>
    </div>
</div>

<!-- Upload progress toast -->
<div class="upload-progress" id="uploadProgress">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
        <span id="uploadProgressText" style="font-size:0.875rem;font-weight:500;">Uploading…</span>
        <span id="uploadProgressCount" style="font-size:0.8rem;color:var(--muted-foreground);"></span>
    </div>
    <div id="uploadProgressFilename" style="font-size:0.75rem;color:var(--muted-foreground);margin-top:0.25rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></div>
    <div class="progress-bar-track"><div class="progress-bar-fill" id="uploadProgressBar"></div></div>
</div>

<!-- Copy URL Modal -->
<div id="copyUrlModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-content-wrapper">
            <div class="modal-body">
                <div class="modal-body-inner">
                    <div class="modal-icon info">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                        </svg>
                    </div>
                    <div class="modal-text-content">
                        <h2 class="modal-title">Copy URL</h2>
                        <p class="modal-message">Which URL would you like to copy?</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="flex-direction: column; gap: 0.5rem;">
                <button type="button" class="btn btn-modal-confirm" style="width: 100%;" onclick="copySelectedUrl('original')">Original</button>
                <button type="button" class="btn btn-modal-confirm" id="copyUrlWebpBtn" style="width: 100%;" onclick="copySelectedUrl('webp')">WebP</button>
                <button type="button" class="btn btn-modal-confirm" style="width: 100%;" onclick="copySelectedUrl('thumbnail')">Thumbnail</button>
                <button type="button" class="btn btn-modal-cancel" style="width: 100%;" onclick="closeCopyUrlModal()">Cancel</button>
            </div>
        </div>
        <button type="button" class="modal-close" onclick="closeCopyUrlModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- Copy toast -->
<div class="copy-toast" id="copyToast">URL copied to clipboard!</div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    let MEDIA_CONFIG = window.dashboardMediaConfig || {};
    if (!Object.keys(MEDIA_CONFIG).length) {
        const configElement = document.getElementById('dashboardMediaConfig');
        if (configElement?.textContent) {
            try {
                MEDIA_CONFIG = JSON.parse(configElement.textContent);
            } catch {
                MEDIA_CONFIG = {};
            }
        }
    }
    const CSRF = MEDIA_CONFIG.csrfToken || '';
    const UPLOAD_URL  = MEDIA_CONFIG.uploadUrl || '';
    const DELETE_BASE = MEDIA_CONFIG.mediaUrl ? MEDIA_CONFIG.mediaUrl.replace(/\/+$/, '') + '/' : '/dashboard/media/';
    const RENAME_BASE  = '/dashboard/media/';
    const ALT_BASE    = '/dashboard/media/';

    const toggleUploadForm = document.getElementById('toggleUploadForm');
    const uploadPanel = document.getElementById('uploadPanel');
    const lightbox = document.getElementById('dashboardMediaLightbox');
    const lightboxImage = document.getElementById('dashboardLightboxImage');
    const lightboxName = document.getElementById('dashboardLightboxName');
    const lightboxMeta = document.getElementById('dashboardLightboxMeta');
    let previousFocus = null;
    let previousOverflow = '';

    toggleUploadForm?.addEventListener('click', () => {
        document.getElementById('fileInput')?.click();
    });

    // ── Drag & drop ───────────────────────────────────────────────────
    const uploadZone = document.getElementById('uploadZone');

    uploadZone.addEventListener('dragover', e => {
        e.preventDefault();
        uploadZone.classList.add('drag-over');
    });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
    uploadZone.addEventListener('drop', e => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        handleFiles(e.dataTransfer.files);
    });

    document.getElementById('fileInput').addEventListener('change', e => {
        handleFiles(e.target.files);
        e.target.value = '';
    });

    async function handleFiles(files) {
        const arr = Array.from(files);
        if (!arr.length) return;
        showUploadProgress(0, arr.length, arr[0].name);
        let done = 0;
        for (const file of arr) {
            await uploadFile(file, (pct) => {
                setUploadProgress(pct, done, arr.length, file.name);
            });
            done++;
            setUploadProgress(100, done, arr.length, 'Done!');
        }
        setTimeout(() => {
            hideUploadProgress();
            location.reload();
        }, 800);
    }

    function uploadFile(file, onProgress) {
        return new Promise((resolve) => {
            const fd = new FormData();
            fd.append('file', file);
            fd.append('_token', CSRF);

            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable && onProgress) {
                    onProgress(Math.round((e.loaded / e.total) * 100));
                }
            });

            xhr.addEventListener('loadend', () => resolve());

            xhr.open('POST', UPLOAD_URL);
            xhr.send(fd);
        });
    }

    function showUploadProgress(pct, total, filename) {
        const el = document.getElementById('uploadProgress');
        el.classList.add('show');
        setUploadProgress(pct, 0, total, filename);
    }
    function setUploadProgress(pct, done, total, filename) {
        document.getElementById('uploadProgressText').textContent = done < total ? 'Uploading…' : 'Done!';
        document.getElementById('uploadProgressCount').textContent = done + '/' + total;
        document.getElementById('uploadProgressBar').style.width = Math.round(pct) + '%';
        const fnEl = document.getElementById('uploadProgressFilename');
        if (fnEl) {
            fnEl.textContent = filename || '';
        }
    }
    function hideUploadProgress() {
        document.getElementById('uploadProgress').classList.remove('show');
    }

    // ── Copy URL ──────────────────────────────────────────────────────
    let copyUrlData = {};

    function showCopyUrlModal(originalUrl, webpUrl, thumbnailUrl) {
        copyUrlData = { original: originalUrl, webp: webpUrl, thumbnail: thumbnailUrl };
        const modal = document.getElementById('copyUrlModal');
        const webpBtn = document.getElementById('copyUrlWebpBtn');
        webpBtn.style.display = webpUrl ? 'block' : 'none';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCopyUrlModal() {
        const modal = document.getElementById('copyUrlModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        copyUrlData = {};
    }

    function copySelectedUrl(type) {
        const url = copyUrlData[type];
        if (!url) return;

        navigator.clipboard.writeText(url).then(() => {
            showCopyToast('URL copied to the clipboard!');
            closeCopyUrlModal();
        }).catch(() => {
            showAlert('Could not copy the URL automatically. Please copy it from the media item.', 'Copy Failed', {
                variant: 'info',
                confirmText: 'OK',
            });
        });
    }

    function showCopyToast(message = 'Copied to the clipboard!') {
        const t = document.getElementById('copyToast');
        t.textContent = message;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2000);
    }

    // Close copy URL modal on overlay click
    document.getElementById('copyUrlModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeCopyUrlModal();
        }
    });

    // Close copy URL modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && document.getElementById('copyUrlModal').classList.contains('active')) {
            closeCopyUrlModal();
        }
    });

    // ── Delete ────────────────────────────────────────────────────────
    function updateBulkMediaDeleteButtonState() {
        const checkedCount = document.querySelectorAll('.media-bulk-checkbox:checked').length;
        const button = document.getElementById('bulk-media-delete-btn');
        if (button) {
            button.style.display = checkedCount > 0 ? '' : 'none';
            button.textContent = checkedCount > 0 ? `Delete Selected (${checkedCount})` : 'Delete Selected';
        }

        const selectAll = document.getElementById('select-all-media');
        if (selectAll) {
            const checkboxes = Array.from(document.querySelectorAll('.media-bulk-checkbox'));
            selectAll.checked = checkboxes.length > 0 && checkboxes.every((checkbox) => checkbox.checked);
            selectAll.indeterminate = checkboxes.some((checkbox) => checkbox.checked) && !selectAll.checked;
        }
    }

    function submitBulkMediaDelete() {
        const checked = Array.from(document.querySelectorAll('.media-bulk-checkbox:checked'));
        if (!checked.length) return;

        showDanger('Delete Selected Media', `Delete ${checked.length} selected media ${checked.length === 1 ? 'file' : 'files'}? This action cannot be undone.`)
            .then((confirmed) => {
                if (!confirmed) return;

                const form = document.getElementById('bulk-media-delete-form');
                form.querySelectorAll('input[name="selected_ids[]"]').forEach((input) => input.remove());
                checked.forEach((checkbox) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });
                form.submit();
            });
    }

    async function deleteMedia(id, btn) {
        const confirmed = await showDanger('Delete File', 'Delete this file? This cannot be undone.');
        if (!confirmed) return;

        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '…';
        const res = await fetch(DELETE_BASE + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (res.ok) {
            const entry = document.getElementById('media-' + id);
            if (entry) {
                entry.style.transition = 'opacity 0.3s';
                entry.style.opacity = '0';
                setTimeout(() => {
                    entry.remove();
                    updateBulkMediaDeleteButtonState();
                }, 300);
            }
        } else {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            showAlert('Delete failed. Please try again.', 'Delete Failed', {
                variant: 'danger',
                confirmText: 'OK',
            });
        }
    }

    // ── Alt text ──────────────────────────────────────────────────────
    let altTimer;
    function saveAlt(id, value) {
        clearTimeout(altTimer);
        altTimer = setTimeout(async () => {
            const fd = new FormData();
            fd.append('_token', CSRF);
            fd.append('_method', 'PATCH');
            fd.append('alt_text', value);
            await fetch(ALT_BASE + id + '/alt', { method: 'POST', body: fd });
        }, 600);
    }

    // ── Rename ────────────────────────────────────────────────────────
    async function renameMedia(id, btn) {
        const current = btn.dataset.filename || '';
        const ext     = btn.dataset.extension || '';
        // Strip extension for display/prompt — user works with clean names
        const currentDisplay = ext && current.endsWith('.' + ext)
            ? current.slice(0, -(ext.length + 1))
            : current;

        const result = await showPrompt('Rename File', 'Enter a new name for this file (without extension):', currentDisplay, currentDisplay);
        if (result === false || result === null) return;   // cancelled
        const newDisplay = result.trim();
        if (!newDisplay || newDisplay === currentDisplay) return;  // blank or unchanged

        // Re-attach the original extension transparently
        const newName = ext ? newDisplay + '.' + ext : newDisplay;

        const fd = new FormData();
        fd.append('_token', CSRF);
        fd.append('_method', 'PATCH');
        fd.append('filename', newName);

        try {
            const res  = await fetch(RENAME_BASE + id + '/rename', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            if (res.ok && json.success) {
                const savedExt = json.filename.includes('.') ? json.filename.split('.').pop() : '';
                const savedDisplay = savedExt && json.filename.endsWith('.' + savedExt)
                    ? json.filename.slice(0, -(savedExt.length + 1))
                    : json.filename;

                const entry = btn.closest('[data-media-entry]');
                entry?.querySelectorAll('[data-media-name]').forEach((nameEl) => {
                    nameEl.textContent = savedDisplay;
                    nameEl.title = savedDisplay;
                });

                const tableFilename = entry?.querySelector('.media-table-filename');
                if (tableFilename) {
                    tableFilename.textContent = json.filename;
                }

                document.querySelectorAll(`[data-media-rename][data-media-id="${id}"]`).forEach((renameBtn) => {
                    renameBtn.dataset.filename = json.filename;
                });

                document.querySelectorAll(`.cr-edit-btn[data-media-id="${id}"]`).forEach((editBtn) => {
                    editBtn.dataset.filename = json.filename;
                });
            } else {
                showAlert(json.message || 'Rename failed. Please try again.', 'Rename Failed', { variant: 'danger', confirmText: 'OK' });
            }
        } catch {
            showAlert('Network error. Please try again.', 'Rename Failed', { variant: 'danger', confirmText: 'OK' });
        }
    }

    function openMediaLightbox(source) {
        if (!lightbox || !lightboxImage) return;

        previousFocus = document.activeElement;
        const imageSrc = source?.dataset?.imageSrc ?? source?.imageSrc ?? '';
        const imageAlt = source?.dataset?.imageAlt ?? source?.imageAlt ?? '';
        const imageNameText = source?.dataset?.imageName ?? source?.imageName ?? '';
        const imageMetaText = source?.dataset?.imageMeta ?? source?.imageMeta ?? '';

        lightboxImage.src = imageSrc;
        lightboxImage.alt = imageAlt || imageNameText || 'Media preview';
        lightboxName.textContent = imageNameText;
        lightboxMeta.textContent = imageMetaText;

        lightbox.classList.add('open');
        lightbox.setAttribute('aria-hidden', 'false');
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        lightbox.querySelector('[data-lightbox-close]')?.focus();
    }

    function closeMediaLightbox() {
        if (!lightbox || !lightboxImage) return;

        lightbox.classList.remove('open');
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImage.src = '';
        document.body.style.overflow = previousOverflow;

        if (previousFocus instanceof HTMLElement) {
            previousFocus.focus();
        }

        previousFocus = null;
    }

    document.addEventListener('click', (event) => {
        if (event.target.closest('.media-bulk-check, .media-table-select')) {
            event.stopPropagation();
            return;
        }

        const trigger = event.target.closest('[data-lightbox-trigger]');
        if (!trigger) return;
        openMediaLightbox(trigger);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter' && event.key !== ' ') return;
        const trigger = event.target.closest('[data-lightbox-trigger]');
        if (!trigger) return;
        event.preventDefault();
        openMediaLightbox(trigger);
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.media-bulk-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('click', (event) => event.stopPropagation());
            checkbox.addEventListener('change', updateBulkMediaDeleteButtonState);
        });

        const selectAll = document.getElementById('select-all-media');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.media-bulk-checkbox').forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
                updateBulkMediaDeleteButtonState();
            });
        }

        updateBulkMediaDeleteButtonState();
    });

    lightbox?.addEventListener('click', (event) => {
        if (event.target instanceof Element && event.target.closest('[data-lightbox-close]')) {
            closeMediaLightbox();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && lightbox?.classList.contains('open')) {
            event.preventDefault();
            event.stopImmediatePropagation();
            closeMediaLightbox();
        }
    });

    // ── Crop/Resize Modal ─────────────────────────────────────────────
    let cropper     = null;
    let crMediaId   = null;
    let crNaturalW  = 0;
    let crNaturalH  = 0;
    let crMode      = 'crop';

    // Decode HTML entities from data-* attributes
    function crDecodeHtml(str) {
        const el = document.createElement('span');
        el.innerHTML = str;
        return el.textContent;
    }

    // Event delegation for Edit buttons
    document.addEventListener('click', e => {
        const btn = e.target.closest('.cr-edit-btn');
        if (btn) openCropResize(btn.dataset.mediaId, btn.dataset.url, crDecodeHtml(btn.dataset.filename));
    });

    document.getElementById('crBackdrop').addEventListener('click', closeCrModal);

    function openCropResize(id, url, filename) {
        crMediaId = id;
        crMode    = 'crop';
        crNaturalW = crNaturalH = 0;

        document.getElementById('crSourceName').textContent = filename;
        document.getElementById('crNewName').value = 'edited-' + filename;
        document.getElementById('crStatus').textContent = '';
        document.getElementById('crSaveBtn').disabled = false;
        document.getElementById('crCropDims').textContent = '–';

        if (cropper) { cropper.destroy(); cropper = null; }

        const cropImg   = document.getElementById('crImage');
        const resizeImg = document.getElementById('crResizePreview');
        cropImg.src   = url;
        resizeImg.src = url;

        cropImg.decode().then(() => {
            crNaturalW = cropImg.naturalWidth;
            crNaturalH = cropImg.naturalHeight;
            document.getElementById('crOrigDims').textContent = `Original: ${crNaturalW} × ${crNaturalH} px`;
            document.getElementById('crResizeW').value = crNaturalW;
            document.getElementById('crResizeH').value = crNaturalH;
            if (crMode === 'crop') initCropper(cropImg);
        }).catch(() => {});

        setCrMode('crop');
        const modal = document.getElementById('crModal');
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function initCropper(img) {
        if (cropper) { cropper.destroy(); cropper = null; }
        cropper = new Cropper(img, {
            viewMode: 1,
            dragMode: 'crop',
            autoCropArea: 1,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            crop(event) {
                const d = event.detail;
                document.getElementById('crCropDims').textContent =
                    `${Math.round(d.width)} × ${Math.round(d.height)} px  at  (${Math.round(d.x)}, ${Math.round(d.y)})`;
            },
        });
    }

    function setCrMode(mode) {
        crMode = mode;
        const isCrop = mode === 'crop';
        document.getElementById('tabCrop').classList.toggle('cr-tab--active', isCrop);
        document.getElementById('tabResize').classList.toggle('cr-tab--active', !isCrop);
        document.getElementById('crCropPanel').style.display  = isCrop ? '' : 'none';
        document.getElementById('crResizePanel').style.display = isCrop ? 'none' : '';
        if (!isCrop) {
            if (cropper) { cropper.destroy(); cropper = null; }
        } else {
            const cropImg = document.getElementById('crImage');
            if (cropImg.naturalWidth > 0) {
                requestAnimationFrame(() => initCropper(cropImg));
            }
        }
    }

    function closeCrModal() {
        if (cropper) { cropper.destroy(); cropper = null; }
        const modal = document.getElementById('crModal');
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.getElementById('crImage').src = '';
        document.getElementById('crResizePreview').src = '';
    }

    // Aspect ratio lock for resize inputs
    document.getElementById('crResizeW').addEventListener('input', () => {
        if (document.getElementById('crAspectLock').checked && crNaturalH > 0) {
            const w = parseInt(document.getElementById('crResizeW').value);
            if (w > 0) document.getElementById('crResizeH').value = Math.round(w * crNaturalH / crNaturalW);
        }
    });
    document.getElementById('crResizeH').addEventListener('input', () => {
        if (document.getElementById('crAspectLock').checked && crNaturalW > 0) {
            const h = parseInt(document.getElementById('crResizeH').value);
            if (h > 0) document.getElementById('crResizeW').value = Math.round(h * crNaturalW / crNaturalH);
        }
    });

    async function saveCropResize(replace = false) {
        if (replace) {
            const confirmed = await showConfirm(
                'Replace Original Image',
                'This will permanently overwrite the original image with the resized version. This action cannot be undone. Are you sure you want to continue?',
                { variant: 'danger', confirmText: 'Yes, Replace', cancelText: 'Cancel' }
            );
            if (!confirmed) return;
        }

        const saveBtn    = document.getElementById('crSaveBtn');
        const replaceBtn = document.getElementById('crReplaceBtn');
        const status     = document.getElementById('crStatus');

        const fd = new FormData();
        fd.append('_token', CSRF);
        fd.append('mode', crMode);
        fd.append('replace', replace ? '1' : '0');

        if (!replace) {
            const newName = document.getElementById('crNewName').value.trim();
            if (!newName) {
                showAlert('Please enter a filename for the new image.', 'Missing Filename', { variant: 'warning', confirmText: 'OK' });
                return;
            }
            fd.append('new_filename', newName);
        }

        saveBtn.disabled = true;
        replaceBtn.disabled = true;
        status.textContent = replace ? 'Replacing…' : 'Saving…';

        if (crMode === 'crop') {
            if (!cropper) { status.textContent = 'Cropper not ready.'; saveBtn.disabled = false; replaceBtn.disabled = false; return; }
            const data = cropper.getData(true);
            if (data.width < 1 || data.height < 1) {
                status.textContent = 'Please select a crop area.'; saveBtn.disabled = false; replaceBtn.disabled = false; return;
            }
            fd.append('crop_x', data.x);
            fd.append('crop_y', data.y);
            fd.append('crop_width', data.width);
            fd.append('crop_height', data.height);
        } else {
            const w = parseInt(document.getElementById('crResizeW').value) || 0;
            const h = parseInt(document.getElementById('crResizeH').value) || 0;
            if (!w && !h) { status.textContent = 'Enter a width or height.'; saveBtn.disabled = false; replaceBtn.disabled = false; return; }
            if (w) fd.append('resize_width', w);
            if (h) fd.append('resize_height', h);
        }

        try {
            const res  = await fetch(`/dashboard/media/${crMediaId}/crop-resize`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });
            const json = await res.json();
            if (res.ok && json.id) {
                status.textContent = replace ? 'Replaced! Reloading…' : 'Saved! Reloading…';
                setTimeout(() => { closeCrModal(); location.reload(); }, 900);
            } else {
                status.textContent = json.message || 'Failed to save.';
                saveBtn.disabled = false;
                replaceBtn.disabled = false;
            }
        } catch (err) {
            status.textContent = 'Network error. Please try again.';
            saveBtn.disabled = false;
            replaceBtn.disabled = false;
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && document.getElementById('crModal').classList.contains('open')) closeCrModal();
    });

    // ── Image Importer ────────────────────────────────────────────────
    const II_SEARCH_URL = MEDIA_CONFIG.searchUrl || '';
    const II_IMPORT_URL = MEDIA_CONFIG.importUrl || '';
    const II_STARRED_STORE_URL = MEDIA_CONFIG.starredStoreUrl || '';
    const II_STARRED_DELETE_URL = MEDIA_CONFIG.starredDeleteUrl || '';
    const II_KEYS       = MEDIA_CONFIG.importerKeys || {};
    const II_INITIAL_STARRED_IMAGES = MEDIA_CONFIG.starredImages || [];
    const II_LEGACY_STARRED_STORAGE_KEY = 'dashboard.media.importer.starred.v1';

    const iiProviderLabels = {
        freepik: 'Freepik',
        pixabay: 'Pixabay',
        unsplash: 'Unsplash',
        pexels: 'Pexels',
        starred: 'Starred',
    };

    const iiNoKeyLabels = {
        freepik:  { title: 'Freepik API Key Required'  },
        pixabay:  { title: 'Pixabay API Key Required'  },
        unsplash: { title: 'Unsplash Access Key Required' },
        pexels:   { title: 'Pexels API Key Required'   },
    };

    let iiCurrentProvider = 'freepik';
    let iiCurrentPage     = 1;
    let iiTotalPages      = 1;
    let iiTotalResults    = 0;
    let iiImages          = [];
    const iiProviderState = new Map();
    let iiStarredImages   = iiSortStarredImages(Array.isArray(II_INITIAL_STARRED_IMAGES) ? II_INITIAL_STARRED_IMAGES : []);
    let iiStarredBootstrapPromise = null;
    let iiHasImported     = false;

    function iiProviderLabel(provider) {
        return iiProviderLabels[provider] || 'Image';
    }

    function iiCloneImageData(img) {
        try {
            return typeof structuredClone === 'function'
                ? structuredClone(img)
                : JSON.parse(JSON.stringify(img));
        } catch {
            return JSON.parse(JSON.stringify(img ?? {}));
        }
    }

    function iiResolveSourceUrl(data, provider = '') {
        const raw = data?.raw && typeof data.raw === 'object' ? data.raw : {};
        const normalizedProvider = provider || data?.provider || data?.source || '';
        const directUrl = data?.source_url || raw?.source_url;

        if (directUrl) return directUrl;

        if (normalizedProvider === 'unsplash') {
            return raw?.links?.html || raw?.links?.self || raw?.url || '';
        }

        if (normalizedProvider === 'pixabay') {
            return raw?.pageURL || raw?.pageUrl || raw?.page_url || raw?.url || '';
        }

        if (normalizedProvider === 'freepik') {
            return raw?.url || raw?.page_url || raw?.share_url || '';
        }

        if (normalizedProvider === 'pexels') {
            return raw?.url || '';
        }

        return raw?.pageURL || raw?.page_url || raw?.share_url || raw?.url || raw?.links?.html || raw?.links?.self || data?.preview || data?.download_url || data?.thumb || '';
    }

    function iiNormalizeImage(provider, img) {
        const data = img && typeof img === 'object' ? img : {};
        const normalizedProvider = provider || data.provider || data.source || '';

        return {
            star_key: data.star_key ?? data.starKey ?? null,
            provider: normalizedProvider,
            id: String(data.id ?? data.download_location ?? data.download_url ?? data.preview ?? data.thumb ?? ''),
            alt: data.alt ?? data.title ?? data.description ?? '',
            author: data.author ?? data.user ?? data.photographer ?? '',
            thumb: data.thumb ?? data.preview ?? data.download_url ?? '',
            preview: data.preview ?? data.full ?? data.download_url ?? data.thumb ?? '',
            download_url: data.download_url ?? data.preview ?? data.full ?? data.thumb ?? '',
            download_location: data.download_location ?? null,
            source_url: iiResolveSourceUrl(data, normalizedProvider) || null,
            raw: data.raw ?? iiCloneImageData(data),
            starred_at: data.starred_at ?? data.starredAt ?? null,
        };
    }

    function iiHydrateStoredImage(img) {
        if (!img || typeof img !== 'object') return null;
        const normalized = iiNormalizeImage(img.provider ?? img.source ?? '', img);
        if (!normalized.provider) return null;
        normalized.star_key = img.star_key ?? img.starKey ?? normalized.star_key ?? null;
        normalized.starred_at = img.starred_at ?? img.starredAt ?? normalized.starred_at ?? new Date().toISOString();
        return normalized;
    }

    function iiStarKeySource(img) {
        return [
            img?.provider || '',
            img?.download_location || '',
            img?.download_url || '',
            img?.id || '',
        ].join('|');
    }

    async function iiSha256Hex(value) {
        if (!window.crypto?.subtle) return null;

        const buffer = await window.crypto.subtle.digest('SHA-256', new TextEncoder().encode(value));
        return Array.from(new Uint8Array(buffer), byte => byte.toString(16).padStart(2, '0')).join('');
    }

    async function iiResolveStarKey(img) {
        if (img?.star_key && String(img.star_key).length === 64) {
            return img.star_key;
        }

        const source = iiStarKeySource(img);
        const key = await iiSha256Hex(source);
        return key || null;
    }

    function iiSortStarredImages(images) {
        return images
            .map(iiHydrateStoredImage)
            .filter(Boolean)
            .sort((a, b) => (b.starred_at || '').localeCompare(a.starred_at || ''));
    }

    function iiSetStarredImages(images) {
        iiStarredImages = iiSortStarredImages(images);
        return iiStarredImages;
    }

    function iiLoadLegacyStarredImages() {
        try {
            const stored = JSON.parse(localStorage.getItem(II_LEGACY_STARRED_STORAGE_KEY) || '[]');
            return Array.isArray(stored) ? iiSortStarredImages(stored) : [];
        } catch {
            return [];
        }
    }

    async function iiSerializeStarredImage(img) {
        const normalized = iiNormalizeImage(img.provider || iiCurrentProvider, img);
        const starKey = await iiResolveStarKey(img);

        return {
            star_key: starKey || img?.star_key || normalized.star_key || null,
            provider: normalized.provider,
            id: normalized.id,
            alt: normalized.alt,
            author: normalized.author,
            thumb: normalized.thumb,
            preview: normalized.preview,
            download_url: normalized.download_url,
            download_location: normalized.download_location,
            source_url: normalized.source_url,
            raw: normalized.raw,
            starred_at: img?.starred_at || normalized.starred_at || new Date().toISOString(),
        };
    }

    async function iiPersistStarredImage(img) {
        const res = await fetch(II_STARRED_STORE_URL, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(await iiSerializeStarredImage(img)),
        });

        const json = await res.json().catch(() => ({}));
        if (!res.ok || !json.data) {
            throw new Error(json.message || 'Could not save starred image.');
        }

        return iiHydrateStoredImage(json.data);
    }

    async function iiDeletePersistedStarredImage(img) {
        const res = await fetch(II_STARRED_DELETE_URL, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                star_key: img?.star_key || null,
                provider: img?.provider || iiCurrentProvider,
                id: img?.id || null,
                download_url: img?.download_url || null,
                download_location: img?.download_location || null,
            }),
        });

        const json = await res.json().catch(() => ({}));
        if (!res.ok || json.success !== true) {
            throw new Error(json.message || 'Could not remove starred image.');
        }
    }

    function iiRefreshStarredImages() {
        iiSetStarredImages(iiStarredImages);
    }

    async function iiMigrateLegacyStarredImages() {
        const legacyImages = iiLoadLegacyStarredImages();
        if (!legacyImages.length) return;

        let allMigrated = true;

        for (const image of legacyImages) {
            try {
                const persisted = await iiPersistStarredImage(image);
                iiSetStarredImages([persisted, ...iiStarredImages.filter(item => iiStarKey(item) !== iiStarKey(persisted))]);
            } catch {
                allMigrated = false;
            }
        }

        if (allMigrated) {
            localStorage.removeItem(II_LEGACY_STARRED_STORAGE_KEY);
        }
    }

    function iiBootStarredImages() {
        if (!iiStarredBootstrapPromise) {
            iiStarredBootstrapPromise = iiMigrateLegacyStarredImages().finally(() => {
                iiRefreshCurrentView();
            });
        }

        return iiStarredBootstrapPromise;
    }

    function iiStarKey(img) {
        return img?.star_key || [
            img?.provider || '',
            img?.download_location || '',
            img?.download_url || '',
            img?.id || '',
        ].join('::');
    }

    function iiFindStarredMatch(img) {
        const imgComposite = [img?.provider||'', img?.download_location||'', img?.download_url||'', img?.id||''].join('::');
        return iiStarredImages.find(item => {
            if (img?.star_key && item?.star_key) return img.star_key === item.star_key;
            const itemComposite = [item?.provider||'', item?.download_location||'', item?.download_url||'', item?.id||''].join('::');
            return imgComposite === itemComposite;
        }) ?? null;
    }

    function iiIsStarred(img) {
        return iiFindStarredMatch(img) !== null;
    }

    function iiStarButtonSvg(isStarred) {
        return isStarred
            ? `<svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.75.75 0 0 1 1.04 0l2.76 2.666a.75.75 0 0 0 .53.216h3.813a.75.75 0 0 1 .438 1.358l-3.084 2.17a.75.75 0 0 0-.273.84l1.167 3.666a.75.75 0 0 1-1.152.84l-3.152-2.172a.75.75 0 0 0-.86 0l-3.152 2.172a.75.75 0 0 1-1.152-.84l1.167-3.666a.75.75 0 0 0-.273-.84L4.892 7.739a.75.75 0 0 1 .438-1.358h3.813a.75.75 0 0 0 .53-.216l2.76-2.666Z"/></svg>`
            : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.75.75 0 0 1 1.04 0l2.76 2.666a.75.75 0 0 0 .53.216h3.813a.75.75 0 0 1 .438 1.358l-3.084 2.17a.75.75 0 0 0-.273.84l1.167 3.666a.75.75 0 0 1-1.152.84l-3.152-2.172a.75.75 0 0 0-.86 0l-3.152 2.172a.75.75 0 0 1-1.152-.84l1.167-3.666a.75.75 0 0 0-.273-.84L4.892 7.739a.75.75 0 0 1 .438-1.358h3.813a.75.75 0 0 0 .53-.216l2.76-2.666Z"/></svg>`;
    }

    function iiGetSourceUrl(img) {
        return iiResolveSourceUrl(img, img?.provider || '');
    }

    function iiOpenSourceUrl(img) {
        const sourceUrl = iiGetSourceUrl(img);
        if (!sourceUrl) {
            showAlert('No URL is available for this image.', 'Open URL Failed', {
                variant: 'info',
                confirmText: 'OK',
            });
            return;
        }

        window.open(sourceUrl, '_blank', 'noopener,noreferrer');
    }

    function iiCaptureCurrentState() {
        if (iiCurrentProvider === 'starred') {
            return;
        }

        const nokeyVisible = document.getElementById('iiNoKey')?.style.display === '';
        const resultsVisible = document.getElementById('iiResultsWrap')?.style.display === '';
        const loadingVisible = document.getElementById('iiLoading')?.style.display === '';
        const emptyVisible = document.getElementById('iiEmpty')?.style.display === '';
        const errorVisible = document.getElementById('iiError')?.style.display === '';
        const input = document.getElementById('iiSearchInput');

        iiProviderState.set(iiCurrentProvider, {
            query: input?.value || '',
            page: iiCurrentPage,
            totalPages: iiTotalPages,
            totalResults: iiTotalResults,
            images: iiImages.map(iiCloneImageData),
            zone: resultsVisible ? 'results' : loadingVisible ? 'loading' : emptyVisible ? 'empty' : errorVisible ? 'error' : nokeyVisible ? 'nokey' : 'idle',
        });
    }

    function iiRestoreProviderState(provider) {
        const state = iiProviderState.get(provider);
        if (!state) {
            return false;
        }

        iiCurrentPage = state.page || 1;
        iiTotalPages = state.totalPages || 1;
        iiTotalResults = state.totalResults || 0;
        iiImages = Array.isArray(state.images) ? state.images.map(iiCloneImageData) : [];

        const input = document.getElementById('iiSearchInput');
        if (input) {
            input.value = state.query || '';
            input.disabled = false;
            input.placeholder = 'Search images…';
        }

        document.getElementById('iiResultsCount').textContent =
            iiTotalResults > 0 ? `${iiTotalResults.toLocaleString()} result${iiTotalResults !== 1 ? 's' : ''}` : '';

        if (state.zone === 'results') {
            iiRenderResults(iiImages);
        }

        const zone = state.zone || (iiImages.length ? 'results' : 'idle');
        iiShowZone(zone, { showSearchBar: zone !== 'nokey' });

        if (zone === 'nokey') {
            iiSetNoKeyMessage(provider);
        }

        iiUpdatePagination();

        return true;
    }

    function iiResetImporterState() {
        iiCurrentProvider = 'freepik';
        iiCurrentPage = 1;
        iiTotalPages = 1;
        iiTotalResults = 0;
        iiImages = [];

        const input = document.getElementById('iiSearchInput');
        if (input) {
            input.value = '';
            input.disabled = false;
            input.placeholder = 'Search images…';
        }

        document.querySelectorAll('.ii-tab').forEach(btn => {
            btn.classList.toggle('ii-tab--active', btn.dataset.iiProvider === 'freepik');
        });

        document.getElementById('iiResults').innerHTML = '';
        document.getElementById('iiResultsCount').textContent = '';
        document.getElementById('iiEmptyMsg').textContent = 'No images found. Try a different keyword.';
        iiShowZone('idle', { showSearchBar: true });
        iiUpdatePagination();
    }

    document.getElementById('openImageImporter').addEventListener('click', () => openIIModal());
    document.getElementById('iiBackdrop').addEventListener('click', closeIIModal);
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if (lightbox?.classList.contains('open')) return;
        if (document.getElementById('iiModal').classList.contains('open')) closeIIModal();
    });

    function openIIModal() {
        const modal = document.getElementById('iiModal');
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        iiBootStarredImages();
        iiSwitchTab(iiCurrentProvider);
        if (iiCurrentProvider !== 'starred') {
            document.getElementById('iiSearchInput')?.focus();
        }
    }

    function closeIIModal() {
        const modal = document.getElementById('iiModal');
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        iiProviderState.clear();
        iiResetImporterState();
        if (iiHasImported) {
            iiHasImported = false;
            location.reload();
        }
    }

    async function iiSwitchTab(provider) {
        if (iiCurrentProvider !== provider) {
            iiCaptureCurrentState();
        }
        iiCurrentProvider = provider;

        // Update tab active state
        document.querySelectorAll('.ii-tab').forEach(btn => {
            btn.classList.toggle('ii-tab--active', btn.dataset.iiProvider === provider);
        });

        // Reset search input
        const input = document.getElementById('iiSearchInput');
        if (input) {
            input.value = '';
            input.disabled = provider === 'starred';
            input.placeholder = provider === 'starred'
                ? 'Starred images are saved to your account.'
                : 'Search images…';
        }

        if (provider !== 'starred' && iiRestoreProviderState(provider)) {
            return;
        }

        if (provider === 'starred') {
            iiShowZone('loading', { showSearchBar: false });
            await iiBootStarredImages();
            iiRenderStarredView();
            return;
        }

        iiCurrentPage = 1;
        iiTotalPages = 1;
        iiTotalResults = 0;
        iiImages = [];

        // Update results display
        document.getElementById('iiResults').innerHTML = '';
        document.getElementById('iiResultsCount').textContent = '';
        document.getElementById('iiEmptyMsg').textContent = 'No images found. Try a different keyword.';

        iiUpdatePagination();

        const hasKey = II_KEYS[provider] === true;
        iiShowZone(hasKey ? 'idle' : 'nokey', { showSearchBar: hasKey });

        if (!hasKey) {
            iiSetNoKeyMessage(provider);
        }
    }

    function iiSetNoKeyMessage(provider) {
        const label = iiNoKeyLabels[provider];
        document.getElementById('iiNoKeyTitle').textContent = label.title;
        const settingsUrl = MEDIA_CONFIG.settingsUrl || '/dashboard/settings/system';
        document.getElementById('iiNoKeyMsg').innerHTML =
            `No API key configured for this provider. Add your key in ` +
            `<a href="${settingsUrl}" style="color:var(--primary);text-decoration:underline;">Dashboard Settings &rarr; Media</a>.`;
    }

    function iiShowZone(zone, options = {}) {
        // zones: nokey | idle | loading | results | empty | error
        const showSearchBar = options.showSearchBar ?? zone !== 'nokey';
        const noKey      = document.getElementById('iiNoKey');
        const searchBar  = document.getElementById('iiSearchBar');
        const resultsWrap = document.getElementById('iiResultsWrap');
        const idle       = document.getElementById('iiIdle');
        const loading    = document.getElementById('iiLoading');
        const empty      = document.getElementById('iiEmpty');
        const errZ       = document.getElementById('iiError');

        noKey.style.display       = zone === 'nokey'   ? '' : 'none';
        searchBar.style.display   = showSearchBar      ? '' : 'none';
        resultsWrap.style.display = zone === 'results' ? '' : 'none';
        idle.style.display        = zone === 'idle'    ? '' : 'none';
        loading.style.display     = zone === 'loading' ? '' : 'none';
        empty.style.display       = zone === 'empty'   ? '' : 'none';
        errZ.style.display        = zone === 'error'   ? '' : 'none';
    }

    async function iiSearch(page = 1) {
        if (iiCurrentProvider === 'starred') {
            iiRenderStarredView();
            return;
        }

        await iiBootStarredImages();

        const q = document.getElementById('iiSearchInput').value.trim();
        if (!q) return;

        iiCurrentPage = page;
        iiShowZone('loading');
        iiUpdatePagination();

        try {
            const params = new URLSearchParams({ provider: iiCurrentProvider, q, page });
            const res    = await fetch(`${II_SEARCH_URL}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const json = await res.json();

            if (!res.ok) {
                document.getElementById('iiErrorMsg').textContent = json.message || 'Search failed. Please try again.';
                iiShowZone('error');
                return;
            }

            if (json.error === 'no_key') {
                iiShowZone('nokey');
                return;
            }

            const images = json.images || [];
            const normalizedImages = images.map(image => iiNormalizeImage(iiCurrentProvider, image));
            iiTotalPages   = json.total_pages || 1;
            iiTotalResults = json.total || images.length;

            if (normalizedImages.length === 0) {
                iiImages = [];
                document.getElementById('iiEmptyMsg').textContent = 'No images found. Try a different keyword.';
                iiShowZone('empty', { showSearchBar: true });
                document.getElementById('iiResultsCount').textContent = '';
                iiUpdatePagination();
                iiCaptureCurrentState();
                return;
            }

            document.getElementById('iiResultsCount').textContent =
                `${iiTotalResults.toLocaleString()} result${iiTotalResults !== 1 ? 's' : ''}`;

            iiRenderResults(normalizedImages);
            iiShowZone('results', { showSearchBar: true });
            iiUpdatePagination();
            iiCaptureCurrentState();
        } catch (err) {
            iiImages = [];
            document.getElementById('iiErrorMsg').textContent = 'Network error. Please try again.';
            iiShowZone('error', { showSearchBar: true });
            iiCaptureCurrentState();
        }
    }

    // Image data store — avoids embedding JSON in onclick attributes (which breaks on quotes/special chars)
    function iiRenderResults(images) {
        iiImages = images; // store so event delegation can look them up safely
        const grid = document.getElementById('iiResults');
        grid.innerHTML = '';

        images.forEach((img, idx) => {
            const provider = img.provider || iiCurrentProvider;
            const isStarred = iiIsStarred(img);
            const previewLabel = img.alt || `${iiProviderLabel(provider)} image ${idx + 1}`;

            const card = document.createElement('div');
            card.className = 'ii-img-card';
            card.dataset.iiIdx = idx;
            card.tabIndex = 0;
            card.setAttribute('role', 'button');
            card.setAttribute('aria-label', `Open URL for ${previewLabel}`);
            card.title = 'Open URL';
            card.innerHTML = `
                <img src="${escHtml(img.thumb || img.preview || img.download_url)}" alt="${escHtml(previewLabel)}" loading="lazy">
                <button type="button" class="ii-star-toggle ${isStarred ? 'is-starred' : ''}" data-ii-star-idx="${idx}" aria-label="${isStarred ? 'Unstar image' : 'Star image'}" title="${isStarred ? 'Unstar image' : 'Star image'}">
                    ${iiStarButtonSvg(isStarred)}
                </button>
                <div class="ii-img-overlay">
                    <div class="ii-preview-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                        Open URL
                    </div>
                    ${img.alt ? `<div class="ii-img-caption">${escHtml(img.alt)}</div>` : ''}
                    ${img.author ? `<div class="ii-img-caption" style="opacity:0.6;font-size:0.65rem;">by ${escHtml(img.author)}</div>` : ''}
                    ${iiCurrentProvider === 'starred' ? `<div class="ii-img-caption" style="opacity:0.72;font-size:0.65rem;">Saved from ${escHtml(iiProviderLabel(provider))}</div>` : ''}
                    <button type="button" class="ii-import-btn" data-ii-idx="${idx}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Import
                    </button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function iiRefreshCurrentView() {
        if (iiCurrentProvider === 'starred') {
            iiRenderStarredView();
            return;
        }

        if (iiImages.length) {
            iiRenderResults(iiImages);
        }
    }

    async function iiStarImage(img) {
        const normalized = iiNormalizeImage(img.provider || iiCurrentProvider, img);
        normalized.star_key = await iiResolveStarKey(normalized);
        normalized.starred_at = new Date().toISOString();

        const previous = [...iiStarredImages];
        if (normalized.star_key) {
            iiSetStarredImages([normalized, ...iiStarredImages.filter(item => iiStarKey(item) !== iiStarKey(normalized))]);
            iiRefreshCurrentView();
        }

        try {
            const persisted = await iiPersistStarredImage(normalized);
            iiSetStarredImages([persisted, ...iiStarredImages.filter(item => iiStarKey(item) !== iiStarKey(persisted))]);
            iiRefreshCurrentView();
        } catch (error) {
            iiSetStarredImages(previous);
            iiRefreshCurrentView();
            showAlert(error.message || 'Could not save this starred image.', 'Star Failed', {
                variant: 'danger',
                confirmText: 'OK',
            });
        }
    }

    async function iiUnstarImage(img) {
        const matchedItem = iiFindStarredMatch(img);
        if (!matchedItem) return;

        const matchedKey = iiStarKey(matchedItem);
        const previous = [...iiStarredImages];
        const next = iiStarredImages.filter(item => iiStarKey(item) !== matchedKey);
        if (next.length === iiStarredImages.length) return;

        iiSetStarredImages(next);
        iiRefreshCurrentView();

        try {
            await iiDeletePersistedStarredImage(matchedItem);
        } catch (error) {
            iiSetStarredImages(previous);
            iiRefreshCurrentView();
            showAlert(error.message || 'Could not remove this starred image.', 'Unstar Failed', {
                variant: 'danger',
                confirmText: 'OK',
            });
        }
    }

    function iiRenderStarredView() {
        iiProviderState.delete('starred');
        iiRefreshStarredImages();
        iiImages = iiStarredImages;
        iiTotalPages = 1;
        iiTotalResults = iiStarredImages.length;
        document.getElementById('iiResultsCount').textContent =
            iiTotalResults > 0 ? `${iiTotalResults.toLocaleString()} starred image${iiTotalResults !== 1 ? 's' : ''}` : '';

        if (!iiStarredImages.length) {
            document.getElementById('iiEmptyMsg').textContent = 'No starred images yet. Star images from any provider to save them to your account.';
            iiShowZone('empty', { showSearchBar: false });
            iiUpdatePagination(true);
            return;
        }

        iiRenderResults(iiStarredImages);
        iiShowZone('results', { showSearchBar: false });
        iiUpdatePagination(true);
    }

    // Event delegation for star/import actions — avoids any JSON-in-attribute issues
    document.getElementById('iiResults').addEventListener('click', async e => {
        const starBtn = e.target.closest('.ii-star-toggle');
        if (starBtn) {
            e.preventDefault();
            e.stopPropagation();

            const idx = parseInt(starBtn.dataset.iiStarIdx, 10);
            if (isNaN(idx) || !iiImages[idx]) return;

            const img = iiImages[idx];
            if (iiIsStarred(img)) {
                await iiUnstarImage(img);
            } else {
                await iiStarImage(img);
            }
            return;
        }

        const btn = e.target.closest('.ii-import-btn');
        if (!btn || btn.disabled || btn.classList.contains('done')) return;
        e.stopPropagation();
        const idx = parseInt(btn.dataset.iiIdx, 10);
        if (isNaN(idx) || !iiImages[idx]) return;
        const img = iiImages[idx];

        const suggested = iiSuggestFilename(img);
        const promptResult = await showPrompt(
            'Save Image As',
            'Enter a filename to save this image as, or leave blank to use the suggested name.',
            suggested,
            suggested
        );
        // false = user cancelled the prompt → abort
        if (promptResult === false) return;
        const filename = (typeof promptResult === 'string' && promptResult.trim()) ? promptResult.trim() : suggested;

        iiImport(btn, img, filename);
    });

    document.getElementById('iiResults').addEventListener('click', e => {
        const card = e.target.closest('.ii-img-card');
        if (!card || e.target.closest('.ii-import-btn') || e.target.closest('.ii-star-toggle')) return;

        const idx = parseInt(card.dataset.iiIdx, 10);
        if (isNaN(idx) || !iiImages[idx]) return;

        iiOpenSourceUrl(iiImages[idx]);
    });

    document.getElementById('iiResults').addEventListener('keydown', e => {
        const card = e.target.closest('.ii-img-card');
        if (!card || e.target.closest('.ii-import-btn') || e.target.closest('.ii-star-toggle')) return;
        if (e.key !== 'Enter' && e.key !== ' ') return;

        e.preventDefault();
        card.click();
    });

    const II_IMPORT_SVG_DL   = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>`;
    const II_IMPORT_SVG_DONE = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:11px;height:11px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`;

    function iiShowImportProgress(text, pct) {
        const bar = document.getElementById('iiImportProgress');
        bar.classList.add('show');
        document.getElementById('iiImportProgressText').textContent = text;
        document.getElementById('iiImportProgressFill').style.width = pct + '%';
    }
    function iiHideImportProgress() {
        const bar = document.getElementById('iiImportProgress');
        bar.classList.remove('show');
        document.getElementById('iiImportProgressFill').style.width = '0%';
    }

    async function iiImport(btn, img, filename) {
        btn.disabled = true;
        btn.innerHTML = `<div class="ii-spinner" style="width:11px;height:11px;border-width:2px;"></div> Importing…`;
        iiShowImportProgress('Downloading image from provider…', 30);

        try {
            const sourceProvider = img.provider || iiCurrentProvider;
            const fd = new FormData();
            fd.append('_token', CSRF);
            fd.append('source', sourceProvider);
            fd.append('url', img.download_url);
            if (img.source_url) fd.append('source_url', img.source_url);
            fd.append('filename', filename);
            if (img.download_location) fd.append('download_location', img.download_location);

            iiShowImportProgress('Downloading image from provider…', 55);

            const res = await fetch(II_IMPORT_URL, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });

            iiShowImportProgress('Saving to media library…', 85);

            let json = {};
            const contentType = res.headers.get('Content-Type') || '';
            if (contentType.includes('application/json')) {
                json = await res.json();
            }

            iiShowImportProgress('Done!', 100);
            setTimeout(iiHideImportProgress, 900);

            if (res.ok && json.id) {
                btn.classList.add('done');
                btn.innerHTML = `${II_IMPORT_SVG_DONE} Imported`;
                iiHasImported = true;
                showIISuccessToast();
            } else {
                btn.disabled = false;
                btn.innerHTML = `${II_IMPORT_SVG_DL} Import`;
                showAlert(json.message || 'Import failed. Please try again.', 'Import Failed', { variant: 'danger', confirmText: 'OK' });
                iiHideImportProgress();
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = `${II_IMPORT_SVG_DL} Import`;
            iiHideImportProgress();
            showAlert('Network error. Please try again.', 'Import Failed', { variant: 'danger', confirmText: 'OK' });
        }
    }

    function iiSuggestFilename(img) {
        const sourceProvider = img.provider || iiCurrentProvider;
        const slug = (img.alt || img.id || 'image')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .slice(0, 60);
        return `${sourceProvider}-${slug || img.id}`;
    }

    function iiUpdatePagination(forceStatic = false) {
        const prevBtn = document.getElementById('iiPrevBtn');
        const nextBtn = document.getElementById('iiNextBtn');
        const pageInfo = document.getElementById('iiPageInfo');

        if (forceStatic || iiCurrentProvider === 'starred') {
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            pageInfo.textContent = '';
            return;
        }

        prevBtn.disabled = iiCurrentPage <= 1;
        nextBtn.disabled = iiCurrentPage >= iiTotalPages;
    pageInfo.textContent = `Page ${iiCurrentPage}`;
    }

    function showIISuccessToast() {
        const t = document.getElementById('iiSuccessToast');
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
</script>
@endpush
