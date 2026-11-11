<?php

declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

final class Mailer
{
    /** Envía a un grupo de destinatarios de MailRecipients (to + cc + bcc). */
    public function sendToGroup(string $group, string $subject, string $htmlBody, ?string $replyTo = null): void
    {
        $this->compose(MailRecipients::for($group), $subject, $htmlBody, $replyTo)->send();
    }

    /**
     * Arma el correo listo para enviar, sin enviarlo.
     *
     * @param array{to: list<string>, cc?: list<string>, bcc?: list<string>} $recipients
     */
    public function compose(array $recipients, string $subject, string $htmlBody, ?string $replyTo = null): PHPMailer
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
        foreach ($recipients['to'] as $address) {
            $mail->addAddress($address);
        }
        foreach ($recipients['cc'] ?? [] as $address) {
            $mail->addCC($address);
        }
        foreach ($recipients['bcc'] ?? [] as $address) {
            $mail->addBCC($address);
        }
        if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($replyTo);
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = trim(preg_replace('/\s+/u', ' ', strip_tags($htmlBody)) ?? '');
        return $mail;
    }
}
