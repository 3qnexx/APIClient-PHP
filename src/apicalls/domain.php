<?php

namespace nexxomnia\apicalls;

use nexxomnia\apicalls\parameters\domainparameters;

class domain extends \nexxomnia\internals\apicall{

	public function __construct(){
		parent::__construct();
		$this->modifiers=NULL;
		$this->parameters=new domainparameters();
		$this->path="domain/";
	}

	public function getParameters():?domainparameters{
		return($this->parameters);
	}

	public function publicInfo():void{
		$this->path.="publicinfo";
	}

	public function instantConfiguration(string $token):void{
		$this->path.="instantconfiguration/".$token;
	}

	public function offlineConfiguration():void{
		$this->path.="offlineconfiguration";
	}

	public function apps():void{
		$this->path.="apps";
	}

	public function accounts():void{
		$this->path.="accounts";
	}

	public function liveLinks():void{
		$this->path.="livelinks";
	}

	public function channels():void{
		$this->path.="channels";
	}

	public function formats():void{
		$this->path.="formats";
	}

	public function videoCategories():void{
		$this->path.="videocategories";
	}

	public function audioCategories():void{
		$this->path.="audiocategories";
	}

	public function imageCategories():void{
		$this->path.="imagecategories";
	}

	public function fileCategories():void{
		$this->path.="filecategories";
	}

	public function articleCategories():void{
		$this->path.="articlecategories";
	}

	public function eventCategories():void{
		$this->path.="eventcategories";
	}

	public function placeCategories():void{
		$this->path.="placecategories";
	}

	public function tags():void{
		$this->path.="tags";
	}

	public function autoUpdateFeeds(bool $addStreamDetails=FALSE):void{
		$this->path.="autoupdatefeeds";
		if($addStreamDetails){
			$this->getParameters()->set("addStreamDetails",1);
		}
	}

	public function widgets(bool $addStreamDetails=FALSE, bool $addEmbedDetails=TRUE):void{
		$this->path.="widgets";
		if($addStreamDetails){
			$this->getParameters()->set("addStreamDetails",1);
		}
		if($addEmbedDetails){
			$this->getParameters()->set("addEmbedDetails",1);
		}
	}

	public function previewLinks():void{
		$this->path.="previewlinks";
	}

	public function broadcastLinks():void{
		$this->path.="previewlinks";
	}

	public function uploadLinks():void{
		$this->path.="previewlinks";
	}

	public function prices():void{
		$this->path.="prices";
	}

	public function affiliatePartners():void{
		$this->path.="affiliatepartners";
	}

	public function adProviders():void{
		$this->path.="adproviders";
	}

	public function payProviders():void{
		$this->path.="payproviders";
	}

	public function avsProviders():void{
		$this->path.="avsproviders";
	}

	public function licensors():void{
		$this->path.="licensors";
	}

	public function deliveryPartners():void{
		$this->path.="deliverypartners";
	}

	public function payModel():void{
		$this->path.="paymodel";
	}

	public function systemUsers():void{
		$this->path.="systemusers";
	}

	public function networkDomains(bool $addChannels=FALSE,bool $addFormats=FALSE,bool $addVideoCategories=FALSE,bool $addAudioCategories=FALSE,bool $addImageCategories=FALSE,bool $addFileCategories=FALSE,bool $addArticleCategories=FALSE,bool $addEventCategories=FALSE,bool $addPlaceCategories=FALSE,bool $addAccounts=FALSE,bool $addLiveLinks=FALSE,bool $addAutoUpdateFeeds=FALSE,bool $addTags=FALSE,bool $addCustomAttributes=FALSE):void{
		$this->path.="networkdomains";
		if($addChannels){
			$this->getParameters()->set("addChannels",1);
		}
		if($addFormats){
			$this->getParameters()->set("addFormats",1);
		}
		if($addVideoCategories){
			$this->getParameters()->set("addVideoCategories",1);
		}
		if($addAudioCategories){
			$this->getParameters()->set("addAudioCategories",1);
		}
		if($addImageCategories){
			$this->getParameters()->set("addImageCategories",1);
		}
		if($addFileCategories){
			$this->getParameters()->set("addFileCategories",1);
		}
		if($addArticleCategories){
			$this->getParameters()->set("addArticleCategories",1);
		}
		if($addEventCategories){
			$this->getParameters()->set("addEventCategories",1);
		}
		if($addPlaceCategories){
			$this->getParameters()->set("addPlaceCategories",1);
		}
		if($addAccounts){
			$this->getParameters()->set("addAccounts",1);
		}
		if($addLiveLinks){
			$this->getParameters()->set("addLiveLinks",1);
		}
		if($addAutoUpdateFeeds){
			$this->getParameters()->set("addAutoUpdateFeeds",1);
		}
		if($addTags){
			$this->getParameters()->set("addTags",1);
		}
		if($addCustomAttributes){
			$this->getParameters()->set("addCustomAttributes",1);
		}
	}
}