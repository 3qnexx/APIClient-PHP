<?php

namespace nexxOMNIA\apicall;

use nexxOMNIA\enums\defaults;
use nexxOMNIA\enums\networkmodes;
use nexxOMNIA\enums\streamtypes;

class domainmanagement extends \nexxOMNIA\internal\apicall{

	protected string $streamtype="";
	protected string $method="";
	protected int $item=0;

	public function __construct(){
		parent::__construct();
		$this->path="manage/";
	}

	public function getPath():string{
		$this->path.=streamtypes::getPluralizedStreamtype($this->streamtype)."/".($this->item>0?$this->item."/":"").$this->method;
		return(parent::getPath());
	}

	private function setStreamtype(string $streamtype):void{
		$this->streamtype=$streamtype;
	}

	private function setItem(int $item=0, string $streamtype=""):void{
		if(!empty($streamtype)){
			$this->setStreamtype($streamtype);
		}
		if($item>0){
			$this->item=$item;
		}
	}

	public function cloneForNetwork(string $title="",string $url="",string $networkmode=networkmodes::OWN,string $refnr=""):void{
		$this->setStreamtype("domain");
		$this->verb=defaults::VERB_POST;
		$this->method="clonefornetwork";
		if(!empty($title)){
			$this->getParameters()->set("title",$title);
		}else{
			throw new \Exception("Title cant be empty");
		}
		if(!empty($url)){
			$this->getParameters()->set("url",$url);
		}else{
			throw new \Exception("URL cant be empty");
		}
		if(in_array($networkmode,networkmodes::getAllTypes())){
			$this->getParameters()->set("networkmode",$networkmode);
		}else{
			throw new \Exception("NetworkMode must be one of ".implode(",",networkmodes::getAllTypes()));
		}
		if(!empty($refnr)){
			$this->getParameters()->set("refnr",$refnr);
		}
	}

	public function markForDeletion(int $domainid=0):void{
		if(!empty($domainID)){
			$this->setItem($domainid,"domain");
			$this->verb=defaults::VERB_DELETE;
			$this->method="markfordeletion";
		}else{
			throw new \Exception("Domain ID cant be empty");
		}
	}

	public function addChannel(array $attributes=[]):void{
		$this->setStreamtype("channel");
		$this->verb=defaults::VERB_POST;
		$this->method="add";
		foreach($attributes as $key=>$value){
			if(is_null($value)){
				$value="";
			}else if(is_array($value)){
				$value="";
			}
			$this->getParameters()->set($key,$value);
		}
	}

	public function updateChannel(int $channelID,array $attributes=[]):void{
		if(!empty($channelID)){
			$this->setItem($channelID,"channel");
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
		}else{
			throw new \Exception("Channel ID cant be empty");
		}
	}

	public function removeChannel(int $channelID):void{
		if(!empty($channelID)){
			$this->setItem($channelID,"channel");
			$this->verb=defaults::VERB_DELETE;
			$this->method="remove";
		}else{
			throw new \Exception("Channel ID cant be empty");
		}
	}

	public function addFormat(array $attributes=[]):void{
		$this->setStreamtype("format");
		$this->verb=defaults::VERB_POST;
		$this->method="add";
		foreach($attributes as $key=>$value){
			if(is_null($value)){
				$value="";
			}else if(is_array($value)){
				$value="";
			}
			$this->getParameters()->set($key,$value);
		}
	}

	public function updateFormat(int $formatID,array $attributes=[]):void{
		if(!empty($formatID)){
			$this->setItem($formatID,"format");
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
		}else{
			throw new \Exception("Format ID cant be empty");
		}
	}

	public function removeFormat(int $formatID):void{
		if(!empty($formatID)){
			$this->setItem($formatID,"format");
			$this->verb=defaults::VERB_DELETE;
			$this->method="remove";
		}else{
			throw new \Exception("Format ID cant be empty");
		}
	}

