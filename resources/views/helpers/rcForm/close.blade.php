	@if(in_array($method, ['POST', 'post']))
		<button type="submit" class="btn btn-primary btn-sm btn-block ">SAVE</button>
	@elseif(in_array($method, ['put', 'patch', 'PUT', 'PATCH']))
		<button type="submit" class="btn btn-primary btn-sm btn-block ">UPDATE</button>
	@elseif(in_array($method, ['delete', 'DELETE']))
		<button type="submit" class="btn btn-danger btn-sm btn-block ">SAVE</button>
	@else
		<button type="submit" class="btn btn-primary btn-sm btn-block ">{{ $method }}</button>
	@endif
</form>