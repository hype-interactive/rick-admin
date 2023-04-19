<?php

namespace App\Orchid\Screens\Author;

use Orchid\Screen\Screen;
use App\Models\User;
use App\Models\CustomRole;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class AuthorEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Author management';

    public $description = 'Create Author';

    public $author;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(User $user): array
    {
        $this->exists = $user->exists;

        if ($this->exists) {
            $this->author = $user;
            $this->name = 'Edit Author';
            $this->description = 'Update author details';
        }

        return [
            'author' => $user->load('authorDetails'),
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
                    Input::make('author.name')
                        ->title('Name')
                        ->placeholder('Author name')
                        ->help('Author\'s name'),

                    Input::make('author.email')
                        ->title('Email')
                        ->placeholder('Author email')
                        ->help('Author\'s email'),
                    ]),
                    
                Group::make([
                    Input::make('author.phone')
                        ->title('Phone')
                        ->placeholder('Author phone')
                        ->help('Author\'s phone'),

                    Input::make('author.title')
                        ->title('Author\'s title')
                        ->value($this->author->authorDetails->title ?? '')
                        ->help('Author\'s title within the company'),
                ]),

            ])
        ];
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(User $user, Request $request)
    {
        $user->fill($request->get('author'))->save();

        $user->authorDetails()->updateOrCreate(
            ['user_id' => $user->id],
            ['title' => $request->get('author')['title']]
        );

        Alert::info('You have successfully created an author.');

        return redirect()->route('platform.authors');
    }
}
