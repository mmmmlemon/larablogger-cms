
<nav class="pagination" role="navigation" aria-label="pagination">
    @if ($paginator->lastPage() > 1)
    <a href="{{ $paginator->url(1) }}" class="pagination-previous"><</a>
    <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="pagination-next">></a>
    <ul class="pagination-list">
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
      <li>
        <a href="{{ $paginator->url($i) }}" class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }} pagination-link" aria-label="Goto page 1">{{ $i }}</a>
      </li>
      @endfor
    </ul>
    @endif
  </nav>
