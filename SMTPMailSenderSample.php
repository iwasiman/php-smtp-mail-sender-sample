<?php

namespace util;


// アプリケーション全体の初期処理などで読み込むと常に読み込まれてしまうので、この部品クラスをコールした必要な時だけにする
require_once __DIR__ . './libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . './libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . './libs/PHPMailer/src/Exception.php';

/**
 * SMTPを用いたメール送信部品のサンプルクラス。

 * @package util
 * @author iwasiman
 */
class SMTPMailSenderSample
{
    private $mailer = null; //  PHPMailer/PHPMailer ライブラリのクラス

    /**
     * コンストラクタ。
     *
     */
    public function __construct(string $host, ?int $port = 25, string $userName, string $password)
    {
        // インスタンスを生成（true指定で例外を有効化する）
        $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        // 文字エンコードを指定
        $mailer->CharSet = 'utf-8';
        // SMTPサーバの設定
        $mailer->isSMTP(); // SMTPの使用宣言
        $mailer->Host = $host; // SMTPサーバーを指定
        $mailer->SMTPAuth = true; // SMTP authenticationを有効化
        $mailer->Username = $userName; // SMTPサーバーのユーザ名
        $mailer->Password = $password; // SMTPサーバーのパスワード
        $mailer->SMTPSecure = false; // 暗号化を有効するなら'tls' or 'ssl' / 無効の場合はfalse
        $mailer->Port = $port; // TCPポートを指定（tlsの場合は465や587）
        $this->mailer = $mailer;
    }

    /**
     * メールを送信します。
     *
     * @param string $email 宛先のメールアドレス(空文字不可)
     * @param array $fromHeader Fromヘッダーを表す文字列配列(長さは1で送信者メールアドレスか、長さ2で送信者メールアドレスと送信者名を指定)
     * @param string $subject メールのタイトル (空文字OK)
     * @param string $body メールの本文(空文字不可)
     * @return boolean true: メール送信に成功 / false: 引数のどれかが間違っている
     * @throws Exception 例外 メール送信時にエラーが発生した場合。
     */
    public function send(string $email, array $fromHeader, string $subject, string $body): bool
    {
        $result = false;
        // Fromヘッダーが正しいかチェックして送信者に設定
        if (count($fromHeader) == 0 || count($fromHeader) > 2) {
            return $result;
        }
        $fromEmail = $fromHeader[0];
        if (is_null($fromEmail) || strlen($fromEmail) === 0) {
            return $result;
        }
        $fromName = null;
        if (count($fromHeader) == 2) {
            $fromName = $fromHeader[1];
        }
        // 送信者 引数$fromHeaderの2つめの送信者名は、nullでなく空文字でもない場合のみメールに入る
        if (is_null($fromName)) {
            $this->mailer->setFrom($fromEmail);
        } else {
            $this->mailer->setFrom($fromEmail, $fromName);
        }

        // 宛先メールアドレスをチェックして設定
        if (strlen($email) === 0) {
            return $result;
        }
        $this->mailer->addAddress($email);

        // メールのタイトル設定(空でも送信されます)
        $this->mailer->Subject = $subject;
        // メールの本分のチェックと設定
        // メール本文が空だとPHPMailerが"Message body empty"のエラーを出すので、送信前に判定
        if (strlen($body) === 0) {
            return $result;
        }
        $this->mailer->Body = $body;

        // メインの送信処理
        try {
            $result = $this->mailer->send();
        } catch (\Exception $e) {
            throw $e;
        }
        return $result;
    }
}