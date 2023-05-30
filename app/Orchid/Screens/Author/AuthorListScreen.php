<?php

namespace App\Orchid\Screens\Author;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Author\AuthorListLayout;

use Illuminate\Http\Request;

use App\Models\AuthorDetails;
use App\Models\User;
use App\Models\CustomRole;

class AuthorListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Authors';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'List of authors';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'authors' => User::with('customRole')->where('role_id', CustomRole::where('name', 'author')->first()->id)->paginate(),
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
                ->route('platform.author.edit', null)
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
            AuthorListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        $user = User::with('articles', 'customRole')->findOrFail($request->get('id'));

        if ($user) {
            if ($user->articles->count() > 0) {
                foreach ($user->articles as $article) {
                    $article->delete();
                }
            }

            $user->delete();
            
            Toast::info('Author deleted successfully');
        } else {
            Toast::error('Error in deleting the author.');
        }
    }
}
