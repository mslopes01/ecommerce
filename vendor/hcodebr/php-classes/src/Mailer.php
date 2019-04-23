<?php 

namespace Hcode;

use Rain\Tpl;

/**
 * 
 */
class Mailer
{
	const USERNAME = "?";
	const PASSWORD = "?";
	//const ARBEIT = "Arbeit Loja";

	private $mail;
	
	public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
	{


		$config = array(
				"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
				"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
				"debug"         => false // set to false to improve the speed
			   );

		Tpl::configure( $config );

		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}


		$html_msg = $tpl->draw($tplName, true);

		$this->mail = new \PHPMailer;

		$this->mail->isSMTP();

		$this->mail->SMTPDebug = 0;

		$this->mail->Debugoutput = 'html';

		$this->mail->Host = 'smtp.mail.yahoo.com';
		//$this->mail->Host = 'smtp.gmail.com';

		$this->mail->Port = 587;

		$this->mail->SMTPSecure = 'tls';

		$this->mail->SMTPAuth = true;

		$this->mail->Username = Mailer::USERNAME;

		$this->mail->Password = Mailer::PASSWORD;

		$this->mail->setFrom(Mailer::USERNAME, "Arbeit Loja");

		//$this->mail->addAddress($toAddress, $toName);
		$this->mail->addAddress($toAddress, $toName);

		$this->mail->Subject = $subject;

		$this->mail->msgHTML($html_msg);

		$this->mail->AltBody = 'This is a plain-text';

	}

	public function send()
	{
		return $this->mail->send();
	}
}


?>