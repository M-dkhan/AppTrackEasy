<style>
   /* Custom pagination styles */
.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    border: 1px solid #3498db; /* Add a border to the pagination */
    border-radius: 5px; /* Add border radius for a rounded appearance */
    padding: 5px; /* Add some padding for spacing */
}

.pagination .page-item {
    margin: 0 2px;
}

.pagination .page-link {
    color: #3498db;
    background-color: transparent;
    border: none;
    border-radius: 5px; /* Add border radius for page links */
}

.pagination .page-link:hover {
    background-color: #3498db;
    color: #fff;
}

.pagination .page-item.active .page-link {
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 5px; /* Add border radius for the active page link */
}


</style>

@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
        @endif
    </ul>
@endif
