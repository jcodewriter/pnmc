<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getUser(Request $request)
    {
        return $request->user();
    }
}
