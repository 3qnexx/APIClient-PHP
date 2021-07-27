<?php
namespace nexxomnia;

use nexxomnia\apicall\statistics;
use nexxomnia\apicall\media;
use nexxomnia\enums\defaults;
use nexxomnia\internal\parameters;
use nexxomnia\internal\modifiers;
use nexxomnia\result\result;

class apiclient{

	protected int $domain=0;
	protected string $secret="";
	protected string $session="";

	protected ?\Psr\Log\LoggerInterface $logger=NULL;

	protected int $timeout=10;
	protected bool $useHTTPS=TRUE;
	protected string $customHost="";
	protected string $consumer="apiclient-php";

	public function __construct(int $domain=0,string $secret="",string $session=""){
		$this->configure($domain,$secret,$session);
	}

	public function configure(int $domain=0,string $secret="",string $session=""):void{
		if(!empty($domain)){
			$this->setDomain($domain);
		}
		if(!empty($secret)){
			$this->setSecret($secret);
		}
		if(!empty($session)){
			$this->setSession($session);
		}
	}

	public function setDomain(int $domain):void{
		$this->domain=$domain;
	}

	public function setSecret(string $secret):void{
		$this->secret=$secret;
	}

	public function setSession(string $session):void{
		$this->session=$session;
	}

	public function setCustomHost(string $hostPath):void{
		$this->customHost=$hostPath;
	}

	public function setCustomConsumer(string $consumer):void{
		$this->consumer=$consumer;
	}

	public function setLogger(\Psr\Log\LoggerInterface $logger):void{
		$this->logger=$logger;
	}

	public function setTimeout(int $timeout):void{
		$this->timeout=$timeout;
	}

	public function disableHTTPS():void{
		$this->useHTTPS=FALSE;
	}

	private function buildToken(string $path):string{
		return(md5(explode("/",$path)[1].$this->domain.$this->secret));
	}

	private function buildHost():string{
		$host=defaults::API_URL;
		if(!empty($this->customHost)){
			$host=str_replace("api.","api".$this->customHost.".",$host);
		}
		return("http".($this->useHTTPS?"s":"")."://".$host."/v".defaults::API_VERSION."/".$this->domain."/");
	}

	public function log(string $message,string $level=\Psr\Log\LogLevel::INFO,array $context=[]):void{
		if($this->logger){
			$this->logger->log($level,$message,$context);
		}
	}

	private function callAPI(string $verb,string $endpoint,?parameters $params=NULL,?modifiers $modifiers=NULL):result{
		$callparams=[];
		$clientconfig=[
			'timeout'=>$this->timeout,
			'http_errors'=>FALSE
		];
		$client=new \GuzzleHttp\Client([
			'headers'=>[
				'X-Request-CID'=>$this->session,
				'X-Request-Consumer'=>$this->consumer,
				'X-Request-Client'=>'apiclient-php',
				'X-Request-Client-Version'=>defaults::CLIENT_VERSION,
				'X-Request-Token'=>$this->buildToken($endpoint)
			]
		]);
		if($params){
			$callparams=array_merge($callparams,$params->get());
		}
		if($modifiers){
			$callparams=array_merge($callparams,$modifiers->get());
		}
		if(!empty($callparams)){
			if($verb==defaults::VERB_GET){
				$clientconfig['query']=$callparams;
			}else{
				$clientconfig['form_params']=$callparams;
			}
		}
		try{
			$url=$this->buildHost().$endpoint;
			$this->log("CALLING URL: ".$url."?".http_build_query($callparams));
			$request=$client->request($verb,$url,$clientconfig);
		}catch(\Exception $e){
			$this->log("APICLIENT EXCEPTION: ".$e->getMessage(),\Psr\Log\LogLevel::ERROR);
		}
		return(new result($request,$endpoint,$this->logger));
	}

	public function call(internal\apicall $call,bool $fetchAllPossibleResults=FALSE):result{
		if($fetchAllPossibleResults){
			if($call instanceof media){
				$this->log("PREPARING FOR CATCHING ALL RESULTS");
				$call->getParameters()->setLimit(defaults::MAX_RESULT_LIMIT);
			}else{
				$fetchAllPossibleResults=FALSE;
			}
		}
		if($call instanceof statistics){
			if($this->timeout<30){
				$this->log("RAISING TIMEOUT TO 30 SECONDS AUTOMATICALLY");
				$this->timeout=30;
			}
		}
		$result=$this->callAPI($call->getVerb(),$call->getPath(),$call->getParameters(),$call->getModifiers());
		if($result->isSuccess()){
			if(($fetchAllPossibleResults)&&($result->getPaging()->hasMoreResults())){
				$callAgain=TRUE;
				$start=defaults::MAX_RESULT_LIMIT;
				while($callAgain){
					$call->getParameters()->setStart($start);
					$adds=$this->callAPI($call->getVerb(),$call->getPath(),$call->getParameters(),$call->getModifiers());
					if($adds->isSuccess()){
						$result->addResults($adds->getResult());
						if($result->getPaging()->hasMoreResults()){
							$start+=defaults::MAX_RESULT_LIMIT;
						}else{
							$callAgain=FALSE;
						}
					}
				}
			}
		}
		return($result);
	}

}