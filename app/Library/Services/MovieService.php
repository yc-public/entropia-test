<?php

namespace App\Library\Services;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use DB;

use App\Library\Repositories\MovieRepository;
use App\Movie;

class MovieService implements MovieRepository
{
	public function all() {
		return Movie::query()
		->with([
			'producer' => function($query) {
				$query->isProducer()
					->select('users.id', 'name');
			},
			'actors' => function($query) {
				$query->isActor()
					->select('users.id', 'name');
			},
		])
		->latest('release_year')
		->get();
	}

	public function store($request) {
		$transaction = DB::transaction(function() use($request) {
			$movie = new Movie;
			$movie->name = $request->name;
			$movie->release_year = $request->release_year;
			$movie->plot = $request->plot;

			$link = Storage::putFile('images', new File($request->poster));

			$movie->poster = $link;
			$movie->producer_id = $request->producer;
			$movie->save();

			$movie->actors()->sync($request->actors);
		});
		
		return $transaction;
	}

	public function update($id, $request) {
		$transaction = DB::transaction(function() use($id, $request) {
			$movie = Movie::where('id', $id)
				->firstOrFail();

			$movie->name = $request->name;
			$movie->release_year = $request->release_year;
			$movie->plot = $request->plot;
			
			$link = $movie->poster;

			if($request->poster) {
				Storage::delete($movie->poster);
				$link = Storage::putFile('images', new File($request->poster));
			}

			$movie->poster = $link;
			$movie->producer_id = $request->producer;
			$movie->save();

			$movie->actors()->sync($request->actors);
		});
		
		return $transaction;
	}

	public function view($id) {
		return Movie::where('id', $id)
			->with([
				'producer' => function($query) {
					$query->isProducer()
						->with('sex');
				},
				'actors' => function($query) {
					$query->isActor()
						->with('sex');
				},
			])
			->firstOrFail();
	}

	public function delete($id) {
		$transaction = DB::transaction(function() use($id) {
			$movie = Movie::where('id', $id)
				->firstOrFail();

			Storage::delete($movie->poster);
			$movie->actors()->detach();
			$movie->delete($id);
		});
		
		return $transaction;
	}
}
