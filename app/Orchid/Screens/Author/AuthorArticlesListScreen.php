<?php

namespace App\Orchid\Screens\Author;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Author\AuthorArticlesListLayout;

use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\User;

class AuthorArticlesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Author | Articles';

    public $author;

    public $description;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(User $user): array
    {
        $this->exists = $user->exists;

        if ($this->exists) {
            $author = User::find($user->id);
            $this->name = $author->name. ' | Articles';
            
            $this->description = 'List of articles by '.$author->name;
        }

        return [
            'articles' => Article::where('user_id', $author->id)->latest('updated_at')->paginate(),
            'author' => $author->id,
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
            Link::make('Create new article')
                ->icon('pencil')
                ->route('platform.article.edit', $this->author)
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
            AuthorArticlesListLayout::class
        ];
    }

    public function remove(Request $request): void
    {
        Article::findOrFail($request->get('id'))->delete();

        Toast::info(__('Article was removed'));
    }
}
