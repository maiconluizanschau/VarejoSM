<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php'; 
require 'PHPMailer/SMTP.php'; 
require 'PHPMailer/Exception.php';
require_once 'autoload.php';

$message_error = "Desculpe, ocorreu um erro ao enviar o formulario.";
$message_ok = "Obrigado pelo envio, iremos retornar o mais breve possível!";

$post_nome = $_POST['nome'];
$post_fone = $_POST['fone'];
$post_email = $_POST['email'];
$post_mensagem = $_POST['mensagem'];

//detect & prevent header injections
$test = "/(content-type|bcc:|cc:|to:)/i";
foreach ( $_POST as $key => $val ) {
if ( preg_match( $test, $val ) ) {
  exit;
	}
}

if (!validate_form())
{
	echo json_encode( array(
		'status'  => 'error',
		'message' => $message_error,
	));		
	exit;
}

/*if (!isRecaptchaValid()) 
{	
	echo json_encode( array(
		'status'  => 'error',
		'message' => $message_error,
	));
	exit;
}*/

//Prepare PHPMailer
$mail = new PHPMailer;
$mail->Subject = "Contato VarejoSM";
$mail->setFrom("contato@varejosm.com.br", "Site VarejoSM");
$mail->addAddress('julianolondero@gmail.com');
$mail->addReplyTo($post_email, $post_name);
$mail->Body    = "<b>Nome:</b> " . $post_nome . "<br><b>Telefone:</b> " . $post_fone . "<br><b>Email:</b> " . $post_email . "<br><b>Mensagem:</b> " . $post_mensagem;
$mail->AltBody = strip_tags($post_message);
$mail->setLanguage('br', 'PHPMailer2');
$mail->isHTML(true);

//PHPMailer User
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Username = 'sistemas@sfhera.com.br';
$mail->Password = 'oeUuDanXiKUG';
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Port = 587;

if( ! $mail->send() ) {
  echo json_encode( array(
	'status'  => 'error',
	'message' => $message_error,
	'reason'  => $mail->ErrorInfo,
  ));
} else {
  echo json_encode( array(
	'status' => 'success',
	'message' => $message_ok,
  ));
}

function validate_form()
{
    $error_count = 0;
	Global $post_email;
	Global $post_nome;
	Global $post_fone;
	Global $post_mensagem;
	
    if (empty($post_email)) {
		Global $message_error;
        $message_error = "Por favor, digite o Email.";
        $error_count ++;
    } 
    elseif (!filter_var($post_email, FILTER_VALIDATE_EMAIL))
    {
		Global $message_error;
        $message_error = "Email inválido.";
        $error_count ++;
    }
    if (empty($post_nome)) {
		Global $message_error;
        $message_error = "Por favor, digite o Nome.";
        $error_count ++;
    }
	if (empty($post_fone)) {
		Global $message_error;
        $message_error = "Por favor, digite o Celular.";
        $error_count ++;
    }
	if (empty($post_mensagem)) {
		Global $message_error;
        $message_error = "Por favor, digite a Mensagem.";
        $error_count ++;
    }
    return $error_count == 0 ? true : false;
}

function isRecaptchaValid()
{
	Global $message_error;
	$message_error = 'Por favor, marque o captcha!';
    $siteSecret = '6LfTQeEUAAAAAA_Jc0KsIY-5xcQt-t2LK0ZJf-IY';
    $recaptcha = new \ReCaptcha\ReCaptcha($siteSecret);
    $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    if ($response->isSuccess()) {
        return true;
    } else {
        return false;
    }
}

?>