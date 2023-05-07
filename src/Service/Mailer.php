<?php
namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

class Mailer{

    public function __construct(private \Symfony\Component\Mailer\Transport\TransportInterface $transport) {
        $this->transport = $transport;
    }
    

    public function sendEmail(
        $to = 'anis.farah@esprit.tn',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Time for Symfony Mailer!',
        $pdfContent = null,$nomPDF='nomPDF'
    ): void {
        $email = (new \Symfony\Component\Mime\Email())
            ->from('ktebinoreply@gmail.com')  
            ->to($to)
            ->subject($subject)
            ->html($content)
            ->attachFromPath('data:application/pdf;base64,'. base64_encode($pdfContent), $nomPDF);

            
        $mailer = new \Symfony\Component\Mailer\Mailer($this->transport);
        $mailer->send($email);
    }
    



}

?>