<template>
    <li class="shopcart">
        <a class="cartbox_active" href="#">
            <span class="product_qun" v-if="unreadCount > 0">{{unreadCount}}</span>
         </a>
        <!-- Start Shopping Cart -->
        <div class="block-minicart minicart__active">
            <div class="minicart-content-wrapper" v-if="unreadCount > 0">
                <div class="single__items">
                    <div class="miniproduct">

                        <div class="item01 d-flex" v-for="item in unread" :key="item.id">
                            <div class="thumb">
                                <a :href="`/comments/${item.data.id}/edit-comment`" @click="readNotification(item)"><img src="/frontend/images/icons/comment.png"  alt="${item.data.post_title}"></a>
                            </div>
                            <div class="content">
                                <h6><a :href="`/comments/${item.data.id}/edit-comment`" @click="readNotification(item)">You have new comment on your post: {{item.data.post_title}}</a></h6>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Shopping Cart -->
    </li>
</template>

<script>
    export default {
        data:function (){
            return{
                read:{},
                unread:{},
                unreadCount:0
            }
        },
        created: function (){
            this.getNotifaction();

            let userId = $('meta[name="userId"]').attr('content');
            Echo.private('App.User.'+userId)
                .notification((notification)=>{
                    this.unread.unshift(notification);
                    this.unreadCount++;
                })
        },methods:{
            getNotifaction(){
                axios.get('user/notification/get').then(res =>{
                    this.read = res.data.read;
                    this.unread = res.data.unread;
                    this.unreadCount = res.data.unread.length;
                }).catch(error => Exception.handle(error))
            },
            readNotification(notification){
                axios.post('user/notification/read',{id:notification.id}).then(res =>{
                    this.unread.splice(notification,1);
                    this.read.push(notification);
                    this.unreadCount--;
                })
            }
        }
    }
</script>
