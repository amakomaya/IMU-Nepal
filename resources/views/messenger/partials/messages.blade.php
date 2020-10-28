<div class="media" xmlns="http://www.w3.org/1999/html">
    <a class="pull-left" href="#">
        <i class="fa fa-user-md fa-4x"></i>
    </a>
    <div class="media-body">
        <h5 class="media-heading">{{ $message->user->username }}</h5>
        <p>{!! $message->body !!}</p>
        <div class="text-muted">
            <small>Posted {{ $message->created_at->diffForHumans() }}</small>
        </div>
    </div>
</div>