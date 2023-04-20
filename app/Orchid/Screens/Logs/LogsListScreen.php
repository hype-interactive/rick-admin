<?php

namespace App\Orchid\Screens\Logs;

use Orchid\Screen\Screen;

class LogsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'LogsListScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [];
    }
}
