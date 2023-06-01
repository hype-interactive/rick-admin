@component('mail::message')
# New Media Article Published

Hello,

A new media article **{{ $article->title }}** has been published. Check it out!

![Article Image]({{ $article->image }})
{{-- {{ $article->image }} --}}


@component('mail::button', ['url' => env('SITE_URL'). strtolower($article->category->name) .'/' . $article->slug])
View
@endcomponent

Thanks,<br>
RickMedia Team
@endcomponent
