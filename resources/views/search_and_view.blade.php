<div class="container white-bg">
    <div class="columns">
        <div class="buttons has-addons" style="width:7rem; margin-left:2pt; margin-top:4pt; margin-bottom:0;">
            <button class="button @if($view_type == 'list') view_button_active ignore @else view_button @endif" id="list_view">
                <span>
                    <i class="fas fa-bars"></i>
                </span>
            </button>
            <button class="button @if($view_type == 'grid') view_button_active ignore @else view_button @endif" id="grid_view">
                <span>
                    <i class="fas fa-grip-horizontal"></i>
                </span>
            </button>
        </div>
        <div class="" style="width:100%; margin-left:2pt; margin-top:4pt; margin-bottom:0;">
          <form action="/full_search" method="GET">
            <div class="field has-addons">
                <div class="control has-icons-left has-icons-right"  style="width:60%;" id="search_bar_div">
                  <input class="input" type="text" placeholder="Search" id="search_bar" name="search_value" value="{{$val ?? '' }}" data-type="post">
                  <span class="icon is-small is-left">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
                <div class="control">
                  <button class="button is-link">
                    Search
                  </button>
                </div>
              </div>
            </form>
        </div>
    </div>
  
</div>
<div class="white-bg search_results" id="search_results">

</div>