<?php

namespace App\Orchid\Layouts\Article;

use App\Models\Article;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ArticleListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'articles';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('image', 'Image')
                ->render(function (Article $article) {
                    return '<img style=" width: 100px;" src='.$article->image.' alt="preview"></img>';
                }),

            TD::make('title', 'Title')
                ->sort()
                ->render(function (Article $article) {
                    return Link::make($article->title)
                        ->route('platform.article.edit', $article);
                }),

            TD::make('author', 'Author')
                ->sort()
                ->render(function (Article $article) {
                    return Link::make($article->author->name)
                        ->route('platform.author.edit', $article->author);
                }),

            
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Article $article) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.article.edit', $article)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the article?'))
                                ->parameters([
                                    'id' => $article->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
