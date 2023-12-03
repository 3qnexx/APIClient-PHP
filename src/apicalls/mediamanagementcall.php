<?php
namespace nexxomnia\apicalls;

use nexxomnia\enums\ageclasses;
use nexxomnia\enums\autoorderattributes;
use nexxomnia\enums\texttrackroles;
use nexxomnia\enums\contentmoderationaspects;
use nexxomnia\enums\defaults;
use nexxomnia\enums\exportparts;
use nexxomnia\enums\externalplatformcontexts;
use nexxomnia\enums\externalplatforms;
use nexxomnia\enums\externalstates;
use nexxomnia\enums\highlightvideopurposes;
use nexxomnia\enums\hotspottypes;
use nexxomnia\enums\liveplaybackstates;
use nexxomnia\enums\livesourcetypes;
use nexxomnia\enums\livestreamtypes;
use nexxomnia\enums\topicitemsources;
use nexxomnia\enums\querymodes;
use nexxomnia\enums\rejectactions;
use nexxomnia\enums\scenepurposes;
use nexxomnia\enums\streamtypes;
use nexxomnia\enums\awardstates;
use nexxomnia\internals\tools;

class mediamanagementcall extends \nexxomnia\internals\apicall{

	protected string $streamtype="";
	protected string $method="";
	protected int $item=0;

	public function __construct(){
		parent::__construct();
		$this->setStreamtype(streamtypes::VIDEO);
	}

	public function getPath():string{
		$this->path="manage/".streamtypes::getPluralizedStreamtype($this->streamtype)."/".($this->item>0?$this->item."/":"").$this->method;
		return(parent::getPath());
	}

