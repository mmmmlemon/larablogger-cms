<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Post extends Model implements Feedable
{
    //
    protected $table = "posts";

    public function toFeedItem()
    {
        $author = Settings::all()[0]->site_title;

        return FeedItem::create()
            ->id($this->id)
            ->title($this->post_title)
            ->updated($this->created_at)
            ->summary($this->post_content)
            ->link("/post/".$this->id)
            ->author($author);
    }

    public static function getFeedItems()
    {
        return Post::where('visibility','=',1)->orderBy("created_at","desc")->limit(10)->get();
        redirect()->back();
    }



}
