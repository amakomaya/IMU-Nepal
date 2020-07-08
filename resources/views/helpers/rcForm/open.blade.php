<form class="form-group" role="form" action="{{ $route }}" enctype="multipart/form-data" method="{{ $method }}" 
	@foreach($attributes as $key => $value)

    	{{ $key }} = "{{ $value }}"

    @endforeach
    >
<input type="hidden" name="_token" value="{{ csrf_token() }}" >