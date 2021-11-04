<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;


use App\Models\Comment;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;


class UserController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    public function index(){
        $posts = auth()->user()->posts()->with(['media' ,'category','user'])
            ->withCount('comments')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('frontend.users.dashboard',compact('posts'));
    }

    public function create(){
        $categories = Category::whereStatus(1)->pluck('name','id');
        return view('frontend.users.create-post',compact('categories'));
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'title'         =>'required',
            'description'   =>'required|min:50',
            'comment_able'  =>'required',
            'status'        =>'required',
            'category_id'   =>'required',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $data['title']          =$request->title;
        $data['description']    =Purify::clean($request->description);
        $data['comment_able']   =$request->comment_able;
        $data['status']         =$request->status;
        $data['category_id']    =$request->category_id;
        //$data['user_id']        =Auth::id();

        $post = auth()->user()->posts()->create($data);

        if($request->images && count($request->images) > 0){
            $i = 1 ;
            foreach ($request->images as $file){
                $file_name = $post->slug.'-'.time().'-'.$i.$file->getClientOriginalName();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();

                $path = public_path('assets/posts/'.$file_name);
                Image::make($file->getRealPath())->resize(800,null,function ($c){
                   $c->aspectRatio();
                })->save($path , 100);

                $post->media()->create([
                   'file_name'=>$file_name,
                   'file_size'=>$file_size,
                   'file_type'=>$file_type
                ]);
                $i++;
            }
        }

        if($request->status = 1){
            Cache::forget('recent_post');
        }

        return redirect()->back()->with([
            'message' => 'Post create successfully',
            'alert' => 'success',
        ]);
    }

    public function edit($post){
        $post = Post::whereSlug($post)->orWhere('id',$post)->whereUserId(auth()->id())->first();

        if($post){
            $categories = Category::whereStatus(1)->pluck('name','id');
            return view('frontend.users.edit-post',compact('categories','post'));
        }
        return redirect()->back()->with([
            'message' => 'Post not found',
            'alert' => 'danger',
        ]);
    }

    public function update(Request $request ,$post){
        $validate = Validator::make($request->all(),[
            'title'         =>'required',
            'description'   =>'required|min:50',
            'comment_able'  =>'required',
            'status'        =>'required',
            'category_id'   =>'required',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $post = Post::whereSlug($post)->orWhere('id',$post)->whereUserId(auth()->id())->first();
        if($post){
            $data['title']          =$request->title;
            $data['description']    =Purify::clean($request->description);
            $data['comment_able']   =$request->comment_able;
            $data['status']         =$request->status;
            $data['category_id']    =$request->category_id;
            //$data['user_id']        =Auth::id();

          $post->update($data);
            if($request->images && count($request->images) > 0){
                $i = 1 ;
                foreach ($request->images as $file){
                    $file_name = $post->slug.'-'.time().'-'.$i.$file->getClientOriginalName();
                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();

                    $path = public_path('assets/posts/'.$file_name);
                    Image::make($file->getRealPath())->resize(800,null,function ($c){
                        $c->aspectRatio();
                    })->save($path , 100);

                    $post->media()->create([
                        'file_name'=>$file_name,
                        'file_size'=>$file_size,
                        'file_type'=>$file_type
                    ]);
                    $i++;
                }
            }
            return redirect()->back()->with([
                'message' => 'Post updated successfully',
                'alert' => 'success',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert' => 'danger',
        ]);
    }

    public function media_destroy($post){
        $media = PostMedia::whereId($post)->first();
        if($media){
            if(File::exists('assets/posts/'.$media->file_name)){
                unlink('assets/posts/'.$media->file_name);
            }
            $media->delete();
            return true;
        }
        return false;
    }
    public function destroy($post){
        $post = Post::whereSlug($post)->orWhere('id',$post)->whereUserId(auth()->id())->first();

        if($post){
           if($post->media->count() > 0){
               foreach ($post->media as $media){
                   if(File::exists('assets/posts/'.$media->file_name)){
                       unlink('assets/posts/'.$media->file_name);
                   }
               }
           }
           $post->delete();
            return redirect()->back()->with([
                'message' => 'Post deleted successfully',
                'alert' => 'success',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert' => 'danger',
        ]);
    }

    public function comment(){
        $posts_id = auth()->user()->posts()->pluck('id')->toArray();
        $comments = Comment::whereIn('post_id',$posts_id)->paginate(10);
        return view('frontend.users.comments',compact('comments'));
    }

    public function edit_comment($comment_id){
        $comment = Comment::whereId($comment_id)->whereHas('post',function ($q){
           $q->where('user_id',auth()->id());
        })->first();
        if($comment){
            return view('frontend.users.edit-comment',compact('comment'));
        }
        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert' => 'danger',
        ]);
    }

    public function update_comment(Request $request ,$comment_id){
        $validate = Validator::make($request->all(),[
            'name'         =>'required',
            'email'         =>'required|email',
            'url'           =>'nullable|url',
            'status'        =>'required',
            'comment'       =>'required',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $comment = Comment::whereId($comment_id)->whereHas('post',function ($q){
            $q->where('user_id',auth()->id());
        })->first();
        if($comment){
            $data['name']   =$request->name;
            $data['email']  =$request->email;
            $data['url']    =$request->url != '' ? $request->url : null;
            $data['status'] =$request->status;
            $data['comment'] =Purify::clean($request->comment);

            $comment->update($data);

            if($request->status = 1){
                Cache::forget('recent_comments');
            }

            return redirect()->back()->with([
                'message' => 'Comment updated successfully',
                'alert' => 'success',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert' => 'danger',
        ]);
    }
    public function destroy_comment($comment_id){

        $comment = Comment::whereId($comment_id)->whereHas('post',function ($q){
            $q->where('user_id',auth()->id());
        })->first();

        if($comment){

            $comment->delete();

            Cache::forget('recent_comments');

            return redirect()->back()->with([
                'message' => 'Comment deleted successfully',
                'alert' => 'success',
            ]);

        }
        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert' => 'danger',
        ]);
    }

    public function edit_info(){
        return view('frontend.users.edit-info');
    }
    public function update_info(Request $request){
        $validate = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'bio'=>'nullable|min:10',
            'receive_email'=>'required',
            'user_image'=>'nullable|image|max:20000,mimes:jpeg,jpg,png',
        ]);

        if ($validate ->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        $data['bio'] = $request->bio;
        $data['receive_email'] = $request->receive_email;

        if($image = $request->file('user_image')){
            if(auth()->user()->user_image != ''){
                if (File::exists('assets/users'.auth()->user()->user_image)){
                    unlink('assets/users'.auth()->user()->user_image);
                }
            }
            $file_name = Str::slug(auth()->user()->username).$image->getClientOriginalName();

            $path = public_path('assets/users/'.$file_name);
            Image::make($image->getRealPath())->resize(300,300,null,function ($c){
                $c->aspectRatio();
            })->save($path , 100);

            $data['user_image']=$file_name;
        }

        $update = auth()->user()->update($data);

        if($update){
            return redirect()->back()->with([
                'message' => 'Information updated successfully',
                'alert' => 'success',
            ]);

        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert' => 'danger',
            ]);
        }
    }
    public function update_password(Request $request){
        $validate = Validator::make($request->all(),[
            'current_password'=>'required',
            'password'=>'required|confirmed',
        ]);

        if ($validate ->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $user = auth()->user();
        if(Hash::check($request->current_password , $user->password)){
            $update = $user->update([
                'password'=>bcrypt($request->password),
            ]);
            if($update){
                return redirect()->back()->with([
                    'message' => 'Password updated successfully',
                    'alert' => 'success',
                ]);

            }else{
                return redirect()->back()->with([
                    'message' => 'Something was wrong',
                    'alert' => 'danger',
                ]);
            }
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert' => 'danger',
            ]);
        }
    }
}
