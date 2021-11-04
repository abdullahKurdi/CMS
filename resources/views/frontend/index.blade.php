@extends('layouts.app')

@section('content')
    <!-- Start Blog Area -->

    <div class="col-lg-9 col-12">
        <div class="blog-page">
            @forelse($posts as $post)
                <article class="blog__post d-flex flex-wrap">
                    <div class="thumb">
                        <a href="{{route('post_show',$post->slug)}}">
                            @if($post->media->count()>0)
                            <img src="{{asset('asset/posts'.$post->media->first()->file_name)}}" alt="{{$post->title}}">
                            @else
                            <img src="{{asset('assets/posts/1.jpg')}}" alt="blog images">
                            @endif
                        </a>
                    </div>
                    <div class="content">
                        <h4><a href="{{route('post_show',$post->slug)}}">{{$post->title}}</a></h4>
                        <ul class="post__meta">
                            <li>Posts by : <a href="{{route('frontend.author.post',$post->user->username)}}">{{$post->user->name}}</a></li>
                            <li class="post_separator">/</li>
                            <li>{{$post->created_at->format('M D Y')}}</li>
                        </ul>
                        <p>{!! \Illuminate\Support\Str::limit($post->description,145 ,'...') !!}</p>
                        <div class="blog__btn">
                            <a href="{{route('post_show',$post->slug)}}">read more</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center">No Posts Found</div>
            @endforelse
        </div>
{{--                    <ul class="wn__pagination">--}}
{{--                        <li class="active"><a href="#">1</a></li>--}}
{{--                        <li><a href="#">2</a></li>--}}
{{--                        <li><a href="#">3</a></li>--}}
{{--                        <li><a href="#">4</a></li>--}}
{{--                        <li><a href="#"><i class="zmdi zmdi-chevron-right"></i></a></li>--}}
{{--                    </ul>--}}
        {!!$posts->appends(request()->input())->links()!!}
    </div>
    <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
        @include('partial.frontend.sidebar')
    </div>

    <!-- End Blog Area -->
@endsection
