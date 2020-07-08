<?php 

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function all();

    public function store(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function show($id);
}