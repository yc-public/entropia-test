@extends('layouts.default')

@section('title', 'Edit Movie')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="py-4">
	<h4 class="card-title">Edit Movie</h4>

	<form method="POST" action="{{ url("movies/{$movie->id}/update") }}" enctype="multipart/form-data">
		@csrf
		@method('PUT')
		<div class="card">
			@if ($errors->any())
			<div class="card-body">
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
			@endif

			<div class="row">
				<div class="col-md-6">
					<div class="card-body">
						<h4 class="card-title">Movie Detail</h4>

						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" placeholder="Name" name="name" value="{{ $movie->name }}">
						</div>

						<div class="form-group">
							<label>Year of Release</label>
							<select class="custom-select" placeholder="Year of Release" name="release_year">
								@foreach ($years as $year)
								<option value="{{ $year }}" {{ $movie->release_year == $year ? 'selected' : '' }}>{{ $year }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label>Plot</label>
							<textarea class="form-control" rows="5" placeholder="Plot" name="plot">{{ $movie->plot }}</textarea>
						</div>

						<div class="form-group">
							<label>Poster</label>

							<div class="custom-file">
								<input name="poster" type="file" class="custom-file-input">
								<label class="custom-file-label">Choose file</label>
							</div>
						</div>

						<button type="submit" class="btn btn-outline-primary mr-3">Submit</button>
						<a href="/" class="btn btn-outline-secondary">Cancel</a>
					</div>
				</div>

				<div class="col-md-6">
					<div class="card-body">
						<h4 class="card-title">Producer & Actors</h4>

						<div class="form-group">
							<label>Producer</label>
							<select class="form-control" id="producer-search" name="producer"></select>
						</div>

						<div class="form-group">
							<label>Actor</label>
							<select class="form-control" id="actor-search" name="actors[]" multiple="multiple"></select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

		const producer = @json($movie->producer);
		var option = new Option(producer.name, producer.id, true, true);
		$('#producer-search').append(option).trigger('change');

		const actors = @json($movie->actors);

		for (const x of actors) {
			var option = new Option(x.name, x.id, true, true);
			$('#actor-search').append(option).trigger('change');
		}

		$('#producer-search').select2({
			ajax: {
				url: '{{ url('users/producer') }}',
				dataType: 'json',
				data: function (params) {
					var query = {
						term: params.term,
						type: 'public'
					}

					return query;
				},
				processResults: function (resp) {
					const mapped = resp.map(x => {
						return {
							id: x.id,
							text: x.name
						}
					});
					
					return {
						results: mapped
					};
				}

			}
		});

		$('#actor-search').select2({
			ajax: {
				url: '{{ url('users/actor') }}',
				dataType: 'json',
				data: function (params) {
					var query = {
						term: params.term,
						type: 'public'
					}

					return query;
				},
				processResults: function (resp) {
					const mapped = resp.map(x => {
						return {
							id: x.id,
							text: x.name
						}
					});
					
					return {
						results: mapped
					};
				}

			}
		});
	});
</script>
@endpush