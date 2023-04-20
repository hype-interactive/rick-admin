<?php

namespace App\Orchid\Screens\Lyrics;

use Orchid\Screen\Screen;
use App\Models\Lyrics;
use App\Models\Artist;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Quill;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class LyricsEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Song Lyrics management';

    public $description = 'Create lyrics';

    public $lyrics;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Lyrics $lyrics): array
    {
        $this->exists = $lyrics->exists;

        if ($this->exists) {
            $this->lyrics = $lyrics;
            $this->name = 'Edit Song Lyrics';
            $this->description = 'Update song lyrics details';
        }

        return [
            'lyrics' => $lyrics->load('artist'),
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
            ModalToggle::make('Add Artist')
                ->modal('addArtist')
                ->icon('user')
                ->modalTitle('Add Artist')
                ->method('addArtist'),

            Button::make('Create')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

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
            Layout::modal('addArtist', [
                Layout::rows([
                    Input::make('artist.name')
                        ->required()
                        ->title('Name')
                        ->help('Enter the artist\'s name, if not present in choices')
                ])
            ])->title('Add Artist'),

            Layout::rows([
                Group::make([
                    Input::make('lyrics.song')
                        ->required()
                        ->title('Song Title')
                        ->help('Enter the song title'),

                    Select::make('lyrics.artist_id')
                        ->fromModel(Artist::class, 'name')
                        ->title('Artist Name')
                        ->required()
                        ->help('Choose artist whose song is one being managed. If not in the list use the "Add Artist" button to add new entry')
                ]),

                Group::make([
                    Input::make('lyrics.audio_link')
                        ->required()
                        ->title('Audio Link')
                        ->help('Enter link to song audio'),

                    Input::make('lyrics.video_link')
                        ->title('Video Link')
                        ->help('Enter link to official video')
                ]),

                Group::make([
                    Input::make('lyrics.album')
                        ->title('Album Name')
                        ->help('Album name, if any, to which the song belong to.'),

                    Switcher::make('lyrics.visibility')
                        ->title('Visibility')
                        ->sendTrueOrFalse()
                        ->help('Specify whether the song is visible to public or not')
                ]),

                Quill::make('lyrics.content')
                    ->title('Song Lyrics')
                    ->required()
                    ->placeholder('Enter song lyrics, feel free to format as you like'),

                Group::make([
                    Cropper::make('lyrics.image')
                        ->title('Image')
                        ->targetUrl()
                        ->help('Optional entry for image accompanying the lyrics display'),

                    Cropper::make('lyrics.cover_image')
                        ->title('Cover Image')
                        ->targetUrl()
                        ->help('Optional cover image, if none a default image will be used from the site')
                ])
            ])
        ];
    }

    /**
     * @param Lyrics $lyrics
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Lyrics $lyrics, Request $request)
    {
        $lyrics->fill($request->get('lyrics'))->save();

        Alert::info('You have successfully saved a lyrics entry.');

        return redirect()->route('platform.lyrics');
    }

    /**
     * @param Lyrics $lyrics
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Lyrics $lyrics)
    {
        $lyrics->delete()
            ? Alert::info('You have successfully deleted the lyrics.')
            : Alert::warning('An error has occurred')
        ;

        return redirect()->route('platform.lyrics');
    }

    public function addArtist(Request $request)
    {
        $artistName = $request->get('artist')['name'];

        // check if artist already exists
        $artist = Artist::where('name', $artistName)->first();

        if ($artist) {
            Alert::info('Artist already exists, proceed with lyrics creation');
        } else {
            $artist = new Artist;
            $artist->name = $artistName;
            $artist->save();

            Alert::info('You have successfully created an artist.');
        }

        return redirect()->route('platform.lyrics.edit', $this->lyrics ?? null);
    }
}
