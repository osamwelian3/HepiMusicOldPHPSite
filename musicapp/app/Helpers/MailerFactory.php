<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Mail\Mailer;
use App\User;

class MailerFactory
{
    protected $mailer;
    protected $fromAddress = "";
    protected $fromName = "";

    /**
     * MailerFactory constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->fromAddress = env('MAIL_FROM_ADDRESS');
        $this->fromName = env('MAIL_FROM_NAME');        
    }
    /**
     * sendWelcomeEmail
     *
     *
     * @param $subject
     * @param $user
     */
    public function sendWelcomeEmail($user)
    {
        $subject = "Thank you for registration";
        try {
            $this->mailer->send("emails.welcome", ['user' => $user, 'subject' => $subject], function ($message) use ($subject, $user) {

                $message->from($this->fromAddress, $this->fromName)
                    ->to($user->email)->subject($subject);

            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return true;
    }
    public function sendEnquiryToDealerEmail($purchaseEnquiry,$allDealer,$loginUser)
    {
        $subject = "New Purchase Enquiry";
        try {
            foreach ($allDealer as $key => $data) {
                $this->mailer->send("emails.purchase_enquiry", ['purchaseEnquiry' => $purchaseEnquiry,'data' => $data,'loginUser' => $loginUser, 'subject' => $subject], function ($message) use ($subject, $data) {

                    $message->from($this->fromAddress, $this->fromName)
                        ->to($data['email'])->subject($subject);

                });
            }    
        } catch (\Exception $ex) {
            Log::error($e->getMessage());
        }
        return true;
    }
}
