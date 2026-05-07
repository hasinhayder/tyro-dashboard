@once
<style>
    .tyro-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 0;
    }

    .tyro-pagination-info {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8125rem;
        color: #6b7280;
    }

    .dark .tyro-pagination-info {
        color: #9ca3af;
    }

    .tyro-pagination-info-label {
        text-transform: uppercase;
        font-size: 0.6875rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        color: inherit;
    }

    .tyro-pagination-info-range {
        font-weight: 700;
        font-size: 0.8125rem;
        color: #111827;
        background: #f3f4f6;
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
    }

    .dark .tyro-pagination-info-range {
        color: #f9fafb;
        background: #1f2937;
    }

    .tyro-pagination-info-total {
        font-weight: 700;
        font-size: 0.8125rem;
        color: #111827;
    }

    .dark .tyro-pagination-info-total {
        color: #f9fafb;
    }

    .tyro-pagination-links {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .tyro-pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 0.5rem;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
        line-height: 1;
    }

    .dark .tyro-pagination-btn {
        border-color: #374151;
        background: #111827;
        color: #9ca3af;
    }

    .tyro-pagination-btn:hover {
        background: #f3f4f6;
        color: #111827;
        border-color: #9ca3af;
    }

    .dark .tyro-pagination-btn:hover {
        background: #1f2937;
        color: #f9fafb;
        border-color: #4b5563;
    }

    .tyro-pagination-btn--active {
        background: #111827;
        color: #ffffff;
        border-color: #111827;
        cursor: default;
        font-weight: 600;
    }

    .dark .tyro-pagination-btn--active {
        background: #f9fafb;
        color: #111827;
        border-color: #f9fafb;
    }

    .tyro-pagination-btn--active:hover {
        background: #111827;
        color: #ffffff;
        border-color: #111827;
    }

    .dark .tyro-pagination-btn--active:hover {
        background: #f9fafb;
        color: #111827;
        border-color: #f9fafb;
    }

    .tyro-pagination-btn--disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }

    .tyro-pagination-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        font-size: 0.8125rem;
        color: #6b7280;
        letter-spacing: 0.05em;
        user-select: none;
    }

    .dark .tyro-pagination-dots {
        color: #9ca3af;
    }

    @media (max-width: 639px) {
        .tyro-pagination {
            gap: 0.625rem;
            align-items: flex-start;
            flex-direction: column;
        }

        .tyro-pagination-links {
            gap: 3px;
        }

        .tyro-pagination-btn {
            min-width: 30px;
            height: 30px;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .tyro-pagination-dots {
            width: 30px;
            height: 30px;
            font-size: 0.75rem;
        }
    }
</style>
@endonce

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="tyro-pagination">

        {{-- Info text --}}
        <p class="tyro-pagination-info">
            @if ($paginator->firstItem())
                <span class="tyro-pagination-info-label">Showing</span>
                <span class="tyro-pagination-info-range">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                <span class="tyro-pagination-info-label">of</span>
                <span class="tyro-pagination-info-total">{{ $paginator->total() }}</span>
            @else
                <span class="tyro-pagination-info-total">{{ $paginator->count() }}</span>
            @endif
        </p>

        {{-- Page links --}}
        <div class="tyro-pagination-links">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="tyro-pagination-btn tyro-pagination-btn--disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 12L6 8l4-4"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="tyro-pagination-btn" aria-label="{{ __('pagination.previous') }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 12L6 8l4-4"/></svg>
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="tyro-pagination-dots" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="tyro-pagination-btn tyro-pagination-btn--active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="tyro-pagination-btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="tyro-pagination-btn" aria-label="{{ __('pagination.next') }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4l4 4-4 4"/></svg>
                </a>
            @else
                <span class="tyro-pagination-btn tyro-pagination-btn--disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4l4 4-4 4"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
