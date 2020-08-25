<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Rules\UserExists;
use App\Library\Repositories\ListRepository;
use App\Library\Repositories\MovieRepository;

use Log;
use Exception;
use Validator;

class MovieController extends Controller
{
	protected $movieRepo, $listRepo;

	public function __construct(MovieRepository $movieRepo, ListRepository $listRepo) {
		$this->movieRepo = $movieRepo;
		$this->listRepo = $listRepo;
	}

	public function all() {
		return view('movies.all', [
			'movies' => $this->movieRepo->all()
		]);
	}

	public function create() {
		return view('movies.create', [
			'years' => $this->listRepo->getYears(),
			'roles' => $this->listRepo->getRoles(),
			'sexes' => $this->listRepo->getSexes()
		]);
	}

	public function store(Request $request) {
		$request->validate([
			'name' => 'required|string',
			'release_year' => 'required|date_format:Y',
			'plot' => 'required|string',
			'poster' => 'required|image',
			'producer' => [
				'required',
				new UserExists('producer'),
			],
			'actors' => 'required|array',
			'actors.*' => [
				'numeric',
				new UserExists('actor'),
			],
		]);

		$data = $request->only('name', 'release_year', 'plot', 'poster', 'producer', 'actors');

		try {
			$this->movieRepo->store($request);
		} catch(Exception $e) {
			Log::error($e->getMessage());
			return back()->with('Error in creating.');
		}

		return redirect('/')
		->with('success','Movie created successfully.');
	}

	public function edit($id) {
		try {
			$movie = $this->movieRepo->view($id);
		} catch(ModelNotFoundException $e) {
			Log::error($e->getMessage());
			abort(404);
		}

		return view('movies.edit', [
			'movie' => $movie,
			'years' => $this->listRepo->getYears()
		]);
	}

	public function update(Request $request, $id) {
		$request->validate([
			'name' => 'required|string',
			'release_year' => 'required|date_format:Y',
			'plot' => 'required|string',
			'poster' => 'nullable|image',
			'producer' => [
				'required',
				new UserExists('producer'),
			],
			'actors' => 'required|array',
			'actors.*' => [
				'numeric',
				new UserExists('actor'),
			],
		]);

		$data = $request->only('name', 'release_year', 'plot', 'poster', 'producer', 'actors');

		try {
			$this->movieRepo->update($id, $request);
		} catch(Exception $e) {
			Log::error($e->getMessage());
			return back()->with('Error in updating.');
		}

		return redirect('/')
		->with('success','Movie updated successfully.');
	}

	public function view($id) {
		try {
			$movie = $this->movieRepo->view($id);
		} catch(ModelNotFoundException $e) {
			Log::error($e->getMessage());
			abort(404);
		}

		return view('movies.view', [
			'movie' => $movie
		]);
	}

	public function delete($id) {
		try {
			$movie = $this->movieRepo->delete($id);
		} catch(ModelNotFoundException $e) {
			Log::error($e->getMessage());
			abort(404);
		}

		return redirect('/')
		->with('success','Movie deleted successfully.');
	}
}
