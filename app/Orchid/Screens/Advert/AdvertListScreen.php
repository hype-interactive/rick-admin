<?php

namespace App\Orchid\Screens\Advert;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Advert\AdvertListLayout;

use Illuminate\Http\Request;

use App\Models\Advert;

class AdvertListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Adverts';

    public $description = 'List of adverts';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'adverts' => Advert::latest('updated_at')->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.advert.edit', null)
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            AdvertListLayout::class
        ];
    }

    /**
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Advert $advert)
    {
        $advert->delete()
            ? Toast::info(__('Advert was removed'))
            : Toast::error(__('Error'));

        return redirect()->route('platform.adverts');
    }
}
