<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class SetCreatedAt
{
    public function creating(Model $model)
    {
        $model->setCreatedAt($model->freshTimestamp());
    }
}
