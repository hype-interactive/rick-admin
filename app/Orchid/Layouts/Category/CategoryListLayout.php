<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use App\Models\Article;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use Illuminate\Support\Str;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Name')
                ->sort()
                ->render(function (Category $category) {
                    return Link::make(Str::ucfirst($category->name))
                        ->route('platform.category.articles', $category);
                }),

            TD::make('slug', 'Slug')
                ->sort()
                ->render(function (Category $category) {
                    return $category->slug;
                }),

            TD::make('articles', '# of Articles')
                ->render(function (Category $category) {
                    return $category->articles->count();
                }),

            TD::make('created_at', 'Created')
                ->render(function (Category $category) {
                    return $category->created_at->diffForHumans();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Category $category) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make(__('Edit'))
                                ->icon('pencil')
                                ->modal('editCategory')
                                ->modalTitle(__('Edit Category'))
                                ->method('editCategory')
                                ->parameters([
                                    'id' => $category->id,
                                ]),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the category?'))
                                ->parameters([
                                    'id' => $category->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
