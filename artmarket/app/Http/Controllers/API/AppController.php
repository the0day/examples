<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function index()
    {
        return [
            'menu' => [
                [
                    'title' => 'Test',
                    'url'   => '/test/'
                ],
                [
                    'title' => 'Test 2',
                    'url'   => '/test2/'
                ]
            ]
        ];
    }
}
