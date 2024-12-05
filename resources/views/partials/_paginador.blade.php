@if ($paginator->hasPages())
            <div class="paginador">
            [
            @if ($paginator->onFirstPage())
                <span aria-hidden="true">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
            @endif
                /
            @foreach ($elements as $element)
                @if (is_string($element))
                    {{ $element }}
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            {{ $page }}
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                        /
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
            @else
                <span aria-hidden="true">&rsaquo;</span>
            @endif
            ]
            </div>
@endif 