<?php

namespace App\Orchid\Layouts\Interview;

use App\Models\Interview;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
// use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use Illuminate\Support\Str;

class InterviewListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'interviews';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('title', 'Title')
                ->sort()
                ->render(function (Interview $interview) {
                    return Link::make($interview->title)
                        ->route('platform.interview.edit', $interview);
                }),

            TD::make('body', 'Body')
                ->sort()
                ->render(function (Interview $interview) {
                    return Str::limit($interview->body, 50);
                }),

            // TD::make('slug', 'Slug')
            //     ->sort()
            //     ->render(function (Interview $interview) {
            //         return Link::make($interview->slug)
            //             ->route('platform.interview.edit', $interview);
            //     }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Interview $interview) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.interview.edit', $interview)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the interview?'))
                                ->parameters([
                                    'id' => $interview->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
