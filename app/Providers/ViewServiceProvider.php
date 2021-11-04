<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!request()->is('admin/*')) {
            Paginator::defaultView('vendor.pagination.cms');
            view()->composer('*',function($view){
                if(!Cache::has('recent_post')){
                    $recent_post = Post::with(['category','media','user'])
                        ->whereHas('category', function ($q){
                            $q->whereStatus(1);
                        })
                        ->whereHas('user', function ($q){
                            $q->whereStatus(1);
                        })
                        ->wherePostType('post')->whereStatus(1)->orderBy('id','desc')->limit(5)->get();
                    Cache::remember('recent_post',3600,function () use ($recent_post){
                       return $recent_post;
                    });
                }
                if(!Cache::has('recent_comments')){
                    $recent_comments = Comment::whereStatus(1)->orderBy('id', 'desc')->limit(5)->get();

                    Cache::remember('recent_comments',3600,function () use ($recent_comments){
                        return $recent_comments;
                    });
                }
                if(!Cache::has('global_categories')){
                    $global_categories = Category::whereStatus(1)->orderBy('id', 'desc')->get();

                    Cache::remember('global_categories',3600,function () use ($global_categories){
                        return $global_categories;
                    });
                }
                if(!Cache::has('global_archives')){
                    $global_archives = Post::whereStatus(1)->orderBy('id', 'desc')
                        ->select(DB::raw("Year(created_at) as year"),DB::raw("Month(created_at) as month"))
                        ->pluck('year','month')->toArray();

                    Cache::remember('global_archives',3600,function () use ($global_archives){
                        return $global_archives;
                    });
                }

                $recent_post = Cache::get('recent_post');
                $recent_comments = Cache::get('recent_comments');
                $global_categories = Cache::get('global_categories');
                $global_archives = Cache::get('global_archives');

                $view->with([
                    'recent_post'=>$recent_post,
                    'recent_comments'=>$recent_comments,
                    'global_categories'=>$global_categories,
                    'global_archives'=>$global_archives,

                ]);
            });
        }
    }
}
