<?php

namespace App\Orchid\Screens\Article;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Article\ArticleListLayout;

use Illuminate\Http\Request;

use App\Models\Article;

class ArticleListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Articles';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'List of all articles';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        // dd(Article::with('author')->latest()->take(5)->get());
        return [
            'articles' => Article::with('author')->latest()->paginate(),
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
                ->route('platform.article.edit', null)
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
            ArticleListLayout::class
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $article = Article::findOrFail($request->get('id'));

        if ($article) {
            if ($article->author && $article->author->id == $request->user()->id) {
                $article->delete();
            } else {
                Toast::error(__('You can not delete this article'));
                return redirect()->route('platform.articles');
            }
        }

        Toast::info(__('Article was removed'));

        return redirect()->route('platform.articles');
    }
}
