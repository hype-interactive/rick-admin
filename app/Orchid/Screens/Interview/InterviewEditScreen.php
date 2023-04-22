<?php

namespace App\Orchid\Screens\Interview;

use Orchid\Screen\Screen;
use App\Models\Interview;
use App\Models\Video;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Quill;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class InterviewEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Interview Record Management';

    public $description = 'Create Interview Record';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Interview $interview): array
    {
        $this->exists = $interview->exists;

        if ($this->exists) {
            $this->name = 'Edit Interview Record';
            $this->description = 'Update interview record details';
        }

        return [
            'interview' => $interview,
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
                ->method('remove')
                ->canSee($this->exists),
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
                    Input::make('interview.title')
                        ->title('Title')
                        ->placeholder('Interview Title')
                        ->help('Enter the title of the interview')
                        ->required(),

                    Input::make('interview.subtitle')
                        ->title('Subtitle')
                        ->placeholder('Interview Subtitle')
                        ->help('Enter the subtitle of the interview')
                        ->required(),
                ]),

                Group::make([
                    Select::make('interview.video_id')
                        ->title('Video')
                        ->fromModel(Video::class, 'title')
                        ->help('Select the video for the interview')
                        ->required(),

                    Switcher::make('interview.visibility')
                        ->title('Visibility')
                        ->sendTrueorFalse()
                        ->placeholder('Specify if the interview is public or private viewable')
                        ->help('Specify if the interview is for public viewing or private viewing')
                        ->required(),
                ]),

                Quill::make('interview.body')
                    ->title('Body')
                    ->placeholder('Interview Body')
                    ->help('Enter the body of the interview')
                    ->required(),
            ]),
        ];
    }

    /**
     * @param Interview $interview
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Interview $interview, Request $request)
    {
        $interview->fill($request->get('interview'))->save();

        Alert::info('You have successfully created an interview.');

        return redirect()->route('platform.interview.list');
    }

    /**
     * @param Interview $interview
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Interview $interview)
    {
        $interview = Interview::findOrFail($request->get('id'));

        if ($interview) {
            if ($interview->video != null) {
                $interview->video()->dissociate($interview->video->id);
                $interview->delete()
                ? Alert::info('You have successfully deleted the interview.')
                : Alert::warning('An error has occurred while deleting the interview.');
            } else {
                $interview->delete();
            }
        } else {
            Alert::warning('An error has occurred while deleting the interview.');
        }

        return redirect()->route('platform.interviews');
    }
}
