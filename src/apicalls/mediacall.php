<?php
namespace nexxomnia\apicalls;

use nexxomnia\apicalls\modifiers\mediamodifiers;
use nexxomnia\apicalls\parameters\mediaparameters;
use nexxomnia\enums\defaults;
use nexxomnia\enums\geoquerymodes;
use nexxomnia\enums\querymodes;
use nexxomnia\enums\streamtypes;

class mediacall extends \nexxomnia\internals\apicall{

	private string $streamtype;
	private string $method;

	public function __construct(string $streamtype=streamtypes::VIDEO){
		parent::__construct();
		$this->modifiers=new mediamodifiers();
		$this->parameters=new mediaparameters();
		$this->setStreamtype($streamtype);
		$this->all();
	}

	public function getModifiers():?mediamodifiers{
		return($this->modifiers);
	}

	public function getParameters():?mediaparameters{
		return($this->parameters);
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

	public function getPath():string{
		$this->path=streamtypes::getPluralizedStreamtype($this->streamtype)."/".$this->method;
		return(parent::getPath());
	}

	private function verifyParameter($method, int $param,array $streamtypes,bool $ignoreID=FALSE):void{
		if(in_array($this->streamtype,$streamtypes)){
			if($ignoreID){
				$this->method=$method;
			}else if($param>0){
				$this->method=$method."/".$param;
			}else{
				throw new \Exception("Media id is necessary");
			}
		}else{
			throw new \Exception("only valid for Streamtypes ".implode(",",$streamtypes));
		}
	}

	public function byID(int $id):void{
		$this->method="byid/".$id;
	}

	public function byHash(string $hash):void{
		$this->method="byhash/".$hash;
	}

	public function byRefNr(string $refnr):void{
		$this->method="byrefnr/".$refnr;
	}

	public function bySlug(string $slug):void{
		$this->method="byslug/".$slug;
	}

	public function byCodename(string $codename):void{
		$this->method="bycodename/".$codename;
	}

	public function byRemoteReference(string $ref):void{
		$this->method="byremotereference/".$ref;
	}

	public function byGlobalID(int $id):void{
		$this->verifyParameter("byglobalid",$id,[streamtypes::ALLMEDIA]);
	}

	public function all():void{
		$this->method="all";
	}

	public function latest():void{
		$this->method="latest";
	}

	public function picked():void{
		$this->method="picked";
	}

	public function evergreens():void{
		$this->method="evergreens";
	}

	public function forKids():void{
		$this->method="forkids";
	}

	public function random():void{
		$this->method="random";
	}

	public function expiring():void{
		$this->method="expiring";
	}

	public function comingSoon():void{
		$this->method="comingsoon";
	}

	public function mostActive(int $timeFrame=5):void{
		$this->method="mostactive";
		$this->getParameters()->set("timeframe",$timeFrame);
	}

	public function mostActiveExternal(int $timeFrame=5):void{
		$this->method="mostactiveexternal";
		$this->getParameters()->set("timeframe",$timeFrame);
	}

	public function topItems():void{
		$this->method="topitems";
	}

	public function topItemsExternal():void{
		$this->method="topitemsexternal";
	}

	public function bestRated(int $timeFrame=5):void{
		$this->method="bestrated";
		$this->getParameters()->set("timeframe",$timeFrame);
	}

	public function mostLiked(int $timeFrame=5):void{
		$this->method="mostliked";
		$this->getParameters()->set("timeframe",$timeFrame);
	}

	public function mostReacted(int $timeFrame=5):void{
		$this->method="mostreacted";
		$this->getParameters()->set("timeframe",$timeFrame);
	}

	public function mostCommented(int $timeFrame=5):void{
		$this->method="mostcommented";
		$this->getParameters()->set("timeframe",$timeFrame);
	}

	public function channelOverview():void{
		$this->method="channeloverview";
	}

	public function formatOverview():void{
		$this->method="formatoverview";
	}

	public function commentsFor(int $id):void{
		$this->method="commentsfor/".$id;
	}

	public function externalCommentsFor(int $id):void{
		$this->method="externalcommentsfor/".$id;
	}

	public function recommendationsFor(int $id):void{
		$this->method="recommendationsfor/".$id;
	}

	public function recommendationsForContext(string $context,string $content="",string $language="",string $title="",string $subtitle=""):void{
		$this->method="recommendationsforcontext/".$context;
		if(!empty($content)){
			$this->verb=defaults::VERB_POST;
			$this->getParameters()->set("content",$content);
			if(!empty($title)){
				$this->getParameters()->set("title",$title);
			}
			if(!empty($subtitle)){
				$this->getParameters()->set("subtitle",$subtitle);
			}
		}
		if(strlen($language)==2){
			$this->getParameters()->set("language",$language);
		}
	}

	public function similarsFor(int $id):void{
		$this->method="similarsfor/".$id;
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function captionsFor(int $id):void{
		$this->verifyParameter("captionsfor",$id,[streamtypes::VIDEO,streamtypes::AUDIO,streamtypes::ALLMEDIA]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function stitchedManifestFor(int $id):void{
		$this->verifyParameter("stitchedmanifestfor",$id,[streamtypes::PLAYLIST,streamtypes::SET,streamtypes::COLLECTION,streamtypes::ALLMEDIA]);
	}

	/**
	 * @throws \Exception on invalid QueryMode
	 */
	public function byQuery(string $query,string $queryMode=querymodes::FULLTEXT,string $queryFields="",int $minimalQueryScore=0,$includeSubsctringMatches=FALSE,$skipReporting=FALSE):void{
		if(!empty($query)){
			if(in_array($queryMode,querymodes::getAllTypes())){
				$this->method="byquery/".urlencode($query);
				$this->getParameters()->set("querymode",$queryMode);
				if(!empty($queryFields)){
					$this->getParameters()->set("queryfields",$queryFields);
				}
				if($queryMode==querymodes::FULLTEXT){
					if(!empty($minimalQueryScore)){
						$this->getParameters()->set("minimalQueryScore",$minimalQueryScore);
					}
				}else if($includeSubsctringMatches){
					$this->getParameters()->set("includeSubstringMatches",1);
				}
				if($skipReporting){
					$this->getParameters()->set("skipReporting",1);
				}
			}else{
				throw new \Exception("QueryMode is not supported");
			}
		}else{
			throw new \Exception("QueryMode cant be empty");
		}
	}

	/**
	 * @throws \Exception on invalid GeoMode
	 */
	public function byGeo(string $geoQuery,string $geoMode=geoquerymodes::PLACE,int $distance=10):void{
		if(!empty($geoQuery)){
			if(in_array($geoMode,geoquerymodes::getAllTypes())){
				$this->method="bygeo/".urlencode($geoQuery);
				$this->getParameters()->set("geomode",$geoMode);
				$this->getParameters()->set("distance",$distance);
			}else{
				throw new \Exception("GeoMode not supported");
			}
		}else{
			throw new \Exception("GeoMode cant be empty");
		}
	}

	/**
	 * @throws \Exception on empty List
	 */
	public function byItemList(array $items):void{
		if(!empty($items)){
			$this->method="byitemlist/".implode(",",$items);
		}else{
			throw new \Exception("Items cant be empty");
		}
	}

	public function byStudio(int $studio):void{
		$this->method="bystudio/".$studio;
	}

	public function byGenre(int $genre):void{
		$this->method="bygenre/".$genre;
	}

	public function byTag(string $name,int $id):void{
		if(!empty($name)){
			$this->method="bytag/".urlencode($name);
		}else if(!empty($id)){
			$this->method="bytagid/".$id;
		}
	}

	public function byPerson(string $name,int $id):void{
		if(!empty($name)){
			$this->method="byperson/".urlencode($name);
		}else if(!empty($id)){
			$this->method="bypersonid/".$id;
		}
	}

	public function byGroup(string $name,int $id):void{
		if(!empty($name)){
			$this->method="bygroup/".urlencode($name);
		}else if(!empty($id)){
			$this->method="bygroupid/".$id;
		}
	}

	public function byShow(string $name,int $id):void{
		if(!empty($name)){
			$this->method="byshow/".urlencode($name);
		}else if(!empty($id)){
			$this->method="byshowid/".$id;
		}
	}

	public function byPlace(string $name,int $id):void{
		if(!empty($name)){
			$this->method="byplace/".urlencode($name);
		}else if(!empty($id)){
			$this->method="byplaceid/".$id;
		}
	}

	public function byUser(int $userid):void{
		$this->method="byuser/".$userid;
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byPlaylist(int $playlistid):void{
		$this->verifyParameter("byplaylist",$playlistid,[streamtypes::VIDEO]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byLiveLink(int $liveLinkID):void{
		$this->verifyParameter("bylivelink",$liveLinkID,[streamtypes::VIDEO]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byAudioAlbum(int $albumid):void{
		$this->verifyParameter("byaudioalbum",$albumid,[streamtypes::AUDIO]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byAlbum(int $albumid):void{
		$this->verifyParameter("byalbum",$albumid,[streamtypes::IMAGE]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byFolder(int $folderid):void{
		$this->verifyParameter("byfolder",$folderid,[streamtypes::FILE]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byMagazine(int $magazineid):void{
		$this->verifyParameter("bymagazine",$magazineid,[streamtypes::ARTICLE]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function byVideo(int $videoid):void{
		$this->verifyParameter("byvideo",$videoid,[streamtypes::SCENE]);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function today():void{
		$this->verifyParameter("today",0,[streamtypes::EVENT],TRUE);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function thisWeek():void{
		$this->verifyParameter("thisweek",0,[streamtypes::EVENT],TRUE);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function thisMonth():void{
		$this->verifyParameter("thismonth",0,[streamtypes::EVENT],TRUE);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function nextDays(int $days):void{
		$this->getParameters()->set('days',$days);
		$this->verifyParameter("nextdays",0,[streamtypes::EVENT],TRUE);
	}

	/**
	 * @throws \Exception on invalid Streamtype or Media ID
	 */
	public function nextInSeries(int $videoid):void{
		$this->verifyParameter("nextinseries",$videoid,[streamtypes::VIDEO]);
	}

	public function userHistory(bool $excludeCompleted=FALSE):void{
		$this->method="userhistory";
		if($excludeCompleted){
			$this->getParameters()->set("excludeCompleted",1);
		}
	}

	public function userFavourites():void{
		$this->method="userfavourites";
	}

	public function userLikes():void{
		$this->method="userlikes";
	}

	public function userRatings():void{
		$this->method="userratings";
	}

	public function userReactions():void{
		$this->method="userreactions";
	}

	public function userComments():void{
		$this->method="usercomments";
	}

	public function userUploads():void{
		$this->method="useruploads";
	}
}