<div class="user-panel">
    <div class="pull-left image">
        <img src="{{ auth()->user()->image }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p>{{ auth()->user()->fullname }}</p>
        <a href="/user">
            <i class="fa fa-fw fa-user"></i>
            Profile
        </a>
    </div>
</div>