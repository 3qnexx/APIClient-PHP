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

	public function setDateFormat(string $format, bool $applyLocalTimezone=TRUE):void{
		$this->set('dateFormat',$format);
		if($applyLocalTimezone){
			$this->setDateFormatTimezone(date_default_timezone_get());
		}
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

	public function setAdditionalReturnFields($fields='all'):void{
		if(is_array($fields)){
			$fields=implode(",",$fields);
		}
		$this->set('additionalFields',$fields);
	}

	public function setOrder(string $order,string $direction="DESC"):void{
		$this->set('order',$order);
		if(in_array(strtoupper($direction),['ASC','DESC'])){
			$this->set('orderDir',strtoupper($direction));
		}
	}

	/**
	 * @throws \Exception on invalid Limit
	 */
	public function setLimit(int $limit,int $start=0):void{
		if($limit>defaults::MAX_RESULT_LIMIT){
			throw new \Exception("max Limit is ".defaults::MAX_RESULT_LIMIT);
		}else{
			$this->set('limit',abs($limit));
			if(!empty($start)){
				$this->setStart(abs($start));
			}
		}
	}

	/**
	 * @throws \Exception on invalid Limit
	 */
	public function setChildLimit(int $limit):void{
		if($limit>defaults::MAX_RESULT_LIMIT){
			throw new \Exception("max Child Limit is ".defaults::MAX_RESULT_LIMIT);
		}else{
			$this->set('childLimit',abs($limit));
		}
	}

	public function setStart(int $start):void{
		$this->set('start',$start);
	}

	public function get():array{
		return($this->params);
	}
}