	public function addUploadLink(string $title="",array $selectedStreamtypes=[],string $language="",int $maxUsages=0,string $code=""):void{
		$this->setStreamtype("uploadlink");
		$this->verb=defaults::VERB_POST;
		$this->method="add";
		if(!empty($title)){
			$this->getParameters()->set("title",$title);
		}else{
			throw new \Exception("Title cant be empty");
		}
		if(empty($selectedStreamtypes)){
			throw new \Exception("At least one Streamtype must be selected");
		}else{
			$arr=[];
			foreach($selectedStreamtypes as $st){
				if(in_array($st,streamtypes::getUploadableTypes())){
					array_push($arr,$st);
				}
			}
			if(empty($arr)){
				throw new \Exception("At least one valid Streamtype must be selected");
			}else{
				$this->getParameters()->set("selectedStreamtypes",implode(",",$arr));
			}
		}
		if(strlen($language)==2){
			$this->getParameters()->set("language",$language);
		}
		if($maxUsages>0){
			$this->getParameters()->set("maxUsages",$maxUsages);
		}
		if(!empty($code)){
			$this->getParameters()->set("code",$code);
		}
	}

	public function deleteUploadLink($uploadLinkID=0):void{
		if(!empty($uploadLinkID)){
			$this->setItem($uploadLinkID,"uploadlink");
			$this->verb=defaults::VERB_DELETE;
			$this->method="remove";
		}else{
			throw new \Exception("UploadLink ID cant be empty");
		}
	}

	private function addCategory(string $streamtype,array $attributes):void{
		$this->setStreamtype($streamtype."category");
		$this->verb=defaults::VERB_POST;
		$this->method="add";
		foreach($attributes as $key=>$value){
			if(is_null($value)){
				$value="";
			}else if(is_array($value)){
				$value="";
			}
			$this->getParameters()->set($key,$value);
		}
	}

	private function updateCategory(string $streamtype="",int $categoryID=0,$attributes=[]):void{
		if(!empty($categoryID)){
			$this->setItem($categoryID,$streamtype."category");
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
		}else{
			throw new \Exception("Category ID cant be empty");
		}
	}

	private function deleteCategory(string $streamtype="",int $categoryID=0):void{
		if(!empty($categoryID)){
			$this->setItem($categoryID,$streamtype."category");
			$this->verb=defaults::VERB_DELETE;
			$this->method="remove";
		}else{
			throw new \Exception("Category ID cant be empty");
		}
	}

	public function addVideoCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::VIDEO,$attributes);
	}

	public function addAudioCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::AUDIO,$attributes);
	}

	public function addImageCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::IMAGE,$attributes);
	}

	public function addFileCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::FILE,$attributes);
	}

	public function addArticleCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::ARTICLE,$attributes);
	}

	public function addEventCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::EVENT,$attributes);
	}

	public function addPlaceCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::PLACE,$attributes);
	}

	public function updateVideoCategory(int $categoryID=0,$attributes=[]):void{
		$this->updateCategory(streamtypes::VIDEO,$categoryID,$attributes);
	}

	public function updateAudioCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::AUDIO,$categoryID,$attributes);
	}

	public function updateImageCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::IMAGE,$categoryID,$attributes);
	}

	public function updateFileCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::FILE,$categoryID,$attributes);
	}

	public function updateArticleCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::ARTICLE,$categoryID,$attributes);
	}

	public function updateEventCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::EVENT,$categoryID,$attributes);
	}

	public function updatePlaceCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::PLACE,$categoryID,$attributes);
	}

	public function deleteVideoCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::VIDEO,$categoryID);
	}

	public function deleteAudioCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::AUDIO,$categoryID);
	}

	public function deleteImageCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::IMAGE,$categoryID);
	}

	public function deleteFileCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::FILE,$categoryID);
	}

	public function deleteArticleCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::ARTICLE,$categoryID);
	}

	public function deleteEventCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::EVENT,$categoryID);
	}

	public function deletePlaceCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::PLACE,$categoryID);
	}

}