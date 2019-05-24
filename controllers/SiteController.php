<?php
namespace controllers;

use models\Db;
use controllers\http\JWT;

class SiteController
{
	
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');

		if ($_SERVER['HTTP_HOST'] != 'localhost') {
			//die(http_response_code(401));
		}
	}

	public function monitoria($request){
		//print_r($request);
		$mes = $request['mes'];
		$ano = $request['ano'];
		$date = date_create_from_format("m/Y",$mes."/".$ano);
		
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/monitoria.json");
		$data = json_decode($data);
		
		foreach($data as $key) {
			$this_date = date_create_from_format("m/Y", $key->date);

			if ($this_date == $date){
				return print_r(json_encode($key));
			}
		}
	}

	public function login($request){
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/users.json");		
		$data = json_decode($data);

		print_r($request);

		if(count($data) === 1){
			JWT::setJWT($request);
			return http_response_code(200);
		}else{
			$msg = ['msg'=>'Email ou senha inválido(s)'];
			print_r(json_encode($msg));
			return http_response_code(405);
		}
	}

	public function aderencia(){
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/aderencia.json");
		print_r($data);
	}

	public function monitoriaT(){
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/monitoria.json");		
		print_r($data);
	}

	public function produtividade(){
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/produtividade.json");		
		print_r($data);
	}

	public function qualidade(){
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/qualidade.json");		
		print_r($data);
	}

	public function qualidadeItem($request){
		$data = file_get_contents(_HOSTNPORT_._BASEPATH_."/data/qualidade_dia.json");
		$data = json_decode($data);
		
		foreach($data as $key) {
			//print_r($key);
			if ($key->id == $request['id']) {
				return print_r(json_encode($key));		
			}
		}
		
	}	
	
	public function index($params){
		try{
			$db = Db::connect();
			$sttm = $db->query("SELECT * FROM users");
			$data =  $sttm->fetchAll(\PDO::FETCH_CLASS);
			
			print_r(json_encode($data));
			return http_response_code(200);

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}
		
	}

	public function show($request){
		try{
			$db = Db::connect();
			$sttm = $db->prepare("SELECT * FROM users where id = ?");
			$sttm->bindValue(1, $request['id']);
			$sttm->execute();

			$data =  $sttm->fetchAll(\PDO::FETCH_CLASS);
			
			$db = null;

			print_r(json_encode($data));
			return http_response_code(200);

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}		
	}

	public function edit($request){
		try{
			$db = Db::connect();
			$sttm = $db->prepare("SELECT * FROM users where id = ?");
			$sttm->bindValue(1, $request['id']);
			$sttm->execute();

			$data =  $sttm->fetchAll(\PDO::FETCH_CLASS);
			
			$db = null;

			print_r(json_encode($data));
			return http_response_code(200);

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}
	}

	#Models
	public function create($request){
		try{
			$db = Db::connect();
			$sttm = $db->prepare("INSERT INTO users (name, email, password) values (?,?,?)");
			$sttm->bindValue(1, $request['name']);
			$sttm->bindValue(2, $request['email']);
			$sttm->bindValue(3, $request['password']);
			$sttm->execute();
			$data =  $sttm->fetchAll(\PDO::FETCH_CLASS);
			
			$db = null;

			print_r(json_encode($data));
			return http_response_code(200);

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}
	}

	public function update($request){
		try{
			$db = Db::connect();
			$sttm = $db->prepare("UPDATE users SET name = ?, email = ?, password = ? where id = ?");
			$sttm->bindValue(1, $request['name']);
			$sttm->bindValue(2, $request['email']);
			$sttm->bindValue(3, $request['password']);
			$sttm->bindValue(4, $request['id']);
			$sttm->execute();
			
			$db = null;

			$data = $request;
			print_r(json_encode($data));

			return http_response_code(202);

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}	
	}

	public function delete($request){
		try{
			$db = Db::connect();
			$sttm = $db->prepare("DELETE FROM users where id = ?");
			$sttm->bindValue(1, $request['id']);
			$sttm->execute();
			
			$db = null;

			$data = ['msg'=>'Deletado com sucesso'];

			print_r(json_encode($data));

			return http_response_code(410);

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}
	}

	public function login2($request){
		try{
			$db = Db::connect();
			$sttm = $db->prepare("SELECT * FROM users where email = ? and password = ?");
			$sttm->bindValue(1, $request['email']);
			$sttm->bindValue(1, $request['password']);
			$sttm->execute();

			$data =  $sttm->fetchAll(\PDO::FETCH_CLASS);
			$db = null;

			if(count($data) === 1){
				JWT::setJWT($request);
				return http_response_code(200);
			}else{
				$msg = ['msg'=>'Email ou senha inválido(s)'];
				print_r(json_encode($msg));
				return http_response_code(405);
			}

		}catch(\PDOException $e){
			print_r(json_encode($e));
			return http_response_code(500);
		}		

		
	}

	public function access(){
		JWT::verJWT();
	}

}