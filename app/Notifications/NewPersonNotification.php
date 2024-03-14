<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use App\Models\Person;

class NewPersonAdded extends Notification
{
    use Queueable;

    protected $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('New person added: ' . $this->person->name)
            ->attachment(function ($attachment) {
                $attachment->title('Person Details', route('people.show', $this->person->id))
                    ->fields([
                        'Name' => $this->person->name,
                        'Birth Date' => $this->person->birth_date,
                    ]);
            });
    }
}
