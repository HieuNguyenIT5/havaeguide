<?php

namespace App\Repositories\User;

interface IUserRepository
{
    public function getAllUser($search);
    public function getUserActive($search);
    public function getUserRemove($search);
    public function create($search);
    public function updateUser($search);
    public function delete($search);
    public function count();
    public function total();
}
