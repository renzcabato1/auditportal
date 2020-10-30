<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ManagerNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $request_for_action_plan;
    protected $return_action_plans;
    protected $actionplans_for_verification;
    protected $total_dues;
    protected $total_not_dues;
    protected $new_action_plan;
    protected $closed_action_plans;
    public function __construct($request_for_action_plan,$return_action_plans,$actionplans_for_verification,$total_dues,$total_not_dues,$new_action_plan,$closed_action_plans)
    {
        //
        $this->request_for_action_plan = $request_for_action_plan;
        $this->return_action_plans = $return_action_plans;
        $this->actionplans_for_verification = $actionplans_for_verification;
        $this->total_dues = $total_dues;
        $this->total_not_dues = $total_not_dues;
        $this->new_action_plan = $new_action_plan;
        $this->closed_action_plans = $closed_action_plans;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('IAD Portal Email - Weekly')
                ->greeting('Good day,')
                ->line("Please don't forget to check the Audit Portal today. Below is the summary of items for your attention:")
                ->line('    - Request for Action Plans and Target Dates : '.$this->request_for_action_plan)
                ->line('    - Returned Action Plans : '.$this->return_action_plans)
                ->line('    - Action Plans for Updates : '.$this->actionplans_for_verification)
                ->line('As of '.date('F d, Y').' your Business Unit has:')
                ->line('    - Action Plans Due : '.$this->total_dues)
                ->line('    - Action Plans Not Due : '.$this->total_not_dues)
                ->line('    - New Action Plans : '.$this->new_action_plan)
                ->line('    - Closed Action Plans : '.$this->closed_action_plans)
                ->action('Audit Portal', url('/'))
                ->line('This is an auto generated email please do not reply!')
                ->line('Thank you for using our application!');
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
