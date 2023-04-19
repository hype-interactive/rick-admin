<?php

namespace App\Orchid\Screens\Article;

use Orchid\Screen\Screen;
use App\Models\Article;
use App\Models\Category;
use App\Models\CustomRole;
use App\Models\User;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class ArticleEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Article Management';

    public $description = 'Create Article';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Article $article): array
    {
        $this->exists = $article->exists;

        if ($this->exists) {
            $this->name = 'Edit Article';
            $this->description = 'Update article details';
        }

        return [
            'article' => $article,
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
                    Input::make('article.title')
                        ->title('Title')
                        ->required()
                        ->placeholder('Enter article title')
                        ->help('Enter article title'),

                    Input::make('article.subtitle')
                        ->title('Subtitle')
                        ->placeholder('Article subtitle')
                        ->help('Enter article subtitle'),

                    DateTimer::make('article.published_at')
                        ->title('Published At')
                        ->placeholder('Enter article published date')
                        ->help('Enter article published date'),
                ]),

                Group::make([
                    Select::make('article.category_id')
                        ->title('Category')
                        ->fromModel(Category::class, 'name')
                        ->help('Select article category'),

                    Select::make('article.user_id')
                        ->title('Author')
                        ->fromModel(User::where('role_id', CustomRole::where('name', 'author')->first()->id), 'name')
                        ->help('Select article author'),
                ]),

                Group::make([
                    Switcher::make('article.visibility')
                        ->title('Visibility')
                        ->sendTrueOrFalse()
                        ->placeholder('Select article visibility')
                        ->help('Select article visibility'),

                    Switcher::make('article.pin')
                        ->title('Pin')
                        ->sendTrueOrFalse()
                        ->placeholder('Select article pin')
                        ->help('Select article pin'),
                ]),

                Cropper::make('article.image')
                    ->title('Image')
                    ->targetUrl()
                    ->help('Upload article image'),

                Quill::make('article.content')
                    ->title('Content')
                    ->placeholder('Enter article content')
                    ->help('Enter article content'),
            ])
        ];
    }

    /**
     * @param Article $article
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Article $article, Request $request)
    {
        $article->published_at = $request->get('article.published_at') ? $request->get('article.published_at') : now(); // Set default value
        $article->fill($request->get('article'))->save();

        Alert::info('You have successfully created an article.');

        return redirect()->route('platform.articles');
    }
}
