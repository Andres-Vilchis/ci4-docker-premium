<?php

use App\Models\UserModel;

function user()
{
    $id = session()->get('user_id');

    if (!$id) {
        return null;
    }

    return (new UserModel())->find($id);
}

function auth_check(): bool
{
    return session()->has('user_id');
}