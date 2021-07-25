<?php

namespace nexxOMNIA\apicall;

use nexxOMNIA\enums\ageclasses;
use nexxOMNIA\enums\contentmoderationaspects;
use nexxOMNIA\enums\defaults;
use nexxOMNIA\enums\externalplatforms;
use nexxOMNIA\enums\rejectreasons;
use nexxOMNIA\enums\streamtypes;

class manage extends \nexxOMNIA\internal\apicall{

	protected string $streamtype="";
	protected string $method="";
	protected int $item=0;

	public function __construct(){
		parent::__construct();
		$this->path="manage/";
		$this->setStreamtype(streamtypes::VIDEO);
	}

	public function getPath():string{
		$this->path.=streamtypes::getPluralizedStreamtype($this->streamtype)."/".($this->item>0?$this->item."/":"").$this->method;
		return(parent::getPath());
	}

	public function setStreamtype(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->streamtype=$streamtype;
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	public function setItem(int $item=0, string $streamtype=""):void{
		if(!empty($streamtype)){
			$this->setStreamtype($streamtype);
		}
		if($item>0){
			$this->item=$item;
		}
	}

	public function createFromURL(string $url, bool $useQueue=TRUE,?bool $autoPublish=NULL, string $refnr="",int $queueStart=0, string $asVariantFor="", int $asVariantOf=0):void{
		if(in_array($this->streamtype,streamtypes::getUploadableTypes())){
			if(substr($url,0,4)=="http"){
				$this->verb=defaults::VERB_POST;
				$this->method="fromurl";
				$this->getParameters()->set("url",$url);
				if(!empty($refnr)){
					$this->getParameters()->set("refnr",$refnr);
				}
				if($useQueue){
					$this->getParameters()->set("useQueue",1);
					if($queueStart>0){
						$this->getParameters()->set("queueStart",$queueStart);
					}
				}
				if($autoPublish!==NULL){
					$this->getParameters()->set('autoPublish',($autoPublish?1:0));
				}
				if($this->streamtype==streamtypes::VIDEO){
					if($asVariantOf>0){
						$this->getParameters()->set("asVariantOf",$asVariantOf);
					}
					if(!empty($asVariantFor)){
						if(in_array($asVariantFor,externalplatforms::getAllTypes())){
							$this->getParameters()->set("asVariantFor",$asVariantFor);
						}
					}
				}
			}else{
				throw new \Exception("URL must start with HTTP");
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getUploadableTypes()));
		}
	}

	public function createFromData(string $title,string $refnr="",string $codename="",?bool $autoPublish=NULL,string $personGender="",string $personType=""):void{
		if(!in_array($this->streamtype,streamtypes::getUploadableTypes())){
			if((!empty($codename))||(!in_array($this->streamtype,streamtypes::getContainerTypes()))){
				$this->verb=defaults::VERB_POST;
				if(in_array($this->streamtype,[streamtypes::PERSON,streamtypes::GROUP])){
					$this->getParameters()->set('artistname',$title);
					if(!empty($personGender)){
						if(in_array($personGender,['m','f','n'])){
							$this->getParameters()->set('gender',$personGender);
						}else{
							throw new \Exception("valid Genders are m, f and n.");
						}
					}
					if(!empty($personType)){
						$this->getParameters()->set('type',$personType);
					}
				}else{
					$this->getParameters()->set('title',$title);
				}
				if(!empty($refnr)){
					$this->getParameters()->set("refnr",$refnr);
				}
				if(!empty($codename)){
					$this->getParameters()->set("codename",$refnr);
				}
				if($autoPublish!==NULL){
					$this->getParameters()->set("autoPublish",($autoPublish?1:0));
				}
			}else{
				throw new \Exception("Container Streamtypes need a valid Codename");
			}
		}else{
			throw new \Exception("Streamtype cannot be in ".implode(", ",streamtypes::getUploadableTypes()));
		}
	}

