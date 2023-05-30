<?php

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make(__('Dashboard'))
                ->icon('chart')
                ->route('platform.index'),

            Menu::make(__('Authors'))
                ->icon('people')
                ->route('platform.authors'),

            Menu::make(__('Categories'))
                ->icon('layers')
                ->route('platform.categories'),

            Menu::make(__('Articles'))
                ->icon('note')
                ->route('platform.articles'),

            Menu::make(__('Adverts'))
                ->icon('badge')
                ->route('platform.adverts'),

            Menu::make(__('Lyrics'))
                ->icon('book-open')
                ->route('platform.lyrics'),

            // Menu::make(__('Campaigns'))
            //     ->icon('bubbles')
            //     ->route('platform.compain'),



            // Menu::make(__('Interviews'))
            //     ->icon('microphone')
            //     ->route('platform.interviews'),

            // Menu::make(__('Videos'))
            //     ->icon('video')
            //     ->route('platform.videos'),

            // Menu::make(__('Logs'))
            //     ->icon('config')
            //     ->route('platform.logs')
            //     ->title(__('Logs')),

            // Menu::make('News')
            //     ->icon('docs')
            //     ->route('platform.news'),

            // Menu::make('Faqs')
            //     ->icon('question')
            //     ->route('platform.faqs'),

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            // Menu::make(__('Roles'))
            //     ->icon('lock')
            //     ->route('platform.systems.roles')
            //     ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }

    /**
     * @return string[]
     */
    public function registerSearchModels(): array
    {
        return [
            // ...Models
            // \App\Models\User::class
        ];
    }
}
