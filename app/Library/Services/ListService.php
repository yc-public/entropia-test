<?php

namespace App\Library\Services;

use App\Library\Repositories\ListRepository;
use App\Sex;
use App\Role;

class ListService implements ListRepository
{
	public function getRoles() {
		return Role::select('id', 'name')
			->get();
	}

	public function getSexes() {
		return Sex::select('id', 'name')
			->get();
	}

	public function getYears() {
		$start = now();
		$end = now()->addYears(4);
		$ranged = collect();


		for($start; $start->lt($end); $start->addYear()) {
			$ranged->push($start->format('Y'));
		}

		return $ranged;
	}
}
