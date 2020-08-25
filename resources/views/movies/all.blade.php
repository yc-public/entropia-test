@extends('layouts.default')

@section('content')
<h3 class="mt-4 card-title">Movie Listing 
	<a href="{{ url('movies/create') }}" class="btn btn-sm btn-outline-primary mr-3">Add new</a>
</h3>

<x-flash-message />

<div class="row">
	@foreach($movies as $movie)
	<div class="col-md-6 pb-3">
		<div class="card">
			<div class="row align-center">
				<div class="col-md-4">
					<img width="100%" src="{{ $movie->poster_url }}">
				</div>

				<div class="col-md-8 py-3">
					<h4>{{ $movie->name }}</h4>
					<h6 class="card-subtitle text-primary">{{ $movie->release_year }}</h6>

					<div class="mt-3">
						<div>Producer: {{ $movie->producer->name }}</div>
						<div>Actors: {{ $movie->actors->pluck('name')->implode(', ') }}
						</div>

						<div class="d-flex mt-3">
							<a href="{{ url("movies/{$movie->id}/view") }}" class="btn btn-outline-primary mr-3">View</a>
							<a href="{{ url("movies/{$movie->id}/edit") }}" class="btn btn-outline-primary mr-3">Edit</a>
							<button class="btn btn-outline-primary mr-3 btn-delete" data-id="{{ $movie->id }}" data-name="{{ $movie->name }}">Delete</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endforeach
</div>

<!-- modal -->
<div class="modal" id="modalDelete" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Delete Confirmation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="mb-0">Are you sure want to delete 
					<span id="#movieName"></span>
				?</p>
			</div>
			<div class="modal-footer">
				<form id="formDelete" method="post">
					@csrf 
					@method('DELETE')

					<button type="submit" class="btn btn-primary btn-submit-user">Yes</button>
					<button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">No</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- ./modal -->
@endsection

@push('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		// handler delete movie
		$(".btn-delete").click(function(e){
			e.preventDefault();

			var data = $(this).data();
			$('#movieName').html(data.name);
			$('#formDelete').attr('action', `/movies/${data.id}/delete`);

			$('#modalDelete').modal('show');
		}); 
	});
</script>
@endpush
