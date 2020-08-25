<?php

namespace App\Library\Services;

use App\Library\Repositories\UserRepository;
use App\User;

use Carbon\Carbon;

class UserService implements UserRepository
{
	public function allByCategory($category) {
		return User::query()
		->when(request()->query('term'), function ($query, $term) {
			return $query->where('name', 'LIKE', "%${term}%");
		})
		->whereHas('role', function($query) use($category) {
			$query->where('name', $category);
		})
		->select('id', 'name')
		->oldest('name')
		->get();
	}

	public function store($request) {
		$user = new User;

		$user->name = $request->name;
		$user->role_id = $request->role;
		$user->sex_id = $request->sex;
		$user->dob = Carbon::createFromFormat('d/m/Y', $request->dob);
		$user->bio = $request->bio;

		$user->save();
		return;
	}
}
