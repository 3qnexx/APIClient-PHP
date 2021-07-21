<?php

namespace nexxOMNIA\apicall\parameters;

use nexxOMNIA\internal\parameters;

class sessionparameters extends parameters{

	public function __construct(){
		parent::__construct();
	}

	public function setLanguage(string $language):void{
		if(strlen($language)==2){
			$this->set('explicitlanguage',strtolower($language));
		}
	}

	public function setGateway(string $gateway):void{
		$this->set('gateway',$gateway);
	}

	public function setDeliveryPartner(int $partner):void{
		$this->set('deliveryPartner',$partner);
	}

	public function setAffiliatePartnerCode(string $code):void{
		$this->set('code',$code);
	}

	public function setDeviceName(string $name):void{
		$this->set('deviceName',$name);
	}

	public function setPortal(string $portal):void{
		$this->set('portal',$portal);
	}

	public function setLinkOrigin(string $origin):void{
		$this->set('linkorigin',$origin);
	}
}