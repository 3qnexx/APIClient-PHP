<?php

namespace nexxomnia\apicalls\parameters;

use nexxomnia\enums\defaults;
use nexxomnia\enums\devicetypes;
use nexxomnia\enums\gateways;
use nexxomnia\enums\itemreferences;
use nexxomnia\enums\browsers;
use nexxomnia\enums\operatingsystems;
use nexxomnia\enums\consentenvironments;
use nexxomnia\enums\pageintegrationtypes;
use nexxomnia\enums\playbackmodes;
use nexxomnia\enums\datamodes;
use nexxomnia\enums\mediaorigins;
use nexxomnia\enums\startconditions;
use nexxomnia\internals\parameters;

class statisticparameters extends parameters{

	public function __construct(){
		parent::__construct();
	}

	public function includeNetworkDomains(bool $include=FALSE):void{
		$this->set("includeNetworkDomains",($include?1:0));
	}

	public function setTimeZone(string $zone):void{
		$this->set("timezone",$zone);
	}

	public function setGateway(string $gateway):void{
		if(in_array($gateway,gateways::getAllGateways())){
			$this->set('gateway',$gateway);
		}else{
			throw new \Exception("Gateway string is unknown");
		}
	}

	public function setDevice(string $device):void{
		if(in_array($device,devicetypes::getAllTypes())){
			$this->set('device',$device);
		}else{
			throw new \Exception("Device string is unknown");
		}
	}

	public function setChannel(int $channel):void{
		$this->set("channel",$channel);
	}

	public function setFormat(int $format):void{
		$this->set("format",$format);
	}

	public function setStreamtype(string $streamtype):void{
		$this->set("streamtype",$streamtype);
	}

	public function restrictToItem(int $item):void{
		$this->set("item",$item);
	}

	public function setItemReference(string $reference):void{
		if(in_array($reference,itemreferences::getAllTypes())){
			$this->set('itemReference',$reference);
		}else{
			throw new \Exception("itemReference string is unknown");
		}
	}

	public function setFrom(string $from):void{
		$this->set("from",$from);
	}

	public function setTo(string $to):void{
		$this->set("to",$to);
	}

	public function setLimit(int $limit,int $start=0):void{
		$this->set('limit',min($limit,defaults::MAX_RESULT_LIMIT_STATISTICS));
	}

	public function restrictToDeliveryPartner(int $parter):void{
		$this->set('deliveryPartner',$parter);
	}

	public function restrictToAffiliatePartner(int $parter):void{
		$this->set('affiliatePartner',$parter);
	}

	public function restrictToLicensor(int $licensor):void{
		$this->set('licensor',$licensor);
	}

	public function restrictToPageIntegration(string $reference):void{
		if(in_array($reference,pageintegrationtypes::getAllTypes())){
			$this->set('pageIntegration',$reference);
		}else{
			throw new \Exception("pageIntegration string is unknown");
		}
	}

	public function restrictToBrowser(string $browser):void{
		if(in_array($browser,browsers::getAllTypes())){
			$this->set('browser',$browser);
		}else{
			throw new \Exception("browser string is unknown");
		}
	}

	public function restrictToOS(string $os):void{
		if(in_array($os,operatingsystems::getAllTypes())){
			$this->set('browser',$os);
		}else{
			throw new \Exception("OS string is unknown");
		}
	}

	public function restrictToCountry(string $code):void{
		if(strlen($code)==2){
			$this->set('countrycode',strtoupper($code));
		}else{
			throw new \Exception("Country Code must be given in 2-Letter-Format");
		}
	}

	public function restrictToRegion(int $region):void{
		$this->set('regioncode',$region);
	}

	public function restrictToManufacturer(string $manufacturer):void{
		$this->set('manufacturer',$manufacturer);
	}

	public function restrictToDeliveryDomain(int $domain):void{
		$this->set('deliveryDomain',$domain);
	}

	public function restrictToConsentEnvironment(string $environment):void{
		if(in_array($environment,consentenvironments::getAllTypes())){
			$this->set('consentEnvironment',$environment);
		}else{
			throw new \Exception("Environment string is unknown");
		}
	}

	public function restrictToPlaybackMode(string $environment):void{
		if(in_array($environment,playbackmodes::getAllTypes())){
			$this->set('playbackMode',$environment);
		}else{
			throw new \Exception("PlaybackMode string is unknown");
		}
	}

	public function restrictToDataMode(string $mode):void{
		if(in_array($mode,datamodes::getAllTypes())){
			$this->set('dataMode',$mode);
		}else{
			throw new \Exception("dataMode string is unknown");
		}
	}

	public function restrictToMediaOrigin(string $origin):void{
		if(in_array($origin,mediaorigins::getAllTypes())){
			$this->set('mediaOrigin',$origin);
		}else{
			throw new \Exception("mediaOrigin string is unknown");
		}
	}

	public function restrictToStartCondition(string $condition):void{
		if(in_array($condition,startconditions::getAllTypes())){
			$this->set('startCondition',$condition);
		}else{
			throw new \Exception("startCondition string is unknown");
		}
	}

}