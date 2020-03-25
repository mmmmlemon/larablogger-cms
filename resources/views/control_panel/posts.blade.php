<div id="posts_content" class="invisible">
    <div class="columns">
        {{-- если есть какие-то посты то, выводим их --}}
    @if($posts->count() > 0)
    <table class="table is-fullwidth is-hoverable">
        <thead>
            <th>Title</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
            <td><b><a href="/post/{{$post->id}}">{{$post->post_title}}</a></b></td>
            <td>
                @if($post->status == 1)
                    <span class="icon is-small" data-tooltip="Post is published">
                        <i class="fas fa-check"></i>
                    </span>
                @else
                    <span class="icon is-small" data-tooltip="Post is not published">
                        <i class="fas fa-times"></i>
                    </span>
                @endif
            </td>
            <td>{{date('d.m.Y', strtotime($post->date))}}</td>
            <td>
                @if($post->status == 1)
                    <a href="/control/hide_post/{{$post->id}}" class="button is-warning">
                        <span class="icon is-small" data-tooltip="Hide this post">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </a>
                @else
                <a href="/control/show_post/{{$post->id}}" class="button is-primary">
                    <span class="icon is-small" data-tooltip="Show this post"> 
                        <i class="fas fa-eye"></i>
                    </span>
                </a>
                @endif
                <a href="/control/delete_post/{{$post->id}}" class="button is-danger">
                    <span class="icon is-small" data-tooltip="Delete this post">
                        <i class="fas fa-trash"></i>
                    </span>
                </a>
            </td>
            </tr>
            
        
        @endforeach
        </tbody>  
    </table>
    @else
    <h1>No posts yet</h1>
    <a href="/control/create_post" class="button is-link">Create post</a>
    @endif
    </div>
</div>