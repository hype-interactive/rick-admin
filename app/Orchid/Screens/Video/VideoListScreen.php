<?php

namespace App\Orchid\Screens\Video;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Video\VideoListLayout;

use Illuminate\Http\Request;

use App\Models\Video;

class VideoListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Videos';

    public $description = 'List of all video assests';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'videos' => Video::latest('updated_at')->paginate(),
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
            ModalToggle::make('Add video')
                ->modal('addVideo')
                ->modalTitle('Add video')
                ->method('createVideoEntry')
                ->icon('plus'),
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
            Layout::modal('addVideo', [
                Layout::rows([
                    Input::make('video.url')
                        ->title('URL')
                        ->placeholder('https://www.youtube.com/watch?v=xxxxxxxxxxx')
                        ->help('The URL of the video you want to add. This can be a YouTube or any other URL.'),

                    Switcher::make('video.visibility')
                        ->title('Visibility')
                        ->sendTrueOrFalse()
                        ->placeholder('Make this video visible to everyone')
                        ->help('If this is checked, the video will be visible to everyone. If not, only you will be able to see it.'),

                    DateTimer::make('video.published_at')
                        ->title('Publish date')
                        ->placeholder('When should this video be published?')
                        ->help('If you want to schedule this video to be published at a later date, set the date and time here.'),
                ])
            ]),

            Layout::modal('editVideo', [
                Layout::rows([
                    Input::make('video.url')
                        ->title('URL')
                        ->placeholder('https://www.youtube.com/watch?v=xxxxxxxxxxx')
                        ->help('The URL of the video you want to add. This can be a YouTube or any other URL.'),

                    Switcher::make('video.visibility')
                        ->title('Visibility')
                        ->sendTrueOrFalse()
                        ->placeholder('Make this video visible to everyone')
                        ->help('If this is checked, the video will be visible to everyone. If not, only you will be able to see it.'),

                    DateTimer::make('video.published_at')
                        ->title('Publish date')
                        ->placeholder('When should this video be published?')
                        ->help('If you want to schedule this video to be published at a later date, set the date and time here.'),
                ])
            ])->async('asyncGetVideo'),

            VideoListLayout::class
        ];
    }

    /**
     * @param Video $video
     *
     * @return array
     */
    public function asyncGetVideo(Video $video): array
    {
        $this->exists = $video->exists;

        return [
            'video' => $video,
        ];
    }

    /**
     * @param Video    $video
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createVideoEntry(Video $video, Request $request)
    {
        $video->fill($request->get('video'))->save();

        Toast::info(__('Video was saved'));

        return redirect()->route('platform.videos');
    }

    /**
     * @param Video    $video
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateVideoEntry(Video $video, Request $request)
    {
        $video->fill($request->get('video'))->save();

        Toast::info(__('Video was saved'));

        return redirect()->route('platform.videos');
    }

    /**
     * @param Video $video
     * 
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Request $request): void
    {
        $video = Video::findOrFail($request->get('id'));

        if ($video) {
            Toast::info(__('Video was removed'));
        } else {
            Toast::error(__('Error in removing video entry from records'));
        }
    }
}
