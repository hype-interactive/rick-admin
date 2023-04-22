<?php

namespace App\Orchid\Layouts\Video;

use App\Models\Video;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class VideoListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'videos';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('thumbnail', 'Thumbnail')
                ->render(function (Video $video) {
                    return $video->url ? '<img src="' . $video->url . '" width="100" alt="video" />' : '';
                }),

            TD::make('visibility', 'Visibility')
                ->sort()
                ->render(function (Video $video) {
                    return $video->visibility == 1 ? 'Public' : 'Private';
                }),

            TD::make('published', 'Published')
                ->sort()
                ->render(function (Video $video) {
                    return $video->published_at ? $video->published_at->format('d/m/Y') : 'time error';
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Video $video) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make(__('Edit'))
                                ->modal('editVideo')
                                ->modalTitle(__('Edit video'))
                                ->icon('pencil')
                                ->method('updateVideoEntry')
                                ->parameters([
                                    'id' => $video->id,
                                ]),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the video?'))
                                ->parameters([
                                    'id' => $video->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }

    
}
