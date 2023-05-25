<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use App\Models\Client;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use App\Http\Integration\Beem\HttpHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Support\Facades\Alert;

class CompainScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Campaign';
    public $description = 'Send SMS to  all clients';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $clients = Client::first();

        if (isset($clients)
            && $clients->count() > 0) {
            $this->description = 'Send SMS to '. $clients->count() .' clients';
        }

        return [
            'clients' => $clients,
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
            Button::make('Send')
                ->icon('paper-plane')
                ->method('createOrUpdate')
                ->canSee(true),
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
            Layout::rows([
                TextArea::make('message')
                ->title('Message')
                ->rows(5)
                ->maxlength(255)
                ->required()
                ->help('1 SMS = 60 characters')
                ->placeholder('Enter your message here'),
            ]),

        ];
    }

    //createCompain
    public function createOrUpdate(Request $request)
    {
        $beem = new HttpHelper();
        if ($beem->send($this->getRecipients(),$request->message)) {
            Alert::info('Message sent successfully');

        } else {
            Alert::error('Message not sent');
        }

        return redirect()->route('platform.compain');
    }

    public function getRecipients(): Array
    {
        $numbers = Client::pluck('phone')->toArray();
        $recipients = [];
        foreach ($numbers as $number) {
            array_push($recipients,array('recipient_id' => substr($number,4,8), 'dest_addr' => $number));
        }

        return $recipients;
    }
}
