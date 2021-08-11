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
use nexxomnia\enums\viewcount;
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

	/**
	 * @throws \Exception on invalid Gateway
	 */
	public function setGateway(string $gateway):void{
		if(in_array($gateway,gateways::getAllTypes())){
			$this->set('gateway',$gateway);
		}else{
			throw new \Exception("Gateway string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid Device
	 */
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

	/**
	 * @throws \Exception on invalid Reference
	 */
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

	/**
	 * @throws \Exception on invalid pageIntegration
	 */
	public function restrictToPageIntegration(string $pageIntegration):void{
		if(in_array($pageIntegration,pageintegrationtypes::getAllTypes())){
			$this->set('pageIntegration',$pageIntegration);
		}else{
			throw new \Exception("pageIntegration string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid Browser
	 */
	public function restrictToBrowser(string $browser):void{
		if(in_array($browser,browsers::getAllTypes())){
			$this->set('browser',$browser);
		}else{
			throw new \Exception("browser string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid OS
	 */
	public function restrictToOS(string $os):void{
		if(in_array($os,operatingsystems::getAllTypes())){
			$this->set('browser',$os);
		}else{
			throw new \Exception("OS string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid Country Code
	 */
	public function restrictToCountry(string $code):void{
		if(strlen($code)==2){
			$this->set('countryCode',strtoupper($code));
		}else{
			throw new \Exception("Country Code must be given in 2-Letter-Format");
		}
	}

	public function restrictToRegion(int $region):void{
		$this->set('regionCode',$region);
	}

	public function restrictToManufacturer(string $manufacturer):void{
		$this->set('manufacturer',$manufacturer);
	}

	public function restrictToDeliveryDomain(int $domain):void{
		$this->set('deliveryDomain',$domain);
	}

	/**
	 * @throws \Exception on invalid environment String
	 */
	public function restrictToConsentEnvironment(string $environment):void{
		if(in_array($environment,consentenvironments::getAllTypes())){
			$this->set('consentEnvironment',$environment);
		}else{
			throw new \Exception("Environment string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid playbackMode
	 */
	public function restrictToPlaybackMode(string $playbackMode):void{
		if(in_array($playbackMode,playbackmodes::getAllTypes())){
			$this->set('playbackMode',$playbackMode);
		}else{
			throw new \Exception("PlaybackMode string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid Data Mode
	 */
	public function restrictToDataMode(string $mode):void{
		if(in_array($mode,datamodes::getAllTypes())){
			$this->set('dataMode',$mode);
		}else{
			throw new \Exception("Data Mode string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid Origin
	 */
	public function restrictToMediaOrigin(string $origin):void{
		if(in_array($origin,mediaorigins::getAllTypes())){
			$this->set('mediaOrigin',$origin);
		}else{
			throw new \Exception("mediaOrigin string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid condition
	 */
	public function restrictToStartCondition(string $condition):void{
		if(in_array($condition,startconditions::getAllTypes())){
			$this->set('startCondition',$condition);
		}else{
			throw new \Exception("startCondition string is unknown");
		}
	}

	/**
	 * @throws \Exception on invalid condition
	 */
	public function restrictToViewCount(string $viewcount):void{
		if(in_array($viewcount,viewcount::getAllTypes())){
			$this->set('viewCount',$viewcount);
		}else{
			throw new \Exception("viewCount string is unknown");
		}
	}

}