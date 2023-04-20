<?php

namespace App\Orchid\Layouts\Lyrics;

use App\Models\Lyrics;
use App\Models\Artist;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LyricsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'lyrics';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('song', 'Song')
                ->sort()
                ->render(function (Lyrics $lyrics) {
                    return Link::make($lyrics->song)
                        ->route('platform.lyrics.edit', $lyrics);
                }),

            TD::make('artist', 'Artist')
                ->sort()
                ->render(function (Lyrics $lyrics) {
                    return ModalToggle::make($lyrics->artist->name)
                            ->modal('editArtist')
                            ->modalTitle('Edit Artist')
                            ->method('editArtist')
                            ->parameters([
                                'id' => $lyrics->artist->id
                            ]);
                }),

            TD::make('visibility', 'Visibility')
                ->render(function (Lyrics $lyrics) {
                    return $lyrics->visibility ? 'Public' : 'Private';
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Lyrics $lyrics) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.lyrics.edit', $lyrics)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the lyrics?'))
                                ->parameters([
                                    'id' => $lyrics->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
