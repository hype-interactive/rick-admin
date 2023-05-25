<?php

namespace App\Orchid\Screens\Article;

use Orchid\Screen\Screen;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Client;
use App\Models\CustomRole;
use App\Models\User;
use App\Mail\ArticleCreated;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\SimpleMDE;

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
            ModalToggle::make('Preview')
                ->modal('previewModal')
                ->method('preview')
                ->icon('eye'),

            // ModalToggle::make('Create Tag')
            //     ->modal('tagModal')
            //     ->method('createTag')
            //     ->icon('tag'),

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
            Layout::modal('previewModal', [
                Layout::rows([
                    Input::make('article.title')
                        ->title('Title')
                        ->disabled()
                        ->placeholder('Enter article title')
                        ->help('Enter article title'),

                    Input::make('article.subtitle')
                        ->title('Subtitle')
                        ->disabled()
                        ->placeholder('Article subtitle')
                        ->help('Enter article subtitle'),

                    DateTimer::make('article.published_at')
                        ->title('Published At')
                        ->disabled()
                        ->placeholder('Enter article published date')
                        ->help('Enter article published date'),
                ]),

                Layout::rows([
                    Select::make('article.category_id')
                        ->title('Category')
                        ->fromModel(Category::class, 'name')
                        ->disabled()
                        ->help('Select article category'),

                    Select::make('article.user_id')
                        ->title('Author')
                        ->fromModel(User::where('role_id', CustomRole::where('name', 'author')->first()->id), 'name')
                        ->disabled()
                        ->help('Select article author'),

                    // Select::make('article.tags.')
                    //     ->title('Tags')
                    //     ->multiple()
                    //     ->fromModel(Tag::class, 'name')
                    //     ->disabled()
                    //     ->help('Select article tags, to select multiple tags hold down the Ctrl (windows) / Command (Mac) button and click on the desired options.'),
                ]),

                Layout::rows([
                    Switcher::make('article.visibility')
                        ->title('Visibility')
                        ->disabled()
                        ->sendTrueOrFalse()
                        ->placeholder('Select article visibility')
                        ->help('Select article visibility'),

                    Switcher::make('article.pin')
                        ->title('Pin')
                        ->disabled()
                        ->sendTrueOrFalse()
                        ->placeholder('Select article pin')
                        ->help('Select article pin'),
                ]),
            ]),

            Layout::modal('tagModal', [
                Layout::rows([
                    Input::make('tag.name')
                        ->title('Name')
                        ->required()
                        ->placeholder('Enter tag name')
                        ->help('Enter tag name'),
                ])
            ])->title('Create Tag')->applyButton('Create'),

            Layout::rows([
                Group::make([
                    Input::make('article.title')
                        ->title('Title')
                        ->required()
                        ->placeholder('Enter article title')
                        ->help('Enter article title'),

                    Input::make('article.subtitle')
                        ->title('Subtitle')
                        ->required()
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
                        ->required()
                        ->fromModel(Category::class, 'name')
                        ->help('Select article category'),

                    Select::make('article.user_id')
                        ->title('Author')
                        ->fromModel(User::where('role_id', CustomRole::where('name', 'author')->first()->id), 'name')
                        ->help('Select article author'),

                    // Select::make('article.tags.')
                    //     ->title('Tags')
                    //     ->multiple()
                    //     ->fromModel(Tag::class, 'name')
                    //     ->help('Select article tags, to select multiple tags hold down the Ctrl (windows) / Command (Mac) button and click on the desired options.'),
                ]),

                Group::make([
                    Switcher::make('article.visibility')
                        ->title('Visibility')
                        ->sendTrueOrFalse()
                        ->placeholder('Select article visibility')
                        ->required()
                        ->help('Select article visibility'),

                    Switcher::make('article.pin')
                        ->title('Pin')
                        ->sendTrueOrFalse()
                        ->placeholder('Select article pin')
                        ->help('Select article pin'),
                ]),

                Cropper::make('article.image')
                    ->title('Image')
                    ->required()
                    ->targetUrl()
                    ->help('Upload article image'),


                Quill::make('article.content')
                    ->title('Content')
                    ->required()
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

        // $articleTags = $request->get('article')['tags'];

        // foreach ($articleTags as $tag) {
        //     // create entries in article_tag table
        //     ArticleTag::firstOrCreate([
        //         'article_id' => $article->id,
        //         'tag_id' => $tag
        //     ]);
        // }

        // send email notification to all subscribed clients
        $subscribers = Client::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new ArticleCreated($subscriber, $article));
        }

        Alert::info('You have successfully created an article.');

        return redirect()->route('platform.articles');
    }

    /**
     * @param Article $article
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Article $article)
    {
        $article->delete();

        Alert::info('You have successfully deleted the article.');

        return redirect()->route('platform.articles');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTag(Request $request)
    {
        $tag = new Tag();
        $tag->fill($request->get('tag'))->save();

        Alert::info('You have successfully created a tag.');

        return back();
    }
}
