@if ($paginator->hasPages())
    <div class="dataTables_paginate paging_simple_numbers" id="kt_project_users_table_paginate">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="paginate_button page-item previous disabled" id="kt_project_users_table_previous">
                    <a href="#" aria-controls="kt_project_users_table" data-dt-idx="0" tabindex="0" class="page-link"><i class="ri-arrow-left-s-line"></i></a>
                </li>
            @else
                <li class="paginate_button page-item previous" id="kt_project_users_table_previous">
                    <a href="{{ $paginator->previousPageUrl() }}" aria-controls="kt_project_users_table" data-dt-idx="0" tabindex="0" class="page-link"><i class="ri-arrow-left-s-line"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="paginate_button page-item disabled"><a href="#" aria-controls="kt_project_users_table" tabindex="0" class="page-link">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="paginate_button page-item active"><a href="{{ $url }}" aria-controls="kt_project_users_table" data-dt-idx="{{ $page }}" tabindex="0" class="page-link">{{ $page }}</a></li>
                        @else
                            <li class="paginate_button page-item"><a href="{{ $url }}" aria-controls="kt_project_users_table" data-dt-idx="{{ $page }}" tabindex="0" class="page-link">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="paginate_button page-item next" id="kt_project_users_table_next">
                    <a href="{{ $paginator->nextPageUrl() }}" aria-controls="kt_project_users_table" data-dt-idx="{{ $paginator->lastPage() }}" tabindex="0" class="page-link"><i class="ri-arrow-right-s-line"></i></a>
                </li>
            @else
                <li class="paginate_button page-item next disabled" id="kt_project_users_table_next">
                    <a href="#" aria-controls="kt_project_users_table" data-dt-idx="{{ $paginator->lastPage() }}" tabindex="0" class="page-link"><i class="ri-arrow-right-s-line"></i></a>
                </li>
            @endif
        </ul>
    </div>
@endif
