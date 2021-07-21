<?php
namespace nexxOMNIA;

use nexxOMNIA\enums\defaults;
use nexxOMNIA\internal\parameters;
use nexxOMNIA\internal\modifiers;
use nexxOMNIA\result\result;

class apiclient{

	protected int $domain=0;
	protected string $secret="";
	protected string $session="";

	protected int $timeout=10;
	protected bool $useHTTPS=TRUE;
	protected string $customHost="";
	protected string $consumer="apiclient";

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

	private function log(string $msg):void{
		echo $msg."<br />";
		ob_flush();
		flush();
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
			$this->log($url."?".http_build_query($callparams));
			$request=$client->request($verb,$url,$clientconfig);
		}catch(\Exception $e){}
		return(new result($request));
	}

	public function startCall(string $type=""):internal\apicall{
		switch($type){
			case defaults::API_KIND_MANAGE:
				$handle=new apicall\manage();
			break;
			case defaults::API_KIND_DOMAIN:
				$handle=new apicall\domain();
			break;
			case defaults::API_KIND_SESSION:
				$handle=new apicall\session();
			break;
			case defaults::API_KIND_STATISTICS:
				$handle=new apicall\statistics();
			break;
			default:
				$handle=new apicall\media();
			break;
		}
		return($handle);
	}

	public function call(internal\apicall $call,bool $fetchAllPossibleResults=FALSE):result{
		if($fetchAllPossibleResults){
			if($call instanceof apicall\media){
				$call->getParameters()->setLimit(defaults::MAX_RESULT_LIMIT);
			}else{
				$fetchAllPossibleResults=FALSE;
			}
		}
		$result=$this->callAPI($call->getVerb(),$call->getPath(),$call->getParameters(),$call->getModifiers());
		if($result->wasSuccessfull()){
			if(($fetchAllPossibleResults)&&($result->getPaging()->hasMoreResults())){
				$callAgain=TRUE;
				$start=defaults::MAX_RESULT_LIMIT;
				while($callAgain){
					$call->getParameters()->setStart($start);
					$adds=$this->callAPI($call->getVerb(),$call->getPath(),$call->getParameters(),$call->getModifiers());
					if($adds->wasSuccessfull()){
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