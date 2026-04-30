<!-- side navbar start -->
<div class="bg-white" id="sidebar-wrapper">
    <div class="sidebar-logo text-center p-4">
        <img src="{{ asset('uploads/system_image/'.$system_image[0]->description) }}" class="img-fluid img-logo" alt="image">
    </div>
    <div class="list-group list-grop-flush ">
        <a href="{{ url('/users/dashboard') }}" class="list-group-item list-group-item-action {{ (request()->is('users/dashboard') || request()->is('users/wallets')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/home.png') }}" class="img-fluid me-4" alt="icon"> Home
        </a>
        <!-- <a href="{{ url('/users/data_analysis') }}" class="list-group-item list-group-item-action {{ (request()->is('users/data_analysis')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/analytics.png') }}" class="img-fluid me-4" alt="icon">Data Analysis
        </a> -->
        <!-- <a href="{{ url('/users/offers') }}" class="list-group-item list-group-item-action {{ (request()->is('users/offers') || request()->is('users/offer_requests*')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/activity.png') }}" class="img-fluid me-4" alt="icon">Offers
        </a> -->
        <a href="{{ url('/users/products') }}" class="list-group-item list-group-item-action {{ (request()->is('users/products*')) || (request()->is('users/product*')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/activity.png') }}" class="img-fluid me-4" alt="icon">Products
        </a>
        <a href="{{ url('/users/track') }}" class="list-group-item list-group-item-action {{ (request()->is('users/track')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/send-2.png') }}" class="img-fluid me-4" alt="icon">Track
        </a>
        <a href="{{ url('/users/connect') }}" class="list-group-item list-group-item-action {{ (request()->is('users/connect')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/Connect.png') }}" class="img-fluid me-4" alt="icon">Connect
        </a>
        <a href="{{ url('/users/profile') }}" class="list-group-item list-group-item-action {{ (request()->is('users/profile') || request()->is('users/profile_edit') || request()->is('users/billing_payment') || request()->is('users/transactions') || request()->is('users/settings') || request()->is('users/profile_faq')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/Profile.png') }}" class="img-fluid me-4" alt="icon">Profile
        </a>
        <a href="{{ url('/users/message') }}" class="list-group-item list-group-item-action {{ (request()->is('users/message*')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/mark_chat_unread.png') }}" class="img-fluid me-4" alt="icon">Message
        </a>
        <a href="{{ url('/users/claims') }}" class="list-group-item list-group-item-action {{ (request()->is('users/claims')) ? 'active' : '' }}">
            <img src="{{ asset('users/assets/images/icons/activity.png') }}" class="img-fluid me-4" alt="icon">Claims
        </a>
    </div>
</div>
<!-- side navbar end-->