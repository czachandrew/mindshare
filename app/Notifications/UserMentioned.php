<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Spark\Notifications\SparkNotification; 
use Laravel\Spark\Notifications\SparkChannel;
use App\Company;
use App\Note;
use App\User;

class UserMentioned extends Notification
{
    use Queueable;

    protected $company;
    protected $author;
    protected $mentioned;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type, $typeobj, User $author, User $mentioned)
    {
        $this->type = $type; 
        $this->typeobj = $typeobj;
        $this->author = $author;
        $this->mentioned = $mentioned;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SparkChannel::class];
    }

    /**
    * Create the spark notification
    * @param mixed $notifiable
    * @return \Spark\Notifications\SparkNotfication
    */

    public function toSpark($notifiable)
    {
        if(strpos($this->type, 'Task')){
            //let's create the body here
            $message = $this->author->name . ' just mentioned you in a Task ' . $this->truncate($this->typeobj->title);
            $action = "/tasks/" . $this->typeobj->id;
        } elseif(strpos($this->type, 'Quote')) {
            //it's a quote 
            $message = $this->author->name . ' just mentioned you on a Quote ' . $this->typeobj->title; 
            $action  = "/quotes/" . $this->typeobj->id;
        }else {
            //it's a company 
            $message = $this->author->name . ' just mentioned you on ' .  $this->typeobj->name; 
            $action = "/company/" . $this->typeobj->id;
        }
        return (new SparkNotification)
            ->from($this->author)
            ->action('Check it out', $action)
            ->icon('fa-users')
            ->body($message);
    }

    public function truncate($string,$length=18,$append="&hellip;") {
      $string = trim($string);

      if(strlen($string) > $length) {
        $string = wordwrap($string, $length);
        $string = explode("\n", $string, 2);
        $string = $string[0] . $append;
    }

    return $string;
}

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if(strpos($this->type, 'Task')){
            //let's create the body here
            $message = $this->author->name . ' just mentioned you in a Task ' . $this->typeobj->title;
            $action = "/tasks/" . $this->typeobj->id;
        } elseif(strpos($this->type, 'Quote')) {
            //it's a quote 
            $message = $this->author->name . ' just mentioned you on a ' . $this->typeobj->title; 
            $action  = "/quotes/" . $this->typeobj->id;
        }else {
            //it's a company 
            $message = $this->author->name . ' just mentioned you on ' .  $this->typeobj->name; 
            $action = "/company/" . $this->typeobj->id;
        }

        return (new MailMessage)
                    ->subject('Someone mentioned you...')
                    ->line($message)
                    ->from('mindshare@mindshare.com')
                    ->action('Check it out', url($action))
                    ->line('Thanks for using MindShare');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
