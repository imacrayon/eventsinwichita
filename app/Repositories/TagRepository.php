<?php

namespace App\Repositories;

use App\User;
use App\Tag;

class TagRepository extends Repository
{
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
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
     * Create or update the tag.
     *
     * @param  \App\Tag $tag
     * @param  array  $inputs
     * @param  integer  $user_id
     * @return \App\Tag
     */
    protected function save(Tag $tag, array $inputs)
    {
        $tag->fill($inputs);

        $tag->save();

        return $tag;
    }

    /**
     * Create a new tag for the given user and data.
     *
     * @param  array  $data
     * @return \App\Tag
     */
    public function store(array $data)
    {
        $tag = $this->model->where('name', $data['name'])->first();
        if (!is_null($tag)) {
            return $tag;
        }

        return $this->save(new Tag(), $data);
    }

    /**
     * Update the tag.
     *
     * @param  array  $data
     * @return \App\Tag
     */
    public function update(array $data, Tag $tag)
    {
        return $this->save($tag, $data);
    }

    /**
     * Destroy the tag.
     *
     * @param  Tag $tag
     * @return boolean
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
    }
}
