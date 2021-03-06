<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

/**
 * 
 */
class User extends Model
{
	const SESSION = "User";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";

	public static function getFromSession()
	{

		$user = new User();

		if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0) {

			$user->setData($_SESSION[User::SESSION]);

		}

		return $user;

	}

	public static function checkLogin($inadmin = True)
	{

		if (!isset($_SESSION[User::SESSION]) 
			|| 
			!$_SESSION[User::SESSION] 
			|| 
			!(int)$_SESSION[User::SESSION]["iduser"] > 0) {

			// Not logged in.
			return false;

		} else {

			// Logged in.

			if ($inadmin === true && (bool)$_SESSION[User::SESSION]["inadmin"] === true) {

				// ... and is administrator
				return true;

			} else if ($inadmin === false) {

				// ... and but is not administrator
				return true;

			} else {

				// ... and is not logged in
				return false;

			}
			

		}

		

	}
	
	public static function login($login, $password)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login

		));

		if (count($results) === 0) 
		{
			throw new \Exception("Usuario inexistente ou/e senha inválida.");
		}

		$data = $results[0];

		if (password_verify($password, $data["despassword"]) === true)
		{

			$user = new User();

			$data['desperson'] = utf8_encode($data['desperson']);

			//$user->setiduser($data["iduser"]);

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

			//var_dump($user);

			//exit;

		} else {

			throw new \Exception("Usuario inexistente ou/e senha inválida.");

		}
	}

	public function verifyLogin($inadmin = true)
	{
		//var_dump($inadmin);
		//exit;
		if (!User::checkLogin($inadmin))
		{
			if ($inadmin) {
				header("Location: /arbeitfirma/login");
			} else {
				header("Location: /login");
			}
			exit;
		}
		
	}

	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}

	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY desperson");
	}

	public function save(){
		$sql = new Sql();

		echo $this->getdeslogin();

		exit;

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":desperson"=>utf8_encode($this->getdesperson()),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);

	}

	public function get($iduser)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
			":iduser"=>$iduser
		));

		$this->setData($results[0]);
	}

	public function update()
	{
		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>utf8_encode($this->getdesperson()),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);
	}

	public function delete()
	{
		$sql = new Sql();

		$sql->select("CALL sp_users_delete(:iduser)", array(
			":iduser"=>$this->getiduser()
		));

	}

	public static function getForgot($email)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_persons a INNER JOIN tb_users b USING(idperson) WHERE a.desemail = :email", array(
			"email"=>$email
		));

		if (count($results) === 0) {
			throw new \Exception("Não foi possivel recuperar a senha.");
		} else {
			$data = $results[0];
			$results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
				"iduser"=>$data["iduser"],
				":desip"=>$_SERVER["REMOTE_ADDR"]
			));

			

			if (count($results2) === 0) {
				throw new \Exception("Não foi possivel recuperar a senha.");
			} else {
				define('SECRET_IV', pack('a16', 'senha'));
				define('SECRET', pack('a16', 'senha'));
				$dataRecovery = $results2[0];
				$code = base64_encode(openssl_encrypt(
					json_encode($dataRecovery["idrecovery"]), 
					'AES-256-CBC',
					SECRET,
					0,
					SECRET_IV
				));
				$link = "htttp://http://www.ecommerceed.com.br/arbeitfirma/forgot/reset?code=$code";

				$mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir senha da Store", "forgot", array(
					"name"=>$data["desperson"],
					"link"=>$link
				));

				$mailer->send();

				return $data;

			}
		}
	}


	public static function setError($msg)
	{
		$_SESSION[User::ERROR] = $msg;
	}

	public static function getError()
	{
		$msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';

		User::clearError();

		return $msg;

	}

	public static function clearError()
	{
		$_SESSION[User::ERROR] = NULL;
	}

	public static function setErrorRegister($msg)
	{
		$_SESSION[User::ERROR_REGISTER] = $msg;
	}
	
	public function updateFreight()
	{
		if ($this->getdeszipcode() != '') {
			$this->setFreight($this->getdeszipcode());
		}
	}
}

?>