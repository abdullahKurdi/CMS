@extends('layouts.app')
@section('content')
    <div class="col-lg-9 col-12">
        <div class="blog-page">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td>{{$post->title}}</td>
                        <td>{{$post->comments_count}}</td>
                        <td>{{$post->status}}</td>
                        <td>
                            <a href="{{route('frontend.dashboard.edit',$post->id)}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" onclick="if(confirm('Are you sure to delete this post')){document.getElementById('post-delete-{{$post->id}}').submit()}else{return false}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            <form action="{{route('frontend.dashboard.destroy',$post->id)}}"  method="post" id="post-delete-{{$post->id}}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No Post Found</td>
                    </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">{!! $posts->links() !!}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                    @include('partial.frontend.users.sidebar')
                </div>
@endsection
