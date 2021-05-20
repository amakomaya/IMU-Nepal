<li>
    <a href="#">
        <i class="fa fa-user" aria-hidden="true"></i>
        User Management <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
        <li>
            <a href="{{ route('permissions.index') }}">
                <i class="fa fa-lock" aria-hidden="true"></i> Permission Management
            </a>
        </li>
        <li>
            <a href="{{ route('user-by-permissions.index') }}">
                <i class="fa fa-users" aria-hidden="true"></i> User By Permission
            </a>
        </li>
        <li>
            <a href="{{ route('password-reset.index') }}">
                <i class="fa fa-key" aria-hidden="true"></i> User Forget Password
                <span class="badge">{{ \App\Models\ForgetPassword::whereNull('read_at')->count() }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('activity-log.index') }}">
                <i class="fa fa-history" aria-hidden="true"></i> {{ trans('sidebar.activity_log') }}
            </a>
        </li>
    </ul>
</li>