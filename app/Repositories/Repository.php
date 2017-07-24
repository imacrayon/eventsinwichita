<?php

namespace App\Repositories;

abstract class Repository
{
    /**
     * The Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Get Model by id.
     *
     * @param  int  $id
     * @return \App\Model
     */
    public function get($id)
    {
        return $this->model->findOrFail($id);
    }
}
