@extends('layouts.app')
@section('content')
    <div class="col-lg-9 col-12">
        <div class="blog-page">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Post</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td>{{$comment->name}}</td>
                        <td>{{$comment->post->title}}</td>
                        <td>{{$comment->status}}</td>
                        <td>
                            <a href="{{route('frontend.dashboard.edit.comment',$comment->id)}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" onclick="if(confirm('Are you sure to delete this comment')){document.getElementById('comment-delete-{{$comment->id}}').submit()}else{return false}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            <form action="{{route('frontend.dashboard.destroy.comment',$comment->id)}}"  method="post" id="comment-delete-{{$comment->id}}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No comment Found</td>
                    </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">{!! $comments->links() !!}</td>
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
