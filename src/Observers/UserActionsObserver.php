<?php

namespace Laraveldaily\Quickadmin\Observers;

use Auth;
use Laraveldaily\Quickadmin\Models\UsersLogs;

class UserActionsObserver
{

    public function saved($model)
    {
        if ($model->wasRecentlyCreated == true) {
            // Data was just created
            $action = 'created';
        } else {
            // Data was updated
            $action = 'updated';
        }
        UsersLogs::create([
            'user_id'      => Auth::user()->id,
            'action'       => $action,
            'action_model' => $model->getTable(),
            'action_id'    => $model->id
        ]);
    }

    public function deleting($model)
    {
        UsersLogs::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_model' => $model->getTable(),
            'action_id'    => $model->id
        ]);
    }
}