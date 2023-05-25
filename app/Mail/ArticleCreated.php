<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArticleCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $client, $article;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $article)
    {
        $this->client = $client;
        $this->article = $article;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $client = $this->client;
        return $this->markdown('email.article-created', compact('client'))
                    ->subject('New Media Article Published');
    }
}
