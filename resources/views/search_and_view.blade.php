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
            <div class="field has-addons">
                <div class="control has-icons-left"  style="width:60%;">
                  <input class="input" type="text" placeholder="Search" id="search_bar">
                  <span class="icon is-small is-left">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
                <div class="control">
                  <a class="button is-link">
                    Search
                  </a>
                </div>
              </div>
        </div>
    </div>
  
</div>
<div class="white-bg search_results" id="search_results">

</div>