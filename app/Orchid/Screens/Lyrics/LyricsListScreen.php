<?php

namespace App\Orchid\Screens\Lyrics;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Lyrics\LyricsListLayout;

use Illuminate\Http\Request;

use App\Models\Lyrics;
use App\Models\Artist;

class LyricsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Song Lyrics';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'List of song lyrics';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'lyrics' => Lyrics::with('artist')->latest('updated_at')->paginate(),
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
                ->route('platform.lyrics.edit', null)
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
            Layout::modal('editArtist', [
                Layout::rows([
                    Input::make('artist.name')
                        ->required()
                        ->title('Artist Name')
                ])
            ])->async('asyncGetArtist'),

            LyricsListLayout::class
        ];
    }

    public function asyncGetArtist(Artist $artist): array
    {
        return [
            'artist' => $artist
        ];
    }

    public function editArtist(Request $request, Artist $artist)
    {
        $artist = Artist::findOrFail($request->get('id'));

        $artist->update([
            'name' => $request->get('artist')['name']
        ]);

        Toast::info(__('Artist updated successfully'));

        return redirect()->route('platform.lyrics');
    }

    public function remove(Request $request)
    {
        $lyrics = Lyrics::findOrFail($request->get('id'));

        if ($lyrics) {

            if ($lyrics->artist != null) {
                // dissociate the relations to keep artist's data
                $lyrics->artist()->dissociate($lyrics->artist->id);
                
                $lyrics->delete();
            }

            Toast::info(__('Song lyrics deleted successfully'));
        } else {
            Toast::error(__('Error in deleting song lyrics'));
        }
    }
}
