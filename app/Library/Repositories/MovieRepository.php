<?php
namespace App\Library\Repositories;

interface MovieRepository
{
	public function all();
	public function store($request);
	public function view($id);
	public function update($id, $request);
	public function delete($id);

}
