<?php
namespace nexxomnia\apicalls\parameters;

use nexxomnia\enums\dimensioncodes;
use nexxomnia\internals\parameters;

class mediaparameters extends parameters{

	public function __construct(){
		parent::__construct();
	}

	public function restrictToCreatedAfter(int $time):void{
		$this->params['createdAfter']=$time;
	}

	public function restrictToModifiedAfter(int $time):void{
		$this->params['modifiedAfter']=$time;
	}

	public function restrictToPublishedAfter(int $time):void{
		$this->params['publishedAfter']=$time;
	}

	public function restrictToAvailableInCountry(string $country):void{
		if(strlen($country)==2){
			$this->params['country']=strtolower($country);
		}
	}

	public function restrictToSessionLanguage(bool $restrict):void{
		$this->params['onylForSessionLanguage']=($restrict?1:0);
	}

	public function restrictToAudioLanguage(string $lang):void{
		if(strlen($lang)==2){
			$this->params['country']=strtoupper($lang);
		}
	}

	/**
	 * @throws \Exception on invalid Dimension
	 */
	public function restrictToDimension(string $dim,int $height=0):void{
		if(in_array($dim,dimensioncodes::getAllTypes())){
			$this->params['dimension']=$dim;
		}else{
			throw new \Exception("Dimension string is unknown");
		}
		if(!empty($height)){
			$this->params['dimension']=$height;
		}
	}

	public function restrictToPlanned():void{
		$this->params['onlyPlanned']=1;
	}

	public function restrictToInactive():void{
		$this->params['onlyInactive']=1;
	}

	public function restrictToAgeGroup(int $minAge=-1,int $maxAge=-1):void{
		if($minAge>0){
			$this->params['minAge']=$minAge;
		}
		if($maxAge>0){
			$this->params['maxAge']=$maxAge;
		}
	}

	public function restrictToNoExplicit():void{
		$this->params['noExplicit']=1;
	}

	public function restrictToNoContentModerationHints():void{
		$this->params['noContentModerationHints']=1;
	}

	public function restrictToUnsecured():void{
		$this->params['onlyUnsecured']=1;
	}

	public function restrictToPanorama():void{
		$this->params['onlyPanorama']=1;
	}

	public function restrictToAnimations():void{
		$this->params['onlyAnimations']=1;
	}

	public function restrictToBlackAndWhite():void{
		$this->params['onlyBW']=1;
	}

	public function restrictToSurroundSound():void{
		$this->params['onlyWithSurroundSound']=1;
	}

	public function restrictToDownloadable():void{
		$this->params['onlyDownloadable']=1;
	}

	public function restrictToDuration(int $minDur=-1,int $maxDur=-1){
		if($minDur>0){
			$this->params['minDuration']=$minDur;
		}
		if($maxDur>0){
			$this->params['maxDuration']=$maxDur;
		}
	}

	public function excludeItems(array $list):void{
		if(!empty($list)){
			$this->params['excludeItems']=implode(",",$list);
		}
	}

	public function setSelectedStreamtypes(array $list):void{
		if(!empty($list)){
			$this->params['selectedStreamtypes']=implode(",",$list);
		}
	}

	public function setInsightFields(array $list):void{
		if(!empty($list)){
			$this->params['insightFields']=implode(",",$list);
		}
	}

	public function setLinkedMediaFormatToID():void{
		$this->params['reduceLinkedMediaToID']=1;
	}

	public function setCaptionFormat(string $format):void{
		$this->params['captionFormat']=$format;
	}

	public function setChannel(int $channel,bool $respectChannelHierarchy=FALSE):void{
		$this->params['channel']=$channel;
		if($respectChannelHierarchy){
			$this->params['respectChannelHierarchy']=1;
		}
	}

	public function setFormat(int $format):void{
		$this->params['format']=$format;
	}

	public function setGenre(int $genre):void{
		$this->params['genre']=$genre;
	}

	public function setType(string $type):void{
		$this->params['type']=$type;
	}

	public function setPurpose(string $purpose):void{
		$this->params['purpose']=$purpose;
	}

	//only valid for POST
	public function setPlatform(string $platform):void{
		$this->params['platform']=$platform;
	}

	//only valid for POST
	public function setAccount(int $account):void{
		$this->params['account']=$account;
	}

	//only valid for FILE
	public function setFileType(string $type):void{
		$this->params['filetype']=$type;
	}

	public function includeUGC(bool $include,bool $onlyUGC=FALSE,$onlyForUser=0):void{
		if(!empty($onlyForUser)){
			$onlyUGC=TRUE;
			$this->params['forUserID']=1;
		}
		if($onlyUGC){
			$include=1;
			$this->params['onlyUGC']=1;
		}
		$this->params['includeUGC']=($include?1:0);
	}

	public function includeRemote(bool $include,bool $onlyRemote=FALSE):void{
		if($onlyRemote){
			$include=1;
			$this->params['onlyRemote']=1;
		}
		$this->params['includeRemote']=($include?1:0);
	}

	public function includePay(bool $onlyFree=FALSE,bool $onlyPayed=FALSE, bool $onlyPremium=FALSE, bool $onlyStandard=FALSE){
		if($onlyFree){
			$this->params['onlyFree']=1;
		}else if($onlyPayed){
			$this->params['onlyPay']=1;
			if($onlyPremium){
				$this->params['onlyPremiumPay']=1;
			}else if($onlyStandard){
				$this->params['onlyStandardPay']=1;
			}
		}
	}

	public function includeNotListables(bool $include):void{
		$this->params['includeNotListables']=($include?1:0);
	}

	public function forceResults(bool $force):void{
		$this->params['forceResults']=($force?1:0);
	}

	public function includeTrailers(bool $include,bool $onlyTrailers=FALSE):void{
		if($onlyTrailers){
			$include=1;
			$this->params['onlyTrailers']=1;
		}
		$this->params['includeTrailers']=($include?1:0);
	}

	public function includeBonus(bool $include,bool $onlyBonus=FALSE):void{
		if($onlyBonus){
			$include=1;
			$this->params['onlyBonus']=1;
		}
		$this->params['includeBonus']=($include?1:0);
	}

	public function includePremieres(bool $include,bool $onlyPremieres=FALSE):void{
		if($onlyPremieres){
			$include=1;
			$this->params['onlyPremieres']=1;
		}
		$this->params['includePremieres']=($include?1:0);
	}

	public function includeEpisodes(bool $include,bool $onlyEpisodes=FALSE):void{
		if($onlyEpisodes){
			$include=1;
			$this->params['onlyEpisodes']=1;
		}
		$this->params['includeEpisodes']=($include?1:0);
	}

	public function includeStories(bool $include,bool $onlyStories=FALSE):void{
		if($onlyStories){
			$include=1;
			$this->params['onlyStories']=1;
		}
		$this->params['includeStories']=($include?1:0);
	}

	public function includeStoryParts(bool $include,bool $onlyStoryParts=FALSE):void{
		if($onlyStoryParts){
			$include=1;
			$this->params['onlyStoryParts']=1;
		}
		$this->params['includeStoryParts']=($include?1:0);
	}

	public function includeSeasons(bool $include,bool $onlySeasons=FALSE):void{
		if($onlySeasons){
			$include=1;
			$this->params['onlySeasons']=1;
		}
		$this->params['includeSeasons']=($include?1:0);
	}
}