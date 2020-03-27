
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
    @if ($paginator->lastPage() > 1)
<a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous" @if($paginator->currentPage() == 1) disabled @endif>
  <span class="icon">
    <i class="fas fa-chevron-left" ></i>
  </span>
</a>
    <a @if($paginator->currentPage() != $paginator->lastPage()) href="{{ $paginator->url($paginator->currentPage()+1) }}" @endif class="pagination-next"  @if($paginator->currentPage() == $paginator->lastPage()) disabled @endif>
      <span class="icon">
        <i class="fas fa-chevron-right" ></i>
      </span>
    </a>
    <ul class="pagination-list">
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
      <li>
        <a href="{{ $paginator->url($i) }}" class="{{ ($paginator->currentPage() == $i) ? ' is-current' : '' }} pagination-link" aria-label="Goto page 1">{{ $i }}</a>
      </li>
      @endfor
    </ul>
    @endif
  </nav>
