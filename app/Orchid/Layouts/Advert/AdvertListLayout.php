<?php

namespace App\Orchid\Layouts\Advert;

use App\Models\Advert;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AdvertListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'adverts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('type', 'Type')
                ->sort()
                ->render(function (Advert $advert) {
                    return $advert->type == 'vertical' ? 'V' : 'H';
                }),

            TD::make('title', 'Title')
                ->sort()
                ->render(function (Advert $advert) {
                    return Link::make($advert->title)
                        ->route('platform.advert.edit', $advert);
                }),

            TD::make('image', 'Image')
                ->render(function (Advert $advert) {
                    return $advert->image ? '<img src="' . $advert->image . '" width="100" alt="ad" />' : '';
                }),

            TD::make('price', 'Price')
                ->sort()
                ->render(function (Advert $advert) {
                    return number_format($advert->price, 2);
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Advert $advert) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.advert.edit', $advert)
                                ->icon('pencil'),

                            // Button::make(__('Delete'))
                            //     ->method('remove')
                            //     ->confirm(__('Are you sure you want to delete the advert?'))
                            //     ->parameters([
                            //         'id' => $advert->id,
                            //     ])
                            //     ->icon('trash'),
                        ]);
                }),
        ];
    }
}
