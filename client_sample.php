<?php
declare(strict_types = 1);
require_once __DIR__ . './SMTPMailSenderSample.php';

use util\SMTPMailSenderSample;

// メール送信部品の呼び出し側コードのサンプルです。

// 必要な値は設定ファイル、DB、他の処理などから取得し、部品クラスを生成します。
$host = "sample.mail.com";
$port = 25;
$user = "sample-smtp-user";
$pass = "sample-smtp-pass";
$mailSender = new SMTPMailSenderSample($host, $port, $user, $pass);

// 必要な値は設定ファイル、DB、他の処理などから取得し、メール１件の情報を組み立てます。
// 宛先メールアドレス
$email = "anonymous@republic.gov";
// ヘッダーにはFROMアドレスと送信者を指定 以下どちらでも動きます
$fromHeader = ['mirage-palace@republic.gov'];
$fromHeader = ['mirage-palace@republic.gov', '陽炎パレス'];
// 通知メールのタイトルを組み立て
$mailSubject = "(作画とキャラデザが)";
// 通知メールの本文を組み立て
$mailBody = "極上だ...";

// メール送信を行います。
$sendResult = false;
try {
    $sendResult = $mailSender->send($email, $fromHeader, $mailSubject, $mailBody);
    if (!$sendResult) {
        // TODO: ここに来れば呼び出し時の引数がおかしい場合です。呼び出し側の処理から早期リターンで抜けるなど。
    }
} catch (\Exception $e) {
    // TODO: エラー処理。ログ記録などを行ってください。
    //var_dump($e->getMessage());
    // ホストが違う,ポート番号が不正: "SMTP Error: Could not connect to SMTP host. Failed to connect to server"
    // 認証に使うユーザー、パスワードが不正："SMTP Error: Could not authenticate."
    // Fromアドレスのメアドが正しくない： "Invalid address:  (From): (ここにメアドの値)"
    // Fromアドレスのメアドが、認証に使うユーザーの保有するものではない： "Sender address rejected: not owned by user (ここにユーザー名)"
    // メール本文が空： "Message body empty"が出る前に部品側でfalseで終了するようにしています
}
if ($sendResult) {
    // TODO: ここまで来ればメール送信が成功しています。成功時のみの処理など。
} else {
    // 送信失敗時は例外に落ちるはずですが、例外が発生せず送信失敗した場合はこちらに来ます。
}


