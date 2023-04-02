<?php
namespace nexxomnia\apicalls;

use nexxomnia\enums\defaults;
use nexxomnia\enums\networkmodes;
use nexxomnia\enums\streamtypes;

class domainmanagementcall extends \nexxomnia\internals\apicall{

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

	/**
	 * @throws \Exception on invalid Parameters
	 */
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

	/**
	 * @throws \Exception on invalid Domain ID
	 */
	public function markForDeletion(int $domainid=0):void{
		if(!empty($domainid)){
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

	/**
	 * @throws \Exception on invalid Channel ID
	 */
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

	/**
	 * @throws \Exception on invalid Channel ID
	 */
	public function removeChannel(int $channelID):void{
		if(!empty($channelID)){
			$this->setItem($channelID,"channel");
			$this->verb=defaults::VERB_DELETE;
			$this->method="remove";
		}else{
			throw new \Exception("Channel ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on empty URL or invalid Channel ID
	 */
	public function setChannelCover(int $channelID,String $url, String $assetLanguage="", String $description="",string $copyright="",bool $isAIGenerated=FALSE):void{
		if(!empty($channelID)){
			if(!empty($url)){
				$this->setItem($channelID,"channel");
				$this->verb=defaults::VERB_POST;
				$this->method="cover";
				if(!empty($description)){
					$this->getParameters()->set("description",$description);
				}
				if(!empty($copyright)){
					$this->getParameters()->set("copyright",$copyright);
				}
				if(!empty($assetLanguage)){
					$this->getParameters()->set("assetLanguage",$assetLanguage);
				}
				$this->getParameters()->set('isAIGenerated',($isAIGenerated?1:0));
			}else{
				throw new \Exception("Cover URL cant be empty");
			}
		}else{
			throw new \Exception("Channel ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on empty URL or invalid Channel ID
	 */
	public function setChannelCoverActionShot(int $channelID,String $url, String $assetLanguage="", String $description="",string $copyright="",bool $isAIGenerated=FALSE):void{
		if(!empty($channelID)){
			if(!empty($url)){
				$this->setItem($channelID,"channel");
				$this->verb=defaults::VERB_POST;
				$this->method="actionshot";
				if(!empty($description)){
					$this->getParameters()->set("description",$description);
				}
				if(!empty($copyright)){
					$this->getParameters()->set("copyright",$copyright);
				}
				if(!empty($assetLanguage)){
					$this->getParameters()->set("assetLanguage",$assetLanguage);
				}
				$this->getParameters()->set('isAIGenerated',($isAIGenerated?1:0));
			}else{
				throw new \Exception("Cover URL cant be empty");
			}
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

	/**
	 * @throws \Exception on invalid Format ID
	 */
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

	/**
	 * @throws \Exception on invalid Format ID
	 */
	public function removeFormat(int $formatID):void{
		if(!empty($formatID)){
			$this->setItem($formatID,"format");
			$this->verb=defaults::VERB_DELETE;
			$this->method="remove";
		}else{
			throw new \Exception("Format ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on empty URL or invalid Format ID
	 */
	public function setFormatCover(int $formatID, String $url, String $assetLanguage="", String $description="",string $copyright="",bool $isAIGenerated=FALSE):void{
		if(!empty($formatID)){
			if(!empty($url)){
				$this->setItem($formatID,"format");
				$this->verb=defaults::VERB_POST;
				$this->method="cover";
				if(!empty($description)){
					$this->getParameters()->set("description",$description);
				}
				if(!empty($copyright)){
					$this->getParameters()->set("copyright",$copyright);
				}
				if(!empty($assetLanguage)){
					$this->getParameters()->set("assetLanguage",$assetLanguage);
				}
				$this->getParameters()->set('isAIGenerated',($isAIGenerated?1:0));
			}else{
				throw new \Exception("Cover URL cant be empty");
			}
		}else{
			throw new \Exception("Format ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on empty URL or invalid Format ID
	 */
	public function setFormatCoverActionShot(int $formatID, String $url,String $assetLanguage="", String $description="",string $copyright="",bool $isAIGenerated=FALSE):void{
		if(!empty($formatID)){
			if(!empty($url)){
				$this->setItem($formatID,"format");
				$this->verb=defaults::VERB_POST;
				$this->method="actionshot";
				if(!empty($description)){
					$this->getParameters()->set("description",$description);
				}
				if(!empty($copyright)){
					$this->getParameters()->set("copyright",$copyright);
				}
				if(!empty($assetLanguage)){
					$this->getParameters()->set("assetLanguage",$assetLanguage);
				}
				$this->getParameters()->set('isAIGenerated',($isAIGenerated?1:0));
			}else{
				throw new \Exception("Cover URL cant be empty");
			}
		}else{
			throw new \Exception("Format ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addUploadLink(string $title="",array $selectedStreamtypes=[],string $language="",int $maxUsages=0,string $code="",bool $useDomainStyle=FALSE, bool $askForNotes=TRUE):void{
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
		if($useDomainStyle){
			$this->getParameters()->set("useDomainStyle",1);
		}
		$this->getParameters()->set('askForNotes',($askForNotes?1:0));
	}

	/**
	 * @throws \Exception on invalid UploadLink ID
	 */
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

	private function setCategoryCover(string $streamtype="",int $categoryID=0,string $url="",string $assetLanguage="",string $description="",string $copyright="",bool $isAIGenerated=FALSE):void{
		if(!empty($categoryID)){
			if(!empty($url)){
				$this->setItem($categoryID,$streamtype."category");
				$this->verb=defaults::VERB_POST;
				$this->method="cover";
				if(!empty($description)){
					$this->getParameters()->set("description",$description);
				}
				if(!empty($copyright)){
					$this->getParameters()->set("copyright",$copyright);
				}
				if(!empty($assetLanguage)){
					$this->getParameters()->set("assetLanguage",$assetLanguage);
				}
				$this->getParameters()->set('isAIGenerated',($isAIGenerated?1:0));
			}else{
				throw new \Exception("Cover URL cant be empty");
			}
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

	public function addProductCategory(array $attributes=[]):void{
		$this->addCategory(streamtypes::PRODUCT,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateVideoCategory(int $categoryID=0,$attributes=[]):void{
		$this->updateCategory(streamtypes::VIDEO,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateAudioCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::AUDIO,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateImageCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::IMAGE,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateFileCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::FILE,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateArticleCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::ARTICLE,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateEventCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::EVENT,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updatePlaceCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::PLACE,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function updateProductCategory(int $categoryID=0,array $attributes=[]):void{
		$this->updateCategory(streamtypes::PRODUCT,$categoryID,$attributes);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteVideoCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::VIDEO,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteAudioCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::AUDIO,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteImageCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::IMAGE,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteFileCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::FILE,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteArticleCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::ARTICLE,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteEventCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::EVENT,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deletePlaceCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::PLACE,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID
	 */
	public function deleteProductCategory(int $categoryID=0):void{
		$this->deleteCategory(streamtypes::PRODUCT,$categoryID);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setVideoCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::VIDEO,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setAudioCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::AUDIO,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setImageCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::IMAGE,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setArticleCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::ARTICLE,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setEventCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::EVENT,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setFileCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::FILE,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setPlaceCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::PLACE,$categoryID,$url,$description);
	}

	/**
	 * @throws \Exception on invalid Category ID or empty URL
	 */
	public function setProductCategoryCover(int $categoryID=0,String $url="", String $description=""):void{
		$this->setCategoryCover(streamtypes::PRODUCT,$categoryID,$url,$description);
	}
}