<?php

namespace App\Orchid\Screens\Category;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Category\CategoryArticlesListLayout;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Article;

class CategoryArticlesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Category | Articles';

    public $category;

    public $description;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Category $category): array
    {
        $this->exists = $category->exists;

        if ($this->exists) {
            $category = Category::find($category->id);
            $this->name = $category->name. ' | Articles';
            
            $this->description = 'List of articles in '.$category->name;
        }

        return [
            'articles' => Article::where('category_id', $category->id)->latest('updated_at')->paginate(),
            'category' => $category->id,
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
                ->icon('plus')
                ->route('platform.article.edit', $this->category)
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
            CategoryArticlesListLayout::class
        ];
    }

    public function remove(Request $request)
    {
        $article = Article::findOrFail($request->get('id'));

        if ($article) {
            if ($article->author && $article->author->id == $request->user()->id) {
                $article->delete();
            } else {
                Toast::error(__('You can not delete this article'));
                return;
                // return redirect()->route('platform.articles');
            }
        }

        Toast::info(__('Article was removed'));
    }
}
