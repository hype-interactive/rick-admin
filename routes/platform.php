<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\FAQs\FAQsEditScreen;
use App\Orchid\Screens\FAQs\FAQsListScreen;
use App\Orchid\Screens\News\NewsEditScreen;
use App\Orchid\Screens\News\NewsListScreen;
use App\Orchid\Screens\Advert\AdvertEditScreen;
use App\Orchid\Screens\Advert\AdvertListScreen;
use App\Orchid\Screens\Author\AuthorListScreen;
use App\Orchid\Screens\Author\AuthorEditScreen;
use App\Orchid\Screens\Author\AuthorArticlesListScreen;
use App\Orchid\Screens\Article\ArticleListScreen;
use App\Orchid\Screens\Article\ArticleEditScreen;
use App\Orchid\Screens\Category\CategoryListScreen;
use App\Orchid\Screens\Category\CategoryArticlesListScreen;
use App\Orchid\Screens\Interview\InterviewsListScreen;
use App\Orchid\Screens\Interview\InterviewEditScreen;
use App\Orchid\Screens\Lyrics\LyricsListScreen;
use App\Orchid\Screens\Lyrics\LyricsEditScreen;
use App\Orchid\Screens\Logs\LogsListScreen;
use App\Orchid\Screens\Video\VideoListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Home > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Home > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit');

// Home > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Home > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Home > System > Roles > Role
Route::screen('roles/{roles}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Home > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Home > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// NEWS

//platform > news
Route::screen('news', NewsListScreen::class)
    ->name('platform.news')
    ->breadcrumbs(function(Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('News'), route('platform.news'));
    });

// Home > news > edit 
Route::screen('news-edit/{news?}', NewsEditScreen::class)
    ->name('platform.news.edit')
    ->breadcrumbs(function(Trail $trail){
        return $trail
            ->parent('platform.news')
            ->push(__('Edit'), route('platform.news.edit'));
    });

// FAQ

// Home > Faqs
Route::screen('faqs', FAQsListScreen::class)
    ->name('platform.faqs')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Faqs'), route('platform.faqs'));
    });

// Home > Faqs > Edit
Route::screen('faq/{faq?}', FAQsEditScreen::class)
    ->name('platform.faqs.faq')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.faqs')
            ->push(__('Edit'), route('platform.faqs.faq'));
    });


// Authors

// Home > Authors
Route::screen('authors', AuthorListScreen::class)
    ->name('platform.authors')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Authors'), route('platform.authors'));
    });

// Home > Authors > Edit
Route::screen('author/{author?}', AuthorEditScreen::class)
    ->name('platform.author.edit')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.authors')
            ->push(__('Edit'), route('platform.author.edit'));
    });

// Home > Author > Articles
Route::screen('articles-by-author/{author?}', AuthorArticlesListScreen::class)
    ->name('platform.author.articles')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.authors')
            ->push(__('Articles'), route('platform.author.articles'));
    });


// Articles

// Home > Articles
Route::screen('articles', ArticleListScreen::class)
    ->name('platform.articles')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Articles'), route('platform.articles'));
    });

// Home > Article > Edit
Route::screen('article/{article?}', ArticleEditScreen::class)
    ->name('platform.article.edit')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.articles')
            ->push(__('Edit'), route('platform.article.edit'));
    });


// Categories

// Home > Categories
Route::screen('categories', CategoryListScreen::class)
    ->name('platform.categories')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Categories'), route('platform.categories'));
    });


// Home > Category > Articles
Route::screen('articles-by-category/{category?}', CategoryArticlesListScreen::class)
    ->name('platform.category.articles')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.categories')
            ->push(__('Articles'), route('platform.category.articles'));
    });


// Adverts

// Home > Adverts
Route::screen('adverts', AdvertListScreen::class)
    ->name('platform.adverts')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Adverts'), route('platform.adverts'));
    });

// Home > Advert > Edit
Route::screen('advert/{advert?}', AdvertEditScreen::class)
    ->name('platform.advert.edit')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.adverts')
            ->push(__('Edit'), route('platform.advert.edit'));
    });


// Lyrics

// Home > Lyrics
Route::screen('lyrics-list', LyricsListScreen::class)
    ->name('platform.lyrics')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Lyrics'), route('platform.lyrics'));
    });

// Home > Lyrics > Edit
Route::screen('lyrics/{lyrics?}', LyricsEditScreen::class)
    ->name('platform.lyrics.edit')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.lyrics')
            ->push(__('Edit'), route('platform.lyrics.edit'));
    });


// Logs

// Home > Logs
Route::screen('logs', LogsListScreen::class)
    ->name('platform.logs')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Logs'), route('platform.logs'));
    });


// Videos

// Home > Videos
Route::screen('videos', VideoListScreen::class)
    ->name('platform.videos')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Videos'), route('platform.videos'));
    });


// Interviews

// Home > Interviews
Route::screen('interviews', InterviewsListScreen::class)
    ->name('platform.interviews')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push(__('Interviews'), route('platform.interviews'));
    });

// Home > Interview > Edit
Route::screen('interview/{interview?}', InterviewEditScreen::class)
    ->name('platform.interview.edit')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.interviews')
            ->push(__('Edit'), route('platform.interview.edit'));
    });