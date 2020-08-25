<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Repositories\UserRepository;

class UserController extends Controller
{
	public function all($category, UserRepository $repo) {
		return $repo->allByCategory($category);
	}

	public function store(Request $request, UserRepository $repo) {
		$request->validate([
			'role' => 'required|exists:roles,id',
			'name' => 'required|string|unique:users,name',
			'sex' => 'required|exists:sexes,id',
			'dob' => 'required|date_format:d/m/Y',
			'bio' => 'required|string'
		]);

		try {
			$repo->store($request);
		} catch(Exception $e) {
			Log::error($e->getMessage());
			return response()->json('Internal Server Error', 500);
		}

		return response()->json('success', 200);
	}
}
