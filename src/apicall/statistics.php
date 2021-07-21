<?php

namespace nexxOMNIA\apicall;

use nexxOMNIA\apicall\parameters\statisticparameters;
use nexxOMNIA\enums\streamtypes;
use nexxOMNIA\enums\kpis;
use nexxOMNIA\enums\registrationproviders;
use nexxOMNIA\enums\subscriptionterminationreasons;
use nexxOMNIA\enums\revenuetypes;

class statistics extends \nexxOMNIA\internal\apicall{

	public function __construct(){
		parent::__construct();
		$this->modifiers=NULL;
		$this->parameters=new statisticparameters();
		$this->path="statistics/";
	}

	private function dateIsValid(string $date):bool{
		$dt=\DateTime::createFromFormat("Y-m-d",$date);
		return (($dt!==FALSE)&&(!array_sum($dt::getLastErrors())));
	}

	private function timeframeIsValid(int $timeframe):bool{
		return(in_array($timeframe,[5, 10, 15, 30, 60, 120, 180, 240]));
	}

	public function getParameters():?statisticparameters{
		return($this->parameters);
	}

	public function setStreamtype(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->getParameters()->setStreamtype($streamtype);
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	public function setDates(string $from,string $to):void{
		if($this->dateIsValid($from)){
			$this->getParameters()->setFrom($from);
		}else{
			throw new \Exception("from must be in YYYY-MM-DD format");
		}
		if($this->dateIsValid($to)){
			$this->getParameters()->setTo($to);
		}else{
			throw new \Exception("to must be in YYYY-MM-DD format");
		}
	}

	public function displayByDay():void{
		$this->path.="displaysbyday";
	}

	public function playerStartsByDay():void{
		$this->path.="playerstartsbyday";
	}

	public function viewsByDay():void{
		$this->path.="viewsbyday";
	}

	public function viewsExternalByDay():void{
		$this->path.="viewsexternalbyday";
	}

	public function viewTimeByDay():void{
		$this->path.="viewtimebyday";
	}

	public function viewTimeAverageByDay():void{
		$this->path.="viewtimeaveragebyday";
	}

	public function viewProgressByDay():void{
		$this->path.="viewprogressbyday";
	}

	public function downloadsByDay():void{
		$this->path.="downloadsbyday";
	}

	public function clicksByDay():void{
		$this->path.="viewsbyday";
	}

	public function adRequestsByDay():void{
		$this->path.="adrequestsbyday";
	}

	public function adImpressionsByDay():void{
		$this->path.="adimpressionsbyday";
	}

	public function adClicksByDay():void{
		$this->path.="adclicksbyday";
	}

	public function loginsByDay(string $provider=""):void{
		$this->path.="loginsbyday";
		if(!empty($provider)){
			if(in_array($provider,registrationproviders::getAllTypes())){
				$this->getParameters()->set("provider",$provider);
			}else{
				throw new \Exception("Provider not supported");
			}
		}
	}

	public function registrationsByDay(string $provider=""):void{
		$this->path.="registrationsbyday";
		if(!empty($provider)){
			if(in_array($provider,registrationproviders::getAllTypes())){
				$this->getParameters()->set("provider",$provider);
			}else{
				throw new \Exception("Provider not supported");
			}
		}
	}

	public function revenueByDay(string $type=""):void{
		$this->path.="revenuebyday";
		if(!empty($type)){
			if(in_array($type,revenuetypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}else{
				throw new \Exception("Revenue Type not supported");
			}
		}
	}

	public function subscriptionsByDay(bool $onlyPremium=FALSE,bool $onlyStandard=FALSE,bool $excludeTerminated=FALSE):void{
		$this->path.="subscriptionsbyday";
		if($onlyPremium){
			$this->getParameters()->set("onlyPremium",1);
		}
		if($onlyStandard){
			$this->getParameters()->set("onlyStandard",1);
		}
		if($excludeTerminated){
			$this->getParameters()->set("excludeTerminated",1);
		}
	}

	public function subscriptionTerminationsByDay(string $reason=""):void{
		$this->path.="revenuebyday";
		if(!empty($reason)){
			if(in_array($reason,subscriptionterminationreasons::getAllTypes())){
				$this->getParameters()->set("reason",$reason);
			}else{
				throw new \Exception("Subscription Termination Reason not supported");
			}
		}
	}

	public function realtime($timeframe=30):void{
		$this->path.="realtime";
		if($this->timeframeIsValid($timeframe)){
			$this->getParameters()->set("timeframe",$timeframe);
		}else{
			throw new \Exception("Timeframe not supported");
		}
	}

	public function realtimeExternal($timeframe=30):void{
		$this->path.="realtimeexternal";
		if($this->timeframeIsValid($timeframe)){
			$this->getParameters()->set("timeframe",$timeframe);
		}else{
			throw new \Exception("Timeframe not supported");
		}
	}

	public function realtimeCharts($timeframe=30):void{
		$this->path.="realtimecharts";
		if($this->timeframeIsValid($timeframe)){
			$this->getParameters()->set("timeframe",$timeframe);
		}else{
			throw new \Exception("Timeframe not supported");
		}
	}

	public function realtimeChartsExternal($timeframe=30):void{
		$this->path.="realtimechartsexternal";
		if($this->timeframeIsValid($timeframe)){
			$this->getParameters()->set("timeframe",$timeframe);
		}else{
			throw new \Exception("Timeframe not supported");
		}
	}

	public function itemlist(string $kpi):void{
		if(in_array($kpi,kpis::getAllTypes())){
			$this->path.="itemlist";
		}else{
			throw new \Exception("KPI not supported");
		}
	}
}