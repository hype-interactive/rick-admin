<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\ChartsLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Rick Media';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Welcome to Rick Media Admin Panel';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {

        return [];

        // $charts = [
        //     [
        //         'labels' => ['january','february','march','april','may','june','july'],
        //         'title'  => 'Some Data',
        //         'values' => [25, 40, 30, 35, 8, 52, 17, -4],
        //     ],
        // ];
    
        // return [
        //     'charts' => $charts,
        // ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Go to Site')
                ->href('https://rickmedia.hype.co.tz')
                ->target('_blank')
                ->icon('globe-alt'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            // ChartsLayout::class
        ];
    }
}
