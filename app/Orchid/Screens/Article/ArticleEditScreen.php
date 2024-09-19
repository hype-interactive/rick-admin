<?php

namespace App\Orchid\Screens\Article;

use App\Models\{Article, ArticleTag, Tag, Category, Client, CustomRole, User};
use App\Mail\ArticleCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, Log, Storage};
use Orchid\Screen\{Screen, Fields\Input, Fields\TextArea, Fields\Group, Actions\Button, Actions\ModalToggle, Fields\Select, Fields\Switcher, Fields\DateTimer, Fields\Quill, Fields\Cropper};
use Orchid\Support\Facades\{Layout, Alert};
use Intervention\Image\Facades\Image;

class ArticleEditScreen extends Screen
{
    public $name = 'Article Management';
    public $description = 'Create Article';
    protected $exists = false;

    public function query(Article $article): array
    {
        $this->exists = $article->exists;

        if ($this->exists) {
            $this->name = 'Edit Article';
            $this->description = 'Update article details';
        }

        return compact('article');
    }

    public function commandBar(): array
    {
        return [
            ModalToggle::make('Create Tag')
                ->modal('tagModal')
                ->method('createTag')
                ->icon('tag'),

            Button::make($this->exists ? 'Update' : 'Create')
                ->icon($this->exists ? 'pencil' : 'note')
                ->method('createOrUpdate'),

            Button::make('Delete')
                ->icon('trash')
                ->method('delete')
                ->canSee($this->exists),
        ];
    }

    public function layout(): array
    {
        return [
            $this->getTagModal(),
            Layout::rows([
                $this->getBasicInfoFields(),
                $this->getCategoryAndTagFields(),
                $this->getVisibilityFields(),
                Cropper::make('article.image')
                    ->title('Image')
                    ->required()
                    ->targetRelativeUrl()
                    ->help('Upload article image'),
                Quill::make('article.content')
                    ->title('Content')
                    ->required()
                    ->placeholder('Enter article content')
                    ->help('Enter article content')
            ])
        ];
    }

    private function getTagModal()
    {
        return Layout::modal('tagModal', [
            Layout::rows([
                Input::make('tag.name')
                    ->title('Name')
                    ->required()
                    ->placeholder('Enter tag name')
                    ->help('Enter tag name'),
            ])
        ])->title('Create Tag')->applyButton('Create');
    }

    private function getBasicInfoFields(): Group
    {
        return Group::make([
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
                ->required()
                ->placeholder('Enter article published date')
                ->help('Enter article published date'),
        ]);
    }

    private function getCategoryAndTagFields(): Group
    {
        return Group::make([
            Select::make('article.category_id')
                ->title('Category')
                ->required()
                ->fromModel(Category::class, 'name')
                ->help('Select article category'),

            Select::make('article.user_id')
                ->title('Author')
                ->fromModel(User::where('role_id', CustomRole::where('name', 'author')->first()->id), 'name')
                ->help('Select article author'),

            Select::make('article.tags.')
                ->title('Tags')
                ->required()
                ->multiple()
                ->fromModel(Tag::class, 'name')
                ->help('Select article tags. Hold Ctrl/Cmd to select multiple.'),
        ]);
    }

    private function getVisibilityFields(): Group
    {
        return Group::make([
            Switcher::make('article.visibility')
                ->title('Visibility')
                ->sendTrueOrFalse()
                ->required()
                ->help('Select article visibility'),

            Switcher::make('article.pin')
                ->title('Pin')
                ->sendTrueOrFalse()
                ->help('Select article pin'),
        ]);
    }


    public function createOrUpdate(Article $article, Request $request)
    {
        $articleData = $request->get('article');
        $article->fill($articleData);
        $article->published_at = $articleData['published_at'] ?? now();
        $article->image = $this->convertToWebp($articleData['image']);
        $article->content = $this->decodeContent($articleData['content']);

        $article->save();

        $this->syncTags($article, $articleData['tags'] ?? []);

        if (!$this->exists && $article->visibility) {
            $this->notifySubscribers($article);
        }

        Alert::info('Article ' . ($this->exists ? 'updated' : 'created') . ' successfully.');

        return redirect()->route('platform.articles');
    }

    // ... [other methods remain unchanged]

    private function syncTags(Article $article, array $tagIds)
    {
        // Remove all existing tag associations
        $article->articleTags()->delete();

        // Create new tag associations
        foreach ($tagIds as $tagId) {
            ArticleTag::create([
                'article_id' => $article->id,
                'tag_id' => $tagId
            ]);
        }
    }

    public function delete(Article $article)
    {
        $article->delete();
        Alert::info('Article deleted successfully.');
        return redirect()->route('platform.articles');
    }

    public function createTag(Request $request)
    {
        Tag::create($request->get('tag'));
        Alert::info('Tag created successfully.');
        return back();
    }

    private function convertToWebp($imageData)
    {
        try {
            $image = Image::make($imageData);
            $image->encode('webp', 80);

            $webpPath = 'public/images/' . uniqid('webp_') . '.webp';
            Storage::put($webpPath, $image->encoded);

            if (Storage::exists($imageData)) {
                Storage::delete($imageData);
            }

            return Storage::url($webpPath);
        } catch (\Exception $e) {
            Log::error('Image conversion failed: ' . $e->getMessage());
            return $imageData;  // Return original image if conversion fails
        }
    }


    private function notifySubscribers(Article $article)
    {
        $subscribers = Client::all();
        try {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->queue(new ArticleCreated($subscriber, $article));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    private function decodeContent($content)
    {
        // Implement your decoding logic here
        return $content;
    }
}
