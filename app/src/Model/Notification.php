<?php

namespace App\Model;

use Pop\Crypt\Bcrypt;
use Pop\Mail\Mail;

class Notification extends AbstractModel
{

    public function sendVerification($user, $title)
    {
        $host    = $_SERVER['HTTP_HOST'];
        $domain  = str_replace('www.', '', $host);
        $schema  = (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443')) ? 'https://' : 'http://';

        // Set the recipient
        $rcpt = [
            'name'   => $user->username,
            'email'  => $user->email,
            'url'    => $schema . $host . '/verify/' . $user->id . '/' . sha1($user->email),
            'domain' => $domain,
            'title'  => $title
        ];

        // Check for an override template
        $mailTemplate = __DIR__ . '/../../view/mail/verify.txt';

        // Send email verification
        $mail = new Mail($title . ' (' . $domain . ') - Email Verification', $rcpt);
        $mail->from('noreply@' . $domain);
        $mail->setText(file_get_contents($mailTemplate));
        $mail->send();
    }

    public function sendApproval($user, $title)
    {
        $host   = $_SERVER['HTTP_HOST'];
        $domain = str_replace('www.', '', $host);

        // Set the recipient
        $rcpt = [
            'name'   => $user->username,
            'email'  => $user->email,
            'domain' => $domain,
            'title'  => $title
        ];

        // Check for an override template
        $mailTemplate = __DIR__ . '/../../view/mail/approval.txt';

        // Send email verification
        $mail = new Mail($title . ' (' . $domain . ') - Approval', $rcpt);
        $mail->from('noreply@' . $domain);
        $mail->setText(file_get_contents($mailTemplate));
        $mail->send();
    }

    public function sendReset($user, $title)
    {
        $host           = $_SERVER['HTTP_HOST'];
        $domain         = str_replace('www.', '', $host);
        $newPassword    = $this->random();
        $user->password = (new Bcrypt())->create($newPassword);
        $user->save();

        $rcpt = [
            'name'     => $user->username,
            'email'    => $user->email,
            'domain'   => $domain,
            'username' => $user->username,
            'password' => $newPassword,
            'title'    => $title
        ];

        $mailTemplate = __DIR__ . '/../../view/mail/forgot.txt';

        // Send email verification
        $mail = new Mail($title . ' (' . $domain . ') - Password Reset', $rcpt);
        $mail->from('noreply@' . $domain);
        $mail->setText(file_get_contents($mailTemplate));
        $mail->send();
    }

    protected function random()
    {
        $chars = [
            0 => str_split('abcdefghjkmnpqrstuvwxyz'),
            1 => str_split('23456789')
        ];
        $indices = [0, 1];
        $str     = '';

        for ($i = 0; $i < 8; $i++) {
            $index = $indices[rand(0, (count($indices) - 1))];
            $subIndex = rand(0, (count($chars[$index]) - 1));
            $str .= $chars[$index][$subIndex];
        }

        return $str;
    }

}