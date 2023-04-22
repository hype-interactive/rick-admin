<?php

namespace App\Orchid\Screens\Interview;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Interview\InterviewListLayout;

use Illuminate\Http\Request;

use App\Models\Interview;

class InterviewsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Interviews';

    public $description = 'List of all interview records';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'interviews' => Interview::with('video')->latest('updated_at')->paginate()
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
            Link::make('Create Interview Record')
                ->icon('plus')
                ->route('platform.interview.edit', null)
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
            InterviewListLayout::class
        ];
    }

    public function remove(Request $request)
    {
        $interview = Interview::findOrFail($request->get('id'));

        if ($interview) {
            if ($interview->video != null) {
                $interview->video()->dissociate($interview->video->id);

                $interview->delete();
            }
            Toast::info(__('Interview record was removed'));
        } else {
            Toast::error(__('Error in deleting interview record'));
        }

        return back();
    }
}
