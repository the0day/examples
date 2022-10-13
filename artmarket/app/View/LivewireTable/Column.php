<?php

namespace App\View\LivewireTable;


class Column extends \Mediconesystems\LivewireDatatables\Column
{
    public static function edit($name = 'id', string $route)
    {
        return static::callback($name, function ($value) use ($route) {
            return view('datatables::edit', ['id' => $value, 'route' => $route]);
        });
    }


    public static function show(string $route, $name = 'id')
    {
        return static::callback($name, function ($value) use ($route) {
            return view('datatables::show', ['id' => $value, 'route' => $route]);
        });
    }
}
