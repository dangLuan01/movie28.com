@if ($paginator->hasPages())
    <div class="col-12">
        <div class="paginator">
            <span class="paginator__pages">
                {{ $paginator->currentPage() }} from {{ $paginator->lastPage() }}
            </span>
            <ul class="paginator__paginator">
                <li>
                    @if ($paginator->onFirstPage())
                        <span class="disabled">
                            <svg width="14" height="11" viewBox="0 0 14 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 5.36475L13.1992 5.36475" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M5.771 10.1271L0.749878 5.36496L5.771 0.602051" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}">
                            <svg width="14" height="11" viewBox="0 0 14 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 5.36475L13.1992 5.36475" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M5.771 10.1271L0.749878 5.36496L5.771 0.602051" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif
                </li>
                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li class="{{ $page == $paginator->currentPage() ? 'active' : '' }}">
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                    @endif
                @endforeach
                <li>
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}">
                            <svg width="14" height="11" viewBox="0 0 14 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.1992 5.3645L0.75 5.3645" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M8.17822 0.602051L13.1993 5.36417L8.17822 10.1271" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @else
                        <span class="disabled">
                            <svg width="14" height="11" viewBox="0 0 14 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.1992 5.3645L0.75 5.3645" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M8.17822 0.602051L13.1993 5.36417L8.17822 10.1271" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
@endif
