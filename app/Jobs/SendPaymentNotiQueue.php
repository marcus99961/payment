<?php

namespace App\Jobs;

use App\Mail\NotiPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentNotiQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $cc;
    public $payment;
    /**
     * Create a new job instance.
     */
    public function __construct($payment,$cc)
    {
    
        $this->payment = $payment;
        $this->cc = $cc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new NotiPayment($this->payment);
        Mail::to('ap@inyalakehotel.com')->cc($this->cc)->send($email);
    }
}