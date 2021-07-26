<?php

namespace nexxomnia\apicall;

use nexxomnia\enums\defaults;
use nexxomnia\apicall\parameters\sessionparameters;
use nexxomnia\apicall\modifiers\sessionmodifiers;

class session extends \nexxomnia\internal\apicall{

	public function __construct(){
		parent::__construct();
		$this->modifiers=new sessionmodifiers();
		$this->parameters=new sessionparameters();
		$this->path="session/";
	}

	public function getParameters():?sessionparameters{
		return($this->parameters);
	}

	public function getModifiers():?sessionmodifiers{
		return($this->modifiers);
	}

	public function init(string $deviceHash,string $userHash="",int $previousSession=0,bool $forcePersistantSession=FALSE,string $externalUserReference=""):void{
		$this->path.="init";
		$this->verb=defaults::VERB_POST;
		$this->getParameters()->set('nxp_devh',$deviceHash);
		if(!empty($userHash)){
			$this->getParameters()->set('nxp_userh',$userHash);
		}else if(!empty($externalUserReference)){
			$this->getParameters()->set('externalUserReference',$externalUserReference);
		}
		if(!empty($previousSession)){
			$this->getParameters()->set('precid',$previousSession);
		}
		if($forcePersistantSession){
			$this->getParameters()->set('forcePersistantSession',1);
		}
	}

	public function loginWithUser(string $username,string $password):void{
		$this->path.="login";
		$this->verb=defaults::VERB_POST;
		$this->getParameters()->set("provider","manual");
		$this->getParameters()->set("username",$username);
		$this->getParameters()->set("password",$password);
	}

	public function loginWithToken(string $provider,string $token):void{
		$this->path.="login";
		$this->verb=defaults::VERB_POST;
		$this->getParameters()->set("provider",$provider);
		$this->getParameters()->set("token",$token);
	}

	public function logout():void{
		$this->path.="logout";
		$this->verb=defaults::VERB_POST;
	}
}