<?php

namespace App\Repositories;

use App\User;

class UserRepository extends Repository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Get all.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->orderBy('name', 'asc')->get();
    }

    /**
     * Create or update a user.
     *
     * @param  \App\Models\Post $post
     * @param  array  $inputs
     * @param  integer  $user_id
     * @return \App\Models\Post
     */
    protected function save(User $user, array $inputs)
    {
        $user->fill($inputs);

        $user->save();

        return $user;
    }

    /**
     * Create a new user for the given user and data.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function store(array $data)
    {
        return $this->save(new User(), $data);
    }

    public function update(array $data, User $user)
    {
        return $this->save($user, $data);
    }

    public function destroy(User $user)
    {
        return $user->delete();
    }
}
