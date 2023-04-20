<?php

namespace App\Orchid\Screens\Advert;

use Orchid\Screen\Screen;
use App\Models\Advert;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class AdvertEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Advert management';

    public $description = 'Create Advert';

    public $advert;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Advert $advert): array
    {
        $this->exists = $advert->exists;

        if ($this->exists) {
            $this->advert = $advert;
            $this->name = 'Edit Advert';
            $this->description = 'Update advert details';
        }

        return [
            'advert' => $advert,
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
            // Button::make('Create')
            //     ->icon('note')
            //     ->method('createOrUpdate')
            //     ->canSee(!$this->exists),

            Button::make('Update')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Delete')
                ->icon('trash')
                ->method('delete')
                ->canSee($this->exists)
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
            Layout::rows([
                Group::make([
                    Input::make('advert.title')
                        ->title('Title')
                        ->required()
                        ->placeholder('Title')
                        ->help('Enter the title of the advert'),

                    Select::make('advert.type')
                        ->title('Type')
                        ->required()
                        ->options([
                            'vertical' => 'Vertical',
                            'horizontal' => 'Horizontal',
                        ])
                        ->help('Enter the type of the advert for viewing purposes'),
                ]),

                Group::make([
                    TextArea::make('advert.description')
                        ->title('Description')
                        ->placeholder('Description')
                        ->help('Enter the description of the advert'),

                    Switcher::make('advert.visibility')
                        ->title('Visibility')
                        ->sendTrueOrFalse()
                        ->help('Enter the visibility of the advert, if it is visible or not'),
                ]),

                Group::make([
                    Input::make('advert.price')
                        ->title('Price')
                        ->required()
                        ->type('number')
                        ->help('Enter the price of the advert'),

                    Input::make('advert.link')
                        ->title('Link')
                        ->placeholder('https://example.com')
                        ->help('Enter the link for redirect to the advertised artefact'),
                ]),

                Cropper::make('advert.image')
                    ->title('Image')
                    ->targetUrl()
                    ->help('Enter the image of the advert'),
            ])
        ];
    }

    /**
     * @param Advert $advert
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Advert $advert, Request $request)
    {
        $advert->fill($request->get('advert'))->save();

        Alert::info('You have successfully created an advert.');

        return redirect()->route('platform.adverts');
    }

    /**
     * @param Advert $advert
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Advert $advert)
    {
        $advert->delete()
            ? Alert::info('You have successfully deleted the advert.')
            : Alert::warning('An error has occurred')
        ;

        return redirect()->route('platform.adverts');
    }
}