	/**
	 * @throws \Exception on invalid Streamtype
	 */
	public function setStreamtype(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->streamtype=$streamtype;
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	/**
	 * @throws \Exception on invalid Streamtype
	 */
	public function setItem(int $item=0, string $streamtype=""):void{
		if(!empty($streamtype)){
			$this->setStreamtype($streamtype);
		}
		if($item>0){
			$this->item=$item;
		}
	}

	private function handleCover(string $method,string $url="",string $description="",string $copyright="",string $assetLanguage="",bool $isAIGenerated=FALSE,float $fromTime=0):void{
		if(substr($url,0,4)=="http"){
			$this->verb=defaults::VERB_POST;
			$this->method=$method;
			$this->getParameters()->set("url",$url);
			if(!empty($description)){
				$this->getParameters()->set("description",$description);
			}
			if(!empty($copyright)){
				$this->getParameters()->set("copyright",$copyright);
			}
			if(!empty($assetLanguage)){
				$this->getParameters()->set("assetLanguage",$assetLanguage);
			}
			$this->getParameters()->set("isAIGenerated",($isAIGenerated?1:0));
		}else if(($fromTime>0)&&(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::SCENE,streamtypes::VARIANT]))){
			$this->verb=defaults::VERB_POST;
			$this->method=$method;
			$this->getParameters()->set("fromTime",$fromTime);
			if(!empty($description)){
				$this->getParameters()->set("description",$description);
			}
			if(!empty($copyright)){
				$this->getParameters()->set("description",$description);
			}
			if(!empty($assetLanguage)){
				$this->getParameters()->set("assetLanguage",$assetLanguage);
			}
			$this->getParameters()->set("isAIGenerated",($isAIGenerated?1:0));
		}else{
			throw new \Exception("a valid Cover URL or TimeStamp (on Video Streamtypes only).");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createFromURL(string $url, bool $useQueue=TRUE,?bool $autoPublish=NULL, string $refnr="",int $queueStart=0, string $asVariantFor="", int $asVariantOf=0, string $sourceLanguage="", string $notes=""):void{
		if(in_array($this->streamtype,streamtypes::getUploadableTypes())){
			if(substr($url,0,4)=="http"){
				$this->verb=defaults::VERB_POST;
				$this->method="fromurl";
				$this->getParameters()->set("url",$url);
				if(!empty($refnr)){
					$this->getParameters()->set("refnr",$refnr);
				}
				if(!empty($notes)){
					$this->getParameters()->set("notes",$notes);
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
				if(($this->streamtype==streamtypes::VIDEO)||($this->streamtype==streamtypes::AUDIO)){
					if(strlen($sourceLanguage)==2){
						$this->getParameters()->set("language",$sourceLanguage);
					}
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

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createFromData(string $title,string $refnr="",string $codename="",?bool $autoPublish=NULL,string $personGender="",string $personType=""):void{
		if(!in_array($this->streamtype,streamtypes::getUploadableTypes())){
			if(!empty($title)){
				$this->verb=defaults::VERB_POST;
				$this->method="fromdata";
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
				throw new \Exception("Title is mandatory");
			}
		}else{
			throw new \Exception("Streamtype cannot be in ".implode(", ",streamtypes::getUploadableTypes()));
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createFromTopic(string $title, string $topic="", string $itemSource="",int $duration=0,int $itemCount=0, string $searchMode="", array $searchFields=[], int $channel=0, int $format=0, int $category=0):void{
		if(in_array($this->streamtype,streamtypes::getSimpleContainerTypes())){
			$this->verb=defaults::VERB_POST;
			$this->method="fromtopic";
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}else{
				throw new \Exception("title cant be empty");
			}
			$isSearch=FALSE;
			if(!empty($topic)){
				$isSearch=TRUE;
				if(!empty($searchFields)){
					$this->getParameters()->set("searchFields",implode(",",$searchFields));
				}
				if((!empty($searchMode))&&(in_array($searchMode,querymodes::getAllTypes()))){
					$this->getParameters()->set("searchMode",$searchMode);
				}
			}
			if(in_array($this->streamtype,[streamtypes::ALBUM,streamtypes::MAGAZINE])){
				if(empty($itemCount)){
					throw new \Exception("this streamtype requires a valid itemCount.");
				}else{
					$this->getParameters()->set("items",$itemCount);
				}
			}else{
				if(empty($duration)){
					throw new \Exception("this streamtype requires a valid target Duration.");
				}else{
					$this->getParameters()->set("duration",$duration);
				}
			}
			if((!$isSearch)&&(empty($itemSource))){
				throw new \Exception("if no topic is given, an itemSource is necessary.");
			}else if(in_array($itemSource,topicitemsources::getAllTypes())){
				$this->getParameters()->set("itemSource",$itemSource);
			}else{
				throw new \Exception("if no topic is given, a valid itemSource is necessary.");
			}
			if(!empty($channel)){
				$this->getParameters()->set("channel",$channel);
			}
			if(!empty($format)){
				$this->getParameters()->set("format",$format);
			}
			if(!empty($category)){
				$this->getParameters()->set("category",$category);
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getSimpleContainerTypes()));
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createHighlightVideoFromVideo(int $videoID,int $duration,bool $includeAudio=TRUE,string $purpose=""):void{
		if($videoID>0){
			$this->setStreamtype(streamtypes::VIDEO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromvideo/".$videoID;
			if(!empty($duration)){
				$this->getParameters()->set("duration",$duration);
			}else{
				throw new \Exception("a Duration must be given.");
			}
			if($includeAudio){
				$this->getParameters()->set("includeAudio",1);
			}
			if(in_array($purpose,highlightvideopurposes::getAllTypes())){
				$this->getParameters()->set("purpose",$purpose);
			}
		}else{
			throw new \Exception("the Video ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createLiveStreamFromLiveConnection(int $liveConnectionID=0,string $title="",string $type=livestreamtypes::EVENT):void{
		if($liveConnectionID>0){
			$this->setStreamtype(streamtypes::LIVE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromliveconnection/".$liveConnectionID;
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
		}else{
			throw new \Exception("the LiveConnection ID must be given.");
		}
	}

	public function createLiveStreamFromAutoLiveConnection(string $title="",string $type=livestreamtypes::EVENT,string $sourceType=livesourcetypes::RTMP,bool $enableDVR=FALSE):void{
		$this->setStreamtype(streamtypes::LIVE);
		$this->verb=defaults::VERB_POST;
		$this->method="fromautoliveconnection";
		if(!empty($title)){
			$this->getParameters()->set("title",$title);
		}
		if(!empty($sourceType)){
			if(in_array($type,livesourcetypes::getAllTypes())){
				$this->getParameters()->set("sourceType",$type);
			}
		}
		if($enableDVR){
			$this->getParameters()->set("enableDVR",1);
		}
		if(in_array($type,livestreamtypes::getAllTypes())){
			$this->getParameters()->set("type",$type);
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createLiveStreamFromRemote(string $hlsURL,string $dashURL="",string $title="",string $type=livestreamtypes::EVENT,bool $supportsDVR=FALSE,bool $supportsLowLatency=FALSE):void{
		if((!empty($hlsURL))&&(substr($hlsURL,0,4)=="http")){
			$this->setStreamtype(streamtypes::LIVE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromremote";
			$this->getParameters()->set("hlsURL",$hlsURL);
			if((!empty($dashURL))&&(substr($dashURL,0,4)=="http")){
				$this->getParameters()->set("dashURL",$dashURL);
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
			if($supportsDVR){
				$this->getParameters()->set("supportsDVR",1);
			}
			if($supportsLowLatency){
				$this->getParameters()->set("supportsLowLatency",1);
			}
		}else{
			throw new \Exception("a valid HLS URL must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createRadioFromLiveConnection(int $liveConnectionID=0,string $title="",string $type=livestreamtypes::EVENT):void{
		if($liveConnectionID>0){
			$this->setStreamtype(streamtypes::RADIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromliveconnection/".$liveConnectionID;
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
		}else{
			throw new \Exception("the LiveConnection ID must be given.");
		}
	}

	public function createRadioFromAutoLiveConnection(string $title="",string $type=livestreamtypes::EVENT):void{
		$this->setStreamtype(streamtypes::RADIO);
		$this->verb=defaults::VERB_POST;
		$this->method="fromautoliveconnection";
		if(!empty($title)){
			$this->getParameters()->set("title",$title);
		}
		if(in_array($type,livestreamtypes::getAllTypes())){
			$this->getParameters()->set("type",$type);
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createRadioFromRemote(string $mp3URL,string $title="",string $type=livestreamtypes::EVENT,string $aacURL="",string $opusURL=""):void{
		if((!empty($mp3URL))&&(substr($mp3URL,0,4)=="http")){
			$this->setStreamtype(streamtypes::RADIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromremote";
			$this->getParameters()->set("mp3URL",$mp3URL);
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(!empty($aacURL)){
				$this->getParameters()->set("aacURL",$aacURL);
			}
			if(!empty($opusURL)){
				$this->getParameters()->set("opusURL",$opusURL);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
		}else{
			throw new \Exception("a valid MP3 URL must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createSceneFromVideo(int $videoID,float $from,float $until=0,string $title="",string $purpose=scenepurposes::CHAPTER):void{
		if($videoID>0){
			$this->setStreamtype(streamtypes::SCENE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromvideo/".$videoID;
			if(!empty($from)){
				$this->getParameters()->set("from",$from);
			}else{
				throw new \Exception("a valid start time for the Scene must be given");
			}
			if(!empty($until)){
				$this->getParameters()->set("until",$until);
			}else{
				throw new \Exception("a valid end time for the Scene must be given");
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($purpose,scenepurposes::getAllTypes())){
				$this->getParameters()->set("purpose",$purpose);
			}
		}else{
			throw new \Exception("the Video ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createLivestreamFromVideo(int $videoID,int $liveConnection, int $startAt,string $title=""):void{
		if($videoID>0){
			$this->setStreamtype(streamtypes::LIVE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromvideo/".$videoID;
			if(!empty($liveConnection)){
				$this->getParameters()->set("liveConnection",$liveConnection);
			}else{
				throw new \Exception("a valid liveConnection must be given");
			}
			if(!empty($startAt)){
				$this->getParameters()->set("start",$startAt);
			}else{
				throw new \Exception("a valid start time for the LiveStream must be given");
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
		}else{
			throw new \Exception("the Video ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createAudioFromVideo(int $videoID=0):void{
		if($videoID>0){
			$this->setStreamtype(streamtypes::AUDIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromvideo/".$videoID;
		}else{
			throw new \Exception("the Video ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createAudioFromCaption(int $captionID=0):void{
		if($captionID>0){
			$this->setStreamtype(streamtypes::AUDIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromcaption/".$captionID;
		}else{
			throw new \Exception("the Caption ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createAudioFromText(string $textcontent, string $title="", string $subtitle="", string $teaser="",string $language="",string $voice=""):void{
		if(!empty($textcontent)){
			$this->setStreamtype(streamtypes::AUDIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromtext";
			$this->getParameters()->set("textcontent",$textcontent);
			if(strlen($language)==2){
				$this->getParameters()->set("language",$language);
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(!empty($subtitle)){
				$this->getParameters()->set("subtitle",$subtitle);
			}
			if(!empty($teaser)){
				$this->getParameters()->set("teaser",$teaser);
			}
			if(!empty($voice)){
				$this->getParameters()->set("voice",$voice);
			}
		}else{
			throw new \Exception("a non-empty Textcontent must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createImageFromVideo(int $videoID=0,float $from=0,float $until=0,string $title="",bool $useAsCover=FALSE):void{
		if($videoID>0){
			$this->setStreamtype(streamtypes::IMAGE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromvideo/".$videoID;
			if(!empty($from)){
				$this->getParameters()->set("from",$from);
			}
			if(!empty($until)){
				$this->getParameters()->set("until",$until);
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if($useAsCover){
				$this->getParameters()->set("useAsCover",1);
			}
		}else{
			throw new \Exception("the Video ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createPostFromText(int $accountID=0,string $postText="",int $postImage=0):void{
		if($accountID>0){
			$this->setStreamtype(streamtypes::POST);
			$this->verb=defaults::VERB_POST;
			$this->method="fromtext";
			$this->getParameters()->set("account",$accountID);
			if(!empty($postText)){
				$this->getParameters()->set("postText",$postText);
			}else{
				throw new \Exception("the Posting Text cant be empty.");
			}
			if($postImage>0){
				$this->getParameters()->set("postImage",$postImage);
			}
		}else{
			throw new \Exception("the Account ID must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
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

	public function updateAudioContent(string $textcontent="", string $title="", string $subtitle="", string $teaser="",string $language="",string $voice=""):void{
		$this->setStreamtype(streamtypes::AUDIO);
		$this->verb=defaults::VERB_PUT;
		$this->method="updatecontent";
		$this->getParameters()->set("textcontent",$textcontent);
		if(!empty($title)){
			$this->getParameters()->set("title",$title);
		}
		if(!empty($subtitle)){
			$this->getParameters()->set("subtitle",$subtitle);
		}
		if(!empty($teaser)){
			$this->getParameters()->set("teaser",$teaser);
		}
		if(!empty($textcontent)){
			$this->getParameters()->set("textcontent",$textcontent);
		}
		if(!empty($language)){
			$this->getParameters()->set("language",$language);
		}
		if(!empty($voice)){
			$this->getParameters()->set("voice",$voice);
		}
	}

	public function updateAudioRepresentation(bool $includeTitle=FALSE,bool $includeSubtitle=FALSE,bool $includeTeaser=FALSE,bool $includeFragments=FALSE):void{
		$this->setStreamtype(streamtypes::ARTICLE);
		$this->verb=defaults::VERB_PUT;
		$this->method="updateaudiorepresentation";
		if($includeTitle){
			$this->getParameters()->set("includeTitle",1);
		}
		if($includeSubtitle){
			$this->getParameters()->set("includeSubtitle",1);
		}
		if($includeTeaser){
			$this->getParameters()->set("includeTeaser",1);
		}
		if($includeFragments){
			$this->getParameters()->set("includeFragments",1);
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
			if(in_array($action,rejectactions::getAllTypes())){
				$this->getParameters()->set("action",$action);
			}
		}
	}

	public function publishItem():void{
		$this->verb=defaults::VERB_PUT;
		$this->method="publish";
	}

	public function unpublishItem(bool $blockFuturePublishing=FALSE):void{
		$this->verb=defaults::VERB_PUT;
		$this->method="unpublish";
		if($blockFuturePublishing){
			$this->getParameters()->set("blockFuturePublishing",1);
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

	/**
	 * @throws \Exception on invalid Parameters
	 */
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

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function reorderItem(?array $itemlist=[],string $autoOrder="",string $autoOrderDirection=""):void{
		if(in_array($this->streamtype,streamtypes::getContainerTypes())){
			$this->verb=defaults::VERB_PUT;
			$this->method="reorder";
			if(!empty($itemlist)){
				$this->getParameters()->set("itemlist",implode(",",$itemlist));
			}else if((!empty($autoOrder))&&(in_array($autoOrder,autoorderattributes::getAllTypes()))){
				$this->getParameters()->set("autoorder",$autoOrder);
				if(in_array($autoOrderDirection,["ASC","DESC"])){
					$this->getParameters()->set("autoorderdirection",$autoOrderDirection);
				}
			}else{
				throw new \Exception("an itemlist or a valid autoOrder String must be given");
			}
		}else{
			throw new \Exception("only Container Elements can be reordered.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
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

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function analyzeItemDetails():void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			$this->verb=defaults::VERB_POST;
			$this->method="analyzedetails";
		}else{
			throw new \Exception("Streamtype must be in video, audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function archiveItem():void{
		if(in_array($this->streamtype,[streamtypes::VIDEO])){
			$this->verb=defaults::VERB_POST;
			$this->method="archive";
		}else{
			throw new \Exception("Streamtype must be video");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function terminateStream():void{
		if(in_array($this->streamtype,[streamtypes::LIVE])){
			$this->verb=defaults::VERB_POST;
			$this->method="terminate";
		}else{
			throw new \Exception("Streamtype must be live");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function updatePlaybackState(string $state):void{
		if(in_array($this->streamtype,[streamtypes::LIVE])){
			if(in_array($state,liveplaybackstates::getAllTypes())){
				$this->verb=defaults::VERB_POST;
				$this->method="updateplaybackstate";
				$this->getParameters()->set('state',$state);
			}else{
				throw new \Exception("Live PlaybackState must be in on,pause");
			}
		}else{
			throw new \Exception("Streamtype must be live");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function startRecording():void{
		if(in_array($this->streamtype,[streamtypes::LIVE])){
			$this->verb=defaults::VERB_POST;
			$this->method="startrecording";
		}else{
			throw new \Exception("Streamtype must be live");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function stopRecording():void{
		if(in_array($this->streamtype,[streamtypes::LIVE])){
			$this->verb=defaults::VERB_POST;
			$this->method="stoprecording";
		}else{
			throw new \Exception("Streamtype must be live");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function exportItem(int $accountID,string $externalCategory="",string $externalState=externalstates::PUBLIC, string $postText="",int $publicationDate=0,int $inVariant=0,int $list=0, string $platformContext=""):void{
		if($accountID>0){
			if(in_array($this->streamtype,streamtypes::getExportableTypes())){
				$this->verb=defaults::VERB_POST;
				$this->method="export";
				$this->getParameters()->set("account",$accountID);
				if(!empty($list)){
					$this->getParameters()->set("list",$list);
				}
				if(!empty($externalCategory)){
					$this->getParameters()->set("externalCategory",$externalCategory);
				}
				if(in_array($externalState,externalstates::getAllTypes())){
					$this->getParameters()->set("externalState",$externalState);
				}
				if(($publicationDate>0)&&($externalState==externalstates::PRIVATE)){
					$this->getParameters()->set("publicationDate",$publicationDate);
				}
				if(($this->streamtype==streamtypes::VIDEO)&&(!empty($platformContext))&&(in_array($platformContext,externalplatformcontexts::getAllTypes()))){
					$this->getParameters()->set("platformContext",$platformContext);
				}
				if(($this->streamtype==streamtypes::VIDEO)&&(!empty($inVariant))){
					$this->getParameters()->set("inVariant",$inVariant);
				}else if(($this->streamtype==streamtypes::ARTICLE)&&(!empty($postText))){
					$this->getParameters()->set("postText",$postText);
				}
			}else{
				throw new \Exception("this streamtype is not supported.");
			}
		}else{
			throw new \Exception("the ID of the Account must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function exportItemAsPost(int $accountID,string $postURL="",string $postText="",string $postImage="",bool $postWithLink=FALSE):void{
		if($accountID>0){
			if(in_array($this->streamtype,streamtypes::getExportableTypes())){
				$this->verb=defaults::VERB_POST;
				$this->method="exportaspost";
				$this->getParameters()->set("account",$accountID);
				if(!empty($postURL)){
					$this->getParameters()->set("postURL",$postURL);
				}
				if(!empty($postText)){
					$this->getParameters()->set("postText",$postText);
				}
				if(!empty($postImage)){
					$this->getParameters()->set("postImage",$postImage);
				}
				 if(($this->streamtype==streamtypes::ARTICLE)&&($postWithLink)){
					$this->getParameters()->set("postWithLink",1);
				}
			}else{
				throw new \Exception("this streamtype is not supported.");
			}
		}else{
			throw new \Exception("the ID of the Account must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function updateItemExport(string $externalReference="",int $exportID=0,string $partToUpdate=""):void{
		$this->verb=defaults::VERB_PUT;
		$this->method="updateexport";
		if(in_array($partToUpdate,exportparts::getAllTypes())){
			$this->getParameters()->set("partToUpdate",$partToUpdate);
		}else{
			throw new \Exception("the partToUpdate Parameter must be one of ".implode(",",exportparts::getAllTypes()));
		}
		if(!empty($externalReference)){
			$this->getParameters()->set("externalReference",$externalReference);
		}else if(!empty($exportID)){
			$this->getParameters()->set("item",$exportID);
		}else{
			throw new \Exception("the target Export must be given by internal or external Reference");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
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

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemPreviewLink(string $title, string $language="",int $maxStarts=0,string $code="",bool $showAnnotations=TRUE,bool $allowAnnotations=TRUE,bool $allowSnapshots=FALSE,bool $allowSourceDownloads=FALSE,bool $useDomainStyle=FALSE):void{
		if(in_array($this->streamtype,streamtypes::getPlayerTypes())){
			$this->verb=defaults::VERB_POST;
			$this->method="addpreviewlink";

			$this->getParameters()->set("title",$title);
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
			if($useDomainStyle){
				$this->getParameters()->set("useDomainStyle",1);
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getPlayerTypes()));
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function deleteItemPreviewLink(int $previewlinkID=0):void{
		if($previewlinkID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removepreviewlink/".$previewlinkID;
		}else{
			throw new \Exception("the ID of the PreviewLink must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemDownloadLink(string $title, string $language="",int $maxStarts=0,string $code="",bool $useDomainStyle=FALSE, string $fileType='',bool $includeTextTracks=TRUE):void{
		if(in_array($this->streamtype,streamtypes::getDownloadLinkTypes())){
			$this->verb=defaults::VERB_POST;
			$this->method="adddownloadlink";

			$this->getParameters()->set("title",$title);
			if((!empty($language))&&(strlen($language)==2)){
				$this->getParameters()->set("language",$language);
			}
			if($maxStarts>0){
				$this->getParameters()->set("maxStarts",$maxStarts);
			}
			if(!empty($code)){
				$this->getParameters()->set("code",$code);
			}
			if(!empty($fileType)){
				$this->getParameters()->set("fileType",$fileType);
			}
			if($includeTextTracks){
				$this->getParameters()->set("includeTextTracks",1);
			}
			if($useDomainStyle){
				$this->getParameters()->set("useDomainStyle",1);
			}
		}else{
			throw new \Exception("Streamtype must be in ".implode(", ",streamtypes::getUploadableTypes()));
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function deleteItemDownloadLink(int $downloadlinkID=0):void{
		if($downloadlinkID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removedownloadlink/".$downloadlinkID;
		}else{
			throw new \Exception("the ID of the DownloadLink must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemToContainer(int $containerID=0):void{
		if($containerID>0){
			$this->verb=defaults::VERB_POST;
			$this->method="addtocontainer/".$containerID;
		}else{
			throw new \Exception("the ID of the Container must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemToCollection(int $collectionID=0):void{
		if($collectionID>0){
			$this->verb=defaults::VERB_POST;
			$this->method="addtocollection/".$collectionID;
		}else{
			throw new \Exception("the ID of the Collection must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemToSet(int $setID=0, string $purpose=''):void{
		if($setID>0){
			$this->verb=defaults::VERB_POST;
			$this->method="addtoset/".$setID;
			if(!empty($purpose)){
				$this->getParameters()->set("purpose",$purpose);
			}
		}else{
			throw new \Exception("the ID of the Set must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemToRack(int $rackID=0, string $purpose=''):void{
		if($rackID>0){
			$this->verb=defaults::VERB_POST;
			$this->method="addtorack/".$rackID;
			if(!empty($purpose)){
				$this->getParameters()->set("purpose",$purpose);
			}
		}else{
			throw new \Exception("the ID of the Rack must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addItemToBundle(int $bundleID=0):void{
		if($bundleID>0){
			$this->verb=defaults::VERB_POST;
			$this->method="addtobundle/".$bundleID;
		}else{
			throw new \Exception("the ID of the Bundle must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeItemFromContainer(int $containerID=0):void{
		if($containerID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removefromcontainer/".$containerID;
		}else{
			throw new \Exception("the ID of the Container must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeItemFromCollection(int $collectionID=0):void{
		if($collectionID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removefromcollection/".$collectionID;
		}else{
			throw new \Exception("the ID of the Collection must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeItemFromSet(int $setID=0):void{
		if($setID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removefromset/".$setID;
		}else{
			throw new \Exception("the ID of the Set must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeItemFromRack(int $rackID=0):void{
		if($rackID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removefromrack/".$rackID;
		}else{
			throw new \Exception("the ID of the Rack must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeItemFromBundle(int $bundleID=0):void{
		if($bundleID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removefrombundle/".$bundleID;
		}else{
			throw new \Exception("the ID of the Bundle must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectLinkToItem(int $linkID=0):void{
		if($linkID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectlink/".$linkID;
		}else{
			throw new \Exception("the ID of the Link must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectFileToItem(int $fileID=0):void{
		if($fileID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectfile/".$fileID;
		}else{
			throw new \Exception("the ID of the File must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectPersonToItem(int $personID=0, string $purpose=''):void{
		if($personID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectperson/".$personID;
			if(!empty($purpose)){
				$this->getParameters()->set("purpose",$purpose);
			}
		}else{
			throw new \Exception("the ID of the Person must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectGroupToItem(int $groupID=0, string $purpose=''):void{
		if($groupID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectgroup/".$groupID;
			if(!empty($purpose)){
				$this->getParameters()->set("purpose",$purpose);
			}
		}else{
			throw new \Exception("the ID of the Group must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectShowToItem(int $showID=0):void{
		if($showID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectshow/".$showID;
		}else{
			throw new \Exception("the ID of the Show must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectPlaceToItem(int $placeID=0):void{
		if($placeID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectplace/".$placeID;
		}else{
			throw new \Exception("the ID of the Place must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectProductToItem(int $productID=0):void{
		if($productID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectproduct/".$productID;
		}else{
			throw new \Exception("the ID of the Product must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeLinkFromItem(int $linkID=0):void{
		if($linkID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removelink/".$linkID;
		}else{
			throw new \Exception("the ID of the Link must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeFileFromItem(int $fileID=0):void{
		if($fileID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removefile/".$fileID;
		}else{
			throw new \Exception("the ID of the File must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removePersonFromItem(int $personID=0):void{
		if($personID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removeperson/".$personID;
		}else{
			throw new \Exception("the ID of the Person must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeGroupFromItem(int $groupID=0):void{
		if($groupID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removegroup/".$groupID;
		}else{
			throw new \Exception("the ID of the Group must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeShowFromItem(int $showID=0):void{
		if($showID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removeshow/".$showID;
		}else{
			throw new \Exception("the ID of the Show must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removePlaceFromItem(int $placeID=0):void{
		if($placeID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removeplace/".$placeID;
		}else{
			throw new \Exception("the ID of the Place must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeProductFromItem(int $productID=0):void{
		if($productID>0){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removeproduct/".$productID;
		}else{
			throw new \Exception("the ID of the Product must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCover(string $url="",string $description="",string $copyright="",bool $isAIGenerated=FALSE,string $assetLanguage="",float $fromTime=0):void{
		$this->handleCover("cover",$url,$description,$copyright,$assetLanguage,$isAIGenerated,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverAlternative(string $url="",string $description="",string $copyright="",bool $isAIGenerated=FALSE,string $assetLanguage="",float $fromTime=0):void{
		$this->handleCover("alternativecover",$url,$description,$copyright,$assetLanguage,$isAIGenerated,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverABTest(string $url="",string $description="",string $copyright="",bool $isAIGenerated=FALSE,string $assetLanguage="",float $fromTime=0):void{
		$this->handleCover("abtestalternative",$url,$description,$copyright,$assetLanguage,$isAIGenerated,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverActionShot(string $url="",string $description="",string $copyright="",bool $isAIGenerated=FALSE,string $assetLanguage="",float $fromTime=0):void{
		$this->handleCover("actionshot",$url,$description,$copyright,$assetLanguage,$isAIGenerated,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverQuad(string $url="",string $description="",string $copyright="",bool $isAIGenerated=FALSE,string $assetLanguage="",float $fromTime=0):void{
		$this->handleCover("quadcover",$url,$description,$copyright,$assetLanguage,$isAIGenerated,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverBanner(string $url="",string $assetLanguage="",string $description="",string $copyright="",bool $isAIGenerated=FALSE,float $fromTime=0):void{
		$this->handleCover("banner",$url,$description,$copyright,$assetLanguage,$isAIGenerated,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverArtwork(string $url="",string $assetLanguage="",string $description="",string $copyright="",bool $isAIGenerated=FALSE):void{
		$this->handleCover("artwork",$url,$description,$copyright,$assetLanguage,$isAIGenerated);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addTextTrackFromURL(string $url="",string $language="",string $title="",string $role=texttrackroles::ROLE_SUBTITLES):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			if(substr($url,0,4)=="http"){
				$this->verb=defaults::VERB_POST;
				$this->method="texttrackfromurl";
				$this->getParameters()->set("url",$url);
				if(strlen($language)==2){
					$this->getParameters()->set("language",$language);
				}else{
					throw new \Exception("language must be given in 2-Letter-Code");
				}
				if(!empty($title)){
					$this->getParameters()->set("title",$title);
				}
				if(!empty($role)){
					$this->getParameters()->set("role",$role);
				}
			}else{
				throw new \Exception("a valid TextTrack URL is missing.");
			}
		}else{
			throw new \Exception("Streamtype must be in video,audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addTextTrackFromSpeech():void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			$this->verb=defaults::VERB_POST;
			$this->method="texttrackfromspeech";
		}else{
			throw new \Exception("Streamtype must be in video,audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function translateTextTrackTo(string $targetLanguage="",string $role=texttrackroles::ROLE_SUBTITLES):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			if(strlen($targetLanguage)==2){
				$this->verb=defaults::VERB_POST;
				$this->method="translatetexttrackto/".$targetLanguage;
				if(!empty($role)){
					$this->getParameters()->set("role",$role);
				}
			}else{
				throw new \Exception("Target Language must be given in 2-Letter-Code");
			}
		}else{
			throw new \Exception("Streamtype must be in video,audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeTextTrack(string $language="",string $role=texttrackroles::ROLE_SUBTITLES):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			if(strlen($language)==2){
				$this->verb=defaults::VERB_DELETE;
				$this->method="removetexttrack";
				$this->getParameters()->set("language",$language);
				$this->getParameters()->set("role",$role);
			}else{
				throw new \Exception("Language must be given in 2-Letter-Code");
			}
		}else{
			throw new \Exception("Streamtype must be in video,audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function translateMetadataTo(string $language):void{
		if(strlen($language)==2){
			$this->verb=defaults::VERB_POST;
			$this->method="translateto/".$language;
		}else{
			throw new \Exception("Language must be given in 2-Letter-Code");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addTranslation(string $language,string $title="",string $subtitle="",string $teaser="",string $description="", string $orderhint=""):void{
		if(strlen($language)==2){
			$this->verb=defaults::VERB_POST;
			$this->method="addtranslation";
			$this->getParameters()->set("language",$language);
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(!empty($subtitle)){
				$this->getParameters()->set("subtitle",$subtitle);
			}
			if(!empty($teaser)){
				$this->getParameters()->set("teaser",$teaser);
			}
			if(!empty($description)){
				$this->getParameters()->set(($this->streamtype==streamtypes::ARTICLE?"textcontent":"description"),$description);
			}
			if(!empty($orderhint)){
				$this->getParameters()->set("orderhint",$orderhint);
			}
		}else{
			throw new \Exception("Language must be given in 2-Letter-Code");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function updateTranslation(string $language,string $title="",string $subtitle="",string $teaser="",string $description="", string $orderhint=""):void{
		if(strlen($language)==2){
			$this->verb=defaults::VERB_PUT;
			$this->method="updatetranslation";
			$this->getParameters()->set("language",$language);
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(!empty($subtitle)){
				$this->getParameters()->set("subtitle",$subtitle);
			}
			if(!empty($teaser)){
				$this->getParameters()->set("teaser",$teaser);
			}
			if(!empty($description)){
				$this->getParameters()->set(($this->streamtype==streamtypes::ARTICLE?"textcontent":"description"),$description);
			}
			if(!empty($orderhint)){
				$this->getParameters()->set("orderhint",$orderhint);
			}
		}else{
			throw new \Exception("Language must be given in 2-Letter-Code");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeTranslation(string $language):void{
		if(strlen($language)==2){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removetranslation";
			$this->getParameters()->set("language",$language);
		}else{
			throw new \Exception("Language must be given in 2-Letter-Code");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addHotSpot(string $type,int $from,int $to, string $title,string $subtitle="",string $link="",string $detailTitle="",string $detailText="",bool $autoPosition=TRUE, int $xPos=0,int $yPos=0, int $maxWidth=0,int $linkedVideo=0,bool $showCover=TRUE,string $imageURL="",float $seekTarget=0):void{
		if((!empty($type))&&(in_array($type,hotspottypes::getAllTypes()))){
			$this->verb=defaults::VERB_POST;
			$this->method="addhotspot";
			$this->getParameters()->set("type",$type);
			if(!empty($from)){
				$this->getParameters()->set("from",$from);
			}
			if(!empty($to)){
				$this->getParameters()->set("to",$to);
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if($autoPosition){
				$this->getParameters()->set("autoPosition",1);
			}else{
				$this->getParameters()->set("autoPosition",0);
				$this->getParameters()->set("xPos",$xPos);
				$this->getParameters()->set("yPos",$yPos);
			}
			if($type==hotspottypes::LINK){
				if(!empty($link)){
					$this->getParameters()->set("link",$link);
				}else{
					throw new \Exception("A HotSpot of Type 'link' must have a link Target");
				}
			}
			if($type==hotspottypes::VIDEO){
				if(!empty($linkedVideo)){
					$this->getParameters()->set("linkedVideo",$linkedVideo);
					$this->getParameters()->set("showCover",($showCover?1:0));
				}else{
					throw new \Exception("A HotSpot of Type 'video' must have a linkedVideo");
				}
			}
			if($type==hotspottypes::SEEK){
				if(!empty($seekTarget)){
					$this->getParameters()->set("seekTarget",$seekTarget);
				}else{
					throw new \Exception("A HotSpot of Type 'seek' must have a seekTarget");
				}
			}
			if($type==hotspottypes::BANNER){
				if(!empty($imageURL)){
					$this->getParameters()->set("imageURL",$imageURL);
					if(!empty($maxWidth)){
						$this->getParameters()->set("maxWidth",$maxWidth);
					}
					if(!empty($link)){
						$this->getParameters()->set("link",$link);
					}
				}else{
					throw new \Exception("A HotSpot of Type 'banner' must give an imageURL");
				}
			}
			if($type==hotspottypes::INTERSTITIAL){
				if(!empty($detailTitle)){
					$this->getParameters()->set("detailTitle",$detailTitle);
				}
				if(!empty($detailText)){
					$this->getParameters()->set("detailText",$detailText);
				}else{
					throw new \Exception("A HotSpot of Type 'interstitial' must have a detailText");
				}
			}else if(!empty($subtitle)){
				$this->getParameters()->set("subtitle",$subtitle);
			}
		}else{
			throw new \Exception("Type must be in ",implode(", ",hotspottypes::getAllTypes()));
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function updateHotSpot(int $hotspotid,int $from=0,int $to=0, string $title="",string $subtitle="",string $link="",string $detailTitle="",string $detailText="",bool $autoPosition=TRUE, int $xPos=0,int $yPos=0, int $maxWidth=0,int $linkedVideo=0,bool $showCover=TRUE,string $imageURL="",float $seekTarget=0):void{
		if(!empty($hotspotid)){
			$this->verb=defaults::VERB_PUT;
			$this->method="updatehotspot";
			$this->getParameters()->set("hotspotid",$hotspotid);
			if(!empty($from)){
				$this->getParameters()->set("from",$from);
			}
			if(!empty($to)){
				$this->getParameters()->set("to",$to);
			}
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(!empty($subtitle)){
				$this->getParameters()->set("subtitle",$subtitle);
			}
			if(!empty($link)){
				$this->getParameters()->set("link",$link);
			}
			if(!empty($detailTitle)){
				$this->getParameters()->set("detailTitle",$detailTitle);
			}
			if(!empty($detailText)){
				$this->getParameters()->set("detailText",$detailText);
			}
			if(!empty($xPos)){
				$this->getParameters()->set("xPos",$xPos);
			}
			if(!empty($yPos)){
				$this->getParameters()->set("yPos",$yPos);
			}
			if(!empty($maxWidth)){
				$this->getParameters()->set("maxWidth",$maxWidth);
			}
			if(!empty($linkedVideo)){
				$this->getParameters()->set("linkedVideo",$linkedVideo);
			}
			if(!empty($imageURL)){
				$this->getParameters()->set("imageURL",$imageURL);
			}
			if(!empty($seekTarget)){
				$this->getParameters()->set("seekTarget",$seekTarget);
			}
			$this->getParameters()->set("autoPosition",($autoPosition?1:0));
			$this->getParameters()->set("showCover",($showCover?1:0));
		}else{
			throw new \Exception("HotSpot ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeHotSpot(int $hotspotid):void{
		if(!empty($hotspotid)){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removehotspot";
			$this->getParameters()->set("hotspotid",$hotspotid);
		}else{
			throw new \Exception("HotSpot ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addLicenseNote(string $note):void{
		if(!empty($note)){
			$this->verb=defaults::VERB_POST;
			$this->method="addlicensenote";
			$this->getParameters()->set("note",$note);
		}else{
			throw new \Exception("Note cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeLicenseNote(int $licensenoteid):void{
		if(!empty($licensenoteid)){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removelicensenote";
			$this->getParameters()->set("licensenoteid",$licensenoteid);
		}else{
			throw new \Exception("LicenseNote ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addAward(string $award,string $category="",string $date="",string $state=""):void{
		if(!empty($award)){
			$this->verb=defaults::VERB_POST;
			$this->method="addaward";
			$this->getParameters()->set("award",$award);
			if(!empty($category)){
				$this->getParameters()->set("category",$category);
			}
			if(!empty($state)){
				if(in_array($state,awardstates::getAllTypes())){
					$this->getParameters()->set("state",$state);
				}else{
					throw new \Exception("state must be in ".implode(",",awardstates::getAllTypes()));
				}
			}
			if(!empty($date)){
				if(tools::dateIsValid($date)){
					$this->getParameters()->set("date",$date);
				}else{
					throw new \Exception("Date must be in YYYY-MM-DD format");
				}
			}
		}else{
			throw new \Exception("Award cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function updateAward(int $awardid,string $award,string $category="",string $date="",string $state=""):void{
		if(!empty($awardid)){
			$this->verb=defaults::VERB_PUT;
			$this->method="updateaward";
			$this->getParameters()->set("awardid",$awardid);
			if(!empty($award)){
				$this->getParameters()->set("award",$award);
			}
			if(!empty($category)){
				$this->getParameters()->set("category",$category);
			}
			if(!empty($state)){
				if(in_array($state,awardstates::getAllTypes())){
					$this->getParameters()->set("state",$state);
				}else{
					throw new \Exception("state must be in ".implode(",",awardstates::getAllTypes()));
				}
			}
			if(!empty($date)){
				if(tools::dateIsValid($date)){
					$this->getParameters()->set("date",$date);
				}else{
					throw new \Exception("Date must be in YYYY-MM-DD format");
				}
			}
		}else{
			throw new \Exception("Award ID cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function removeAward(int $awardid):void{
		if(!empty($awardid)){
			$this->verb=defaults::VERB_DELETE;
			$this->method="removeaward";
			$this->getParameters()->set("awardid",$awardid);
		}else{
			throw new \Exception("Award ID cant be empty");
		}
	}
}