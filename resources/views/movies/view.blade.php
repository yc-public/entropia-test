@extends('layouts.default')

@section('title', 'View Movie')

@section('content')
<div class="row py-4">
	<div class="col-md-4">
		<h4>Movie Detail</h4>
		<div class="card p-3">
			<img width="100%" src="{{ $movie->poster_url }}">
			<h4 class="mt-3">{{ $movie->name }}</h4>
			<h6 class="card-subtitle text-primary">{{ $movie->release_year }}</h6>
			<div class="mt-4">{{ $movie->plot }}</div>
		</div>
	</div>

	<div class="col-md-4">
		<h4>Producer</h4>
		<div class="card p-3">
			<h4 class="mb-0">{{ $movie->producer->name }}</h4>
			<div class="text-muted">{{ $movie->producer->sex->name }}</div>
			<div class="text-primary">{{ $movie->producer->dob->toDateString() }}</div>
			<div class="mt-4">{{ $movie->producer->bio }}</div>
		</div>
	</div>

	<div class="col-md-4">
		<h4>Actors</h4>
		@foreach($movie->actors as $actor)
		<div class="card p-3 mb-3">
			<h4 class="mb-0">{{ $actor->name }}</h4>
			<div class="text-muted">{{ $actor->sex->name }}</div>
			<div class="text-primary">{{ $actor->dob->toDateString() }}</div>
			<div class="mt-4">{{ $actor->bio }}</div>
		</div>
		@endforeach
	</div>
</div>
@endsection
