<?php
namespace nexxomnia\internals;

use nexxomnia\enums\defaults;
use nexxomnia\enums\distanceunits;
use nexxomnia\enums\gateways;
use nexxomnia\enums\imageformats;
use nexxomnia\enums\temperatureunits;

class parameters{

	protected array $params=[];

	public function __construct(){
	}

	public function set($key,$val):void{
		if(!empty($key)){
			$this->params[$key]=$val;
		}
	}

	public function setByArray(array $data):void{
		foreach($data as $key=>$val){
			$this->set($key,$val);
		}
	}

	public function disableCaching():void{
		$this->set('noc',1);
	}

	public function enableExtendedImageGeometry():void{
		$this->set('extendCoverGeometry',1);
	}

	public function setCallingContext(string $context):void{
		$this->set('cfo',$context);
	}

	public function restrictToCurrentDomain():void{
		$this->set('restrictToCurrentDomain',1);
	}

	public function restrictToChildDomain(int $dom):void{
		$this->set('restrictToChildDomain',$dom);
	}

	public function setImageFormat(string $format):void{
		if(in_array($format,imageformats::getAllTypes())){
			$this->set('imageFormat',$format);
		}else{
			throw new \Exception("ImageFormat string is unknown");
		}
	}

	public function setRichTextFormat(string $format):void{
		$this->set('richTextFormat',$format);
	}

	public function setDateFormat(string $format):void{
		$this->set('dateFormat',$format);
	}

	public function setDateFormatTimezone(string $timezone):void{
		$this->set('dateFormatTimezone',$timezone);
	}

	public function setDistanceUnit(string $unit):void{
		if(in_array($unit,distanceunits::getAllTypes())){
			$this->set('distanceUnit',$unit);
		}
	}

	public function setTemperatureUnit(string $unit):void{
		if(in_array($unit,temperatureunits::getAllTypes())){
			$this->set('temperatureUnit',$unit);
		}
	}

	public function setGateway(string $gateway):void{
		if(in_array($gateway,gateways::getMediaGateways())){
			$this->set('forceGateway',$gateway);
		}else{
			throw new \Exception("Gateway string is unknown");
		}
	}

	public function setLanguage(string $language):void{
		if(strlen($language)==2){
			$this->set('forceLanguage',strtolower($language));
		}
	}

	public function setAdditionalReturnFields($fields):void{
		if(is_array($fields)){
			$fields=implode(",",$fields);
		}
		$this->set('additionalfields',$fields);
	}

	public function setOrder(string $order,string $direction="DESC"):void{
		$this->set('order',$order);
		if(in_array(strtoupper($direction),['ASC','DESC'])){
			$this->set('orderdir',strtoupper($direction));
		}
	}

	public function setLimit(int $limit,int $start=0):void{
		$this->set('limit',min($limit,defaults::MAX_RESULT_LIMIT));
		if(!empty($start)){
			$this->setStart($start);
		}
	}

	public function setChildLimit(int $limit):void{
		$this->set('childlimit',min($limit,defaults::MAX_RESULT_LIMIT));
	}

	public function setStart(int $start):void{
		$this->set('start',$start);
	}

	public function get():array{
		return($this->params);
	}
}