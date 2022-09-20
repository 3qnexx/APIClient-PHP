<?php
namespace nexxomnia\apicalls\parameters;

use nexxomnia\internals\parameters;

class sessionparameters extends parameters{

	public function __construct(){
		parent::__construct();
	}

	public function setLanguage(string $language):void{
		if(strlen($language)==2){
			$this->set('explicitLanguage',strtolower($language));
		}
	}

	public function setGateway(string $gateway):void{
		$this->set('gateway',$gateway);
	}

	public function setDeliveryPartner(int $partner):void{
		$this->set('deliveryPartner',$partner);
	}

	public function setAffiliatePartner(int $partner):void{
		$this->set('affiliatePartner',$partner);
	}

	public function setAffiliatePartnerCode(String $code):void{
		$this->set('nxp_afpc',$code);
	}

	public function setDeviceName(string $name):void{
		$this->set('deviceName',$name);
	}

	public function setPortal(string $portal):void{
		$this->set('portal',$portal);
	}

	public function setLinkOrigin(string $origin):void{
		$this->set('linkOrigin',$origin);
	}
}