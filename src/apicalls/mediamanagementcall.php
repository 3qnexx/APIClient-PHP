<?php
namespace nexxomnia\apicalls;

use nexxomnia\enums\ageclasses;
use nexxomnia\enums\autoorderattributes;
use nexxomnia\enums\contentmoderationaspects;
use nexxomnia\enums\defaults;
use nexxomnia\enums\exportparts;
use nexxomnia\enums\externalplatforms;
use nexxomnia\enums\externalstates;
use nexxomnia\enums\highlightvideopurposes;
use nexxomnia\enums\livesourcetypes;
use nexxomnia\enums\livestreamtypes;
use nexxomnia\enums\topicitemsources;
use nexxomnia\enums\querymodes;
use nexxomnia\enums\rejectactions;
use nexxomnia\enums\scenepurposes;
use nexxomnia\enums\streamtypes;

class mediamanagementcall extends \nexxomnia\internals\apicall{

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

	private function handleCover(string $method,string $url="",float $fromTime=0):void{
		if(substr($url,0,4)=="http"){
			$this->verb=defaults::VERB_POST;
			$this->method=$method;
			$this->getParameters()->set("url",$url);
		}else if(($fromTime>0)&&(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::SCENE,'variant']))){
			$this->verb=defaults::VERB_POST;
			$this->method=$method;
			$this->getParameters()->set("fromTime",$fromTime);
		}else{
			throw new \Exception("a valid Cover URL or TimeStamp (on Video Streamtypes only).");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
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

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createFromData(string $title,string $refnr="",string $codename="",?bool $autoPublish=NULL,string $personGender="",string $personType=""):void{
		if(!in_array($this->streamtype,streamtypes::getUploadableTypes())){
			if((!empty($codename))||(!in_array($this->streamtype,streamtypes::getContainerTypes()))){
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
				throw new \Exception("Container Streamtypes need a valid Codename");
			}
		}else{
			throw new \Exception("Streamtype cannot be in ".implode(", ",streamtypes::getUploadableTypes()));
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createFromTopic(string $title, string $topic="", string $itemSource="",int $duration=0,int $itemCount=0, string $searchMode="", array $searchFields=[], int $channel=0, int $format=0):void{
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
	public function createLiveStreamFromLiveLink(int $liveLinkID=0,string $title="",string $type=livestreamtypes::EVENT):void{
		if($liveLinkID>0){
			$this->setStreamtype(streamtypes::LIVE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromlivelink/".$liveLinkID;
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
		}else{
			throw new \Exception("the LiveLink ID must be given.");
		}
	}

	public function createLiveStreamFromAutoLiveLink(string $title="",string $type=livestreamtypes::EVENT,string $sourceType=livesourcetypes::RTMP,bool $enableDVR=FALSE):void{
		$this->setStreamtype(streamtypes::LIVE);
		$this->verb=defaults::VERB_POST;
		$this->method="fromautolivelink";
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
	public function createLiveStreamFromRemote(string $hlsURL,string $dashURL="",string $title="",string $type=livestreamtypes::EVENT):void{
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
		}else{
			throw new \Exception("a valid HLS URL must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createRadioFromLiveLink(int $liveLinkID=0,string $title="",string $type=livestreamtypes::EVENT):void{
		if($liveLinkID>0){
			$this->setStreamtype(streamtypes::RADIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromlivelink/".$liveLinkID;
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
		}else{
			throw new \Exception("the LiveLink ID must be given.");
		}
	}

	public function createRadioFromAutoLiveLink(string $title="",string $type=livestreamtypes::EVENT):void{
		$this->setStreamtype(streamtypes::RADIO);
		$this->verb=defaults::VERB_POST;
		$this->method="fromautolivelink";
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
	public function createRadioFromRemote(string $url,string $title="",string $type=livestreamtypes::EVENT):void{
		if((!empty($url))&&(substr($url,0,4)=="http")){
			$this->setStreamtype(streamtypes::RADIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromremote";
			$this->getParameters()->set("url",$url);
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(in_array($type,livestreamtypes::getAllTypes())){
				$this->getParameters()->set("type",$type);
			}
		}else{
			throw new \Exception("a valid URL must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createSceneFromVideo(int $videoID=0,string $title="",float $from=0,float $until=0,string $purpose=scenepurposes::CHAPTER):void{
		if($videoID>0){
			$this->setStreamtype(streamtypes::SCENE);
			$this->verb=defaults::VERB_POST;
			$this->method="fromvideo/".$videoID;
			$this->getParameters()->set("from",$from);
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
	public function createAudioFromText(string $text,string $language=""):void{
		if(!empty($text)){
			$this->setStreamtype(streamtypes::AUDIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromtext";
			$this->getParameters()->set("text",$text);
			if(strlen($language)==2){
				$this->getParameters()->set("language",$language);
			}
		}else{
			throw new \Exception("a non-empty Text must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function createAudioFromArticle(int $articleID=0,bool $includeTitle=TRUE,bool $includeSubtitle=FALSE,bool $includeTeaser=FALSE,bool $useAsRepresentation=FALSE):void{
		if($articleID>0){
			$this->setStreamtype(streamtypes::AUDIO);
			$this->verb=defaults::VERB_POST;
			$this->method="fromarticle/".$articleID;
			if($includeTitle){
				$this->getParameters()->set('includeTitle',1);
			}
			if($includeSubtitle){
				$this->getParameters()->set('includeSubtitle',1);
			}
			if($includeTeaser){
				$this->getParameters()->set('includeTeaser',1);
			}
			if($useAsRepresentation){
				$this->getParameters()->set('useAsRepresentation',1);
			}
		}else{
			throw new \Exception("the Article ID must be given.");
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
	public function exportItem(int $accountID,string $externalCategory="",string $externalState=externalstates::PUBLIC, string $postText="",int $publicationDate=0,int $inVariant=0):void{
		if($accountID>0){
			if(in_array($this->streamtype,streamtypes::getExportableTypes())){
				$this->verb=defaults::VERB_POST;
				$this->method="export";
				$this->getParameters()->set("account",$accountID);
				if(!empty($externalCategory)){
					$this->getParameters()->set("externalCategory",$externalCategory);
				}
				if(in_array($externalState,externalstates::getAllTypes())){
					$this->getParameters()->set("externalState",$externalState);
				}
				if(($publicationDate>0)&&($externalState==externalstates::PRIVATE)){
					$this->getParameters()->set("publicationDate",$publicationDate);
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
	public function addItemToSet(int $setID=0):void{
		if($setID>0){
			$this->verb=defaults::VERB_POST;
			$this->method="addtoset/".$setID;
		}else{
			throw new \Exception("the ID of the Set must be given.");
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
	public function connectPersonToItem(int $personID=0):void{
		if($personID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectperson/".$personID;
		}else{
			throw new \Exception("the ID of the Person must be given.");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function connectGroupToItem(int $groupID=0):void{
		if($groupID>0){
			$this->verb=defaults::VERB_PUT;
			$this->method="connectgroup/".$groupID;
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
	public function setItemCover(string $url="",float $fromTime=0):void{
		$this->handleCover("cover",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverAlternative(string $url="",float $fromTime=0):void{
		$this->handleCover("alternativecover",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverABTest(string $url="",float $fromTime=0):void{
		$this->handleCover("abtestalternative",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverActionShot(string $url="",float $fromTime=0):void{
		$this->handleCover("actionshot",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverQuad(string $url="",float $fromTime=0):void{
		$this->handleCover("quadcover",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverBanner(string $url="",float $fromTime=0):void{
		$this->handleCover("banner",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function setItemCoverFamilySafe(string $url="",float $fromTime=0):void{
		$this->handleCover("familysafe",$url,$fromTime);
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addCaptionsFromURL(string $url="",string $language="",string $title="",bool $withAudioDescription=FALSE):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			if(substr($url,0,4)=="http"){
				$this->verb=defaults::VERB_POST;
				$this->method="captionsfromurl";
				$this->getParameters()->set("url",$url);
				if(strlen($language)==2){
					$this->getParameters()->set("language",$language);
				}else{
					throw new \Exception("language must be given in 2-Letter-Code");
				}
				if(!empty($title)){
					$this->getParameters()->set("title",$title);
				}
				if($withAudioDescription){
					$this->getParameters()->set("withAudioDescription",1);
				}
			}else{
				throw new \Exception("a valid Caption URL is missing.");
			}
		}else{
			throw new \Exception("Streamtype must be in video,audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function addCaptionsFromSpeech():void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			$this->verb=defaults::VERB_POST;
			$this->method="captionsfromspeech";
		}else{
			throw new \Exception("Streamtype must be in video,audio");
		}
	}

	/**
	 * @throws \Exception on invalid Parameters
	 */
	public function translateCaptionsTo($targetLanguage=""):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			if(strlen($targetLanguage)==2){
				$this->verb=defaults::VERB_POST;
				$this->method="translatecaptionsto/".$targetLanguage;
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
	public function removeCaptions(string $language="",bool $withAudioDescription=FALSE):void{
		if(in_array($this->streamtype,[streamtypes::VIDEO,streamtypes::AUDIO])){
			if(strlen($language)==2){
				$this->verb=defaults::VERB_DELETE;
				$this->method="removecaptions";
				$this->getParameters()->set("language",$language);
				if($withAudioDescription){
					$this->getParameters()->set("withAudioDescription",1);
				}
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
}