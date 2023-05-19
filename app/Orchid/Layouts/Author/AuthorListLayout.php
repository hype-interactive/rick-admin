<?php

namespace App\Orchid\Layouts\Author;

use App\Models\AuthorDetails;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuthorListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'authors';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Name')
                ->sort()
                ->render(function (User $user) {
                    return Link::make($user->name)
                        ->route('platform.author.articles', $user);
                }),

            TD::make('email', 'Email')
                ->sort()
                ->render(function (User $user) {
                    return $user->email;
                }),

            TD::make('articles', '# of Articles')
                ->render(function (User $user) {
                    return $user->articles->count();
                }),

            // TD::make('created_at', 'Created')
            //     ->render(function (User $user) {
            //         return $user->created_at->diffForHumans();
            //     }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (User $user) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.author.edit', $user)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the author?'))
                                ->parameters([
                                    'id' => $user->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
