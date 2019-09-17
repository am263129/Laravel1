<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class ConfirmDevice extends Mailable
{
    use Queueable, SerializesModels;

    public $device;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($data)
    {
        $this->device = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.confirm_device_message')->with('data', $this->device);
    }
}
