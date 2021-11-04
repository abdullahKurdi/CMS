<div class="wn__sidebar">
    <aside class="widget recent_widget">
        <ul>
            <li class="list-group-item">
                <img src="{{asset('assets/users/def-pic.png')}}" alt="{{auth()->user()->name}}">
            </li>
            <li class="list-group-item">
                <a href="{{route('frontend.dashboard')}}">My Posts</a>
            </li>
            <li class="list-group-item">
                <a href="{{route('frontend.dashboard.create')}}">Create Posts</a>
            </li>
            <li class="list-group-item">
                <a href="{{route('frontend.dashboard.comment')}}">Manage Comments</a>
            </li>
            <li class="list-group-item">
                <a href="{{route('frontend.dashboard.edit-info')}}">Update Information</a>
            </li>
            <li class="list-group-item">
                <a href="{{route('frontend.logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </li>
        </ul>
    </aside>
</div>
