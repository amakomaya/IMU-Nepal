	@if(in_array($method, ['POST', 'post']))
		<button type="submit" class="btn btn-primary btn-sm">SAVE</button>
	@elseif(in_array($method, ['put', 'patch', 'PUT', 'PATCH']))
		<button type="submit" class="btn btn-primary btn-sm">UPDATE</button>
	@elseif(in_array($method, ['delete', 'DELETE']))
		<button type="submit" class="btn btn-danger btn-sm">SAVE</button>
	@else
		<button type="submit" class="btn btn-primary btn-sm">{{ $method }}</button>
	@endif
</form>