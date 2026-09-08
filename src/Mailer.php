<?php

declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

final class Mailer
{
    public function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): void
    {
        $host = (string) config('mail.host', '');
        if ($host === '') {
            throw new RuntimeException('SMTP no configurado.');
        }
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = (int) config('mail.port', 587);
        $mail->SMTPAuth = (string) config('mail.user', '') !== '';
        $mail->Username = (string) config('mail.user', '');
        $mail->Password = (string) config('mail.pass', '');
        $encryption = (string) config('mail.encryption', 'tls');
        if ($encryption !== '') {
            $mail->SMTPSecure = $encryption;
        }
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->setFrom((string) config('mail.from'), (string) config('mail.from_name', 'Web Hidrocinco'));
        $mail->addAddress($to);
        if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($replyTo);
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = trim(preg_replace('/\s+/u', ' ', strip_tags($htmlBody)) ?? '');
        $mail->send();
    }
}
