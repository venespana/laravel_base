<div class="user-panel">
    <div class="pull-left image">
        <img src="{{ auth()->user()->image }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p>{{ auth()->user()->fullname }}</p>
        <a href="/user">  
            @faicon('user')
            Profile
        </a>
    </div>
</div>