	public function createFromTopic(string $title, string $topic="", string $itemSource="",int $duration=0,int $itemCount=0, string $searchMode="", string $searchFields="", int $channel=0, int $format=0):void{
		if(in_array($this->streamtype,streamtypes::getSimpleContainerTypes())){
			$this->verb=defaults::VERB_POST;
			$this->method="fromtopic/";
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}else{
				throw new \Exception("title cant be empty");
			}
			if(!empty($channel)){
				$this->getParameters()->set("channel",$channel);
			}
			if(!empty($format)){
				$this->getParameters()->set("format",$format);
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getSimpleContainerTypes()));
		}
	}

	public function updateItemFile(string $url):void{
		if(in_array($this->streamtype,streamtypes::getUploadableTypes())){
			if(substr($url,0,4)=="http"){
				$this->verb=defaults::VERB_PUT;
				$this->method="updatefile";
				$this->getParameters()->set("url",$url);
			}else{
				throw new \Exception("URL must start with HTTP");
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getUploadableTypes()));
		}
	}

	public function updateItemMetaData(array $attributes=[]):void{
		$this->verb=defaults::VERB_PUT;
		$this->method="update";
		foreach($attributes as $key=>$value){
			if(is_null($value)){
				$value="";
			}else if(is_array($value)){
				$value="";
			}
			$this->getParameters()->set($key,$value);
		}
	}

	public function updateItemRestrictions(array $restrictions=[]):void{
		$this->verb=defaults::VERB_PUT;
		$this->method="updaterestrictions";
		foreach($restrictions as $key=>$value){
			if(is_null($value)){
				$value="";
			}else if(is_array($value)){
				$value="";
			}
			$this->getParameters()->set($key,$value);
		}
	}

	public function approveItem(string $reason,int $restrictToAge=0,array $contentModerationAspects=[]):void{
		$this->verb=defaults::VERB_POST;
		$this->method="approve";
		if(!empty($reason)){
			$this->getParameters()->set("reason",$reason);
		}
		if(in_array($restrictToAge,ageclasses::getAllTypes())){
			$this->getParameters()->set("restrictToAge",$restrictToAge);
		}
		if(!empty($contentModerationAspects)){
			$cm=[];
			foreach($contentModerationAspects as $cma){
				if(in_array($cma,contentmoderationaspects::getAllTypes())){
					array_push($cm,$cma);
				}
			}
			if(!empty($cm)){
				$this->getParameters()->set("contentModerationAspects",implode(",",$cm));
			}
		}
	}

	public function rejectItem(string $reason="",string $action=""):void{
		$this->verb=defaults::VERB_POST;
		$this->method="reject";
		if(!empty($reason)){
			$this->getParameters()->set("reason",$reason);
		}
		if(!empty($action)){
			if(in_array($action,rejectreasons::getAllTypes())){
				$this->getParameters()->set("action",$action);
			}
		}
	}

	public function publishItem():void{
		$this->verb=defaults::VERB_PUT;
		$this->method="publish";
	}

	public function unpublishItem(?bool $blockFuturePublishing=NULL):void{
		$this->verb=defaults::VERB_PUT;
		$this->method="unpublish";
		if($blockFuturePublishing!==NULL){
			$this->getParameters()->set("blockFuturePublishing",($blockFuturePublishing?1:0));
		}
	}

	public function unblockItem():void{
		$this->verb=defaults::VERB_PUT;
		$this->method="unblock";
	}

	public function pickItem(int $index=1):void{
		$this->verb=defaults::VERB_PUT;
		$this->method="pick";
		$this->getParameters()->set("index",$index);
	}

	public function unpickItem():void{
		$this->verb=defaults::VERB_PUT;
		$this->method="unpick";
	}

	public function setItemAsNew():void{
		$this->verb=defaults::VERB_PUT;
		$this->method="setasnew";
	}

	public function removeItem():void{
		$this->verb=defaults::VERB_DELETE;
		$this->method="remove";
	}

	public function transferItemToDomain(int $targetDomain=0,bool $andDeleteOriginal=FALSE):void{
		if($targetDomain>0){
			$this->verb=defaults::VERB_POST;
			$this->method="transfertodomain/".$targetDomain;
			if($andDeleteOriginal){
				$this->getParameters()->set("andDeleteOriginal",1);
			}
		}else{
			throw new \Exception("A target Domain must be given");
		}
	}

	public function reencodeItem(string $reason=""):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			$this->verb=defaults::VERB_POST;
			$this->method="reencode";
			if(!empty($reason)){
				$this->getParameters()->set("reason",$reason);
			}
		}else{
			throw new \Exception("Streamtype must be in video, audio");
		}
	}

	public function analyzeItemDetails():void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			$this->verb=defaults::VERB_POST;
			$this->method="analyzedetails";
		}else{
			throw new \Exception("Streamtype must be in video, audio");
		}
	}

	public function archiveItem():void{
		if(in_array($this->streamtype,[streamtypes::VIDEO])){
			$this->verb=defaults::VERB_POST;
			$this->method="archive";
		}else{
			throw new \Exception("Streamtype must be video");
		}
	}

	public function exportItem():void{
		$this->verb=defaults::VERB_POST;
	}

	public function exportItemAsPost():void{
		$this->verb=defaults::VERB_POST;
	}

	public function updateItemExport():void{
		$this->verb=defaults::VERB_PUT;
	}

	public function deleteItemExport(string $externalReference="",int $exportID=0):void{
		$this->verb=defaults::VERB_DELETE;
		$this->method="removeexport";
		if(!empty($externalReference)){
			$this->getParameters()->set("externalReference",$externalReference);
		}else if(!empty($exportID)){
			$this->getParameters()->set("item",$exportID);
		}else{
			throw new \Exception("the target Export must be given by internal or external Reference");
		}
	}

	public function addItemPreviewLink(string $language="",int $maxStarts=0,string $code="",bool $showAnnotations=TRUE,bool $allowAnnotations=TRUE,bool $allowSnapshots=FALSE,bool $allowSourceDownloads=FALSE):void{
		if(in_array($this->streamtype,streamtypes::getPlayerTypes())){
			$this->verb=defaults::VERB_POST;
			$this->method="addpreviewlink";
			if((!empty($language))&&(strlen($language)==2)){
				$this->getParameters()->set("language",$language);
			}
			if($maxStarts>0){
				$this->getParameters()->set("maxStarts",$maxStarts);
			}
			if(!empty($code)){
				$this->getParameters()->set("code",$code);
			}
			if($showAnnotations){
				$this->getParameters()->set("showAnnotations",1);
			}
			if($allowAnnotations){
				$this->getParameters()->set("allowAnnotations",1);
			}
			if($allowSnapshots){
				$this->getParameters()->set("allowSnapshots",1);
			}
			if($allowSourceDownloads){
				$this->getParameters()->set("allowSourceDownloads",1);
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getPlayerTypes()));
		}
	}

	public function deleteItemPreviewLink(int $previewlinkID=0):void{
		if($previewlinkID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removepreviewlink/".$previewlinkID;
		}else{
			throw new \Exception("the ID of the PreviewLink must be given.");
		}
	}
}