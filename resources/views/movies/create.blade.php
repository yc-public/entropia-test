@extends('layouts.default')

@section('title', 'Create Movie')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<h4 class="mt-4 card-title">Create Movie</h4>

<form method="POST" action="/movies/store" enctype="multipart/form-data">
	@csrf
	<div class="card">
		@if ($errors->any())
		<div class="card-body">
			<div class="alert alert-danger">
				<ul class="mb-0">
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
						<input type="text" class="form-control" placeholder="Name" name="name">
					</div>

					<div class="form-group">
						<label>Year of Release</label>
						<select class="custom-select" placeholder="Year of Release" name="release_year">
							@foreach ($years as $year)
							<option>{{ $year }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label>Plot</label>
						<textarea class="form-control" rows="5" placeholder="Plot" name="plot"></textarea>
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
					<h4 class="card-title">Producer & Actors
						<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalUser">
							Add User
						</button>
					</h4>

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

<!-- modal -->
<div class="modal" id="modalUser" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add User</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger alert-block alert-user" style="display:none;">
					<button type="button" class="btn-close close">×</button>    
					<ul class="user-errors"></ul>
				</div>

				<form>
					<div class="form-group">
						<label>Role</label>
						<select class="custom-select" placeholder="Role" name="role">
							@foreach ($roles as $role)
							<option value="{{ $role->id }}">{{ $role->name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" placeholder="Name" name="user_name">
					</div>

					<div class="form-group">
						<label>Sex</label>
						<select class="custom-select" placeholder="Sex" name="sex">
							@foreach ($sexes as $sex)
							<option value="{{ $sex->id }}">{{ $sex->name }}</option>
							@endforeach
						</select>
					</div>


					<div class="form-group">
						<label>DOB</label>
						<input type="text" class="form-control" placeholder="dd/mm/yyyy" name="dob">
					</div>

					<div class="form-group">
						<label>Bio</label>
						<textarea class="form-control" rows="5" placeholder="Bio" name="bio"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-submit-user">Save changes</button>
				<button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- ./modal -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// display file name in input file
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

		// ajax search (producer)
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

		// ajax search (actor)
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

		// handler modal add user
		$(".btn-submit-user").click(function(e){
			e.preventDefault();

			// process formData into JSON
			const role = $("select[name='role']").val();
			const name = $("input[name='user_name']").val();
			const sex = $("select[name='sex']").val();
			const dob = $("input[name='dob']").val();
			const bio = $("textarea[name='bio']").val();

			const fd = {
				role, name, sex, dob, bio

			}

			$.ajax({
				url: '{{ url('users/store') }}',
				type:'POST',
				data: fd
			})
			.done(function(resp) {
				alert('User Created successfully.');
				$(".btn-close").trigger("click");
				
			})
			.fail(function(xhr, status, error) {
				const errors = xhr.responseJSON.errors;
				var li = "";

				// mapping errors message into li
				for (const [key, value] of Object.entries(errors)) {
					li += `<li>${value[0]}</li>`;
				}
				
				$(".user-errors").html(li);
				$(".alert-user").show();
			});
		}); 

		$(".btn-close").click(function(e){
			e.preventDefault();
			$(".alert-user").hide();
		});
	});
</script>
@endpush