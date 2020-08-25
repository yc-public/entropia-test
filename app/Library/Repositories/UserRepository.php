<?php
namespace App\Library\Repositories;

interface UserRepository
{
	public function allByCategory($category);
	public function store($request);
}
