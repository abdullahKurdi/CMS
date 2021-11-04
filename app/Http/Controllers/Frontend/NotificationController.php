<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification(){
        return [
            'read'=>auth()->user()->readNotifications,
            'unread'=>auth()->user()->unreadNotifications,
            'usertype' =>auth()->user()->roles->first()->name,
        ];
    }

    public function markAsRead(Request $request){
        return auth()->user()->notifications->where('id',$request->id)->markAsRead();
    }

    public function markAsReadAndRedirect($id){
        $notifications = auth()->user()->notifications->where('id', $id)->markAsRead()->first();
        $this->markAsRead();

        if(auth()->user()->roles->first()->name == 'user'){
            if($notifications->type == 'App\Notifications\NewCommentForPostOwnerNotify'){
                return redirect()->route('frontend.dashboard.edit.comment',$notifications->data['id']);
            }else{
                return redirect()->back();
            }
        }
    }
}
