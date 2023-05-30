<?php

namespace App\Orchid\Screens\Category;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Layouts\Category\CategoryListLayout;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Article;

class CategoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Article Categories';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'List of article categories';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'categories' => Category::with('articles')->latest('updated_at')->paginate(),
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
            // ModalToggle::make(__('Add'))
            //     ->icon('plus')
            //     ->modal('addCategory')
            //     ->modalTitle('Add Category')
            //     ->method('addCategory')
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
            Layout::modal('addCategory', [
                Layout::rows([
                    Input::make('category.name')
                        ->title('Name')
                        ->placeholder('Category name')
                        ->help('Enter the name of the category')
                ])
            ]),

            Layout::modal('editCategory', [
                Layout::rows([
                    Input::make('category.name')
                        ->title('Name')
                        ->help('Enter the name of the category'),
                ])
            ])->async('asyncGetCategory'),

            CategoryListLayout::class
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCategory(Request $request)
    {
        Category::create($request->get('category'));

        Toast::info(__('Category was added'));

        return redirect()->route('platform.categories');
    }

    /**
     * @param Category $category
     *
     * @return array
     */
    public function asyncGetCategory(Category $category): array
    {
        return [
            'category' => $category,
        ];
    }

    /**
     * @param Request $request
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editCategory(Request $request, Category $category)
    {
        $category = Category::findOrFail($request->get('id'));

        $category->update([
            'name' => $request->get('category')['name'],
        ]);

        Toast::info(__('Category was updated'));

        return redirect()->route('platform.categories');
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Request $request): void
    {
        $category = Category::findOrFail($request->get('id'));

        if ($category->articles->count() > 0) {
            Toast::error(__('Category has articles, consider moving them to another category first'));
            return;
        } else {
            $category->delete();
        }

        Toast::info(__('Category was removed'));
    }
}
