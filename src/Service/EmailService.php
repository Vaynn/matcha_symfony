<?php
    namespace App\Service;

    use Symfony\Component\Mailer\Transport\TransportInterface;
    use Symfony\Component\Mime\Email;

    class EmailService
    {
        private $mailer;

        public function __construct(TransportInterface $mailer){
            $this->mailer = $mailer;
        }

        public function sendRegistrationEmail(string $to, string $token, string $username): void
        {
            $email = (new Email())
                ->to($to)
                ->subject('Confirm your Account')
                ->html('
                    <p>Please click on the following link to activate your account and be able to log in.</p>
                    <a href="http://localhost:8000/activation?token=' . $token . '&username=' . $username . '">Click here</a>'
                );

            $this->mailer->send($email);
        }
    }
