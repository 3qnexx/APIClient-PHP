<?php

namespace nexxomnia\apicalls\modifiers;

use nexxomnia\enums\texttrackformats;
use nexxomnia\enums\commentcontexts;
use nexxomnia\enums\connectedmediadetails;
use nexxomnia\internals\modifiers;

class mediamodifiers extends modifiers{

	public function __construct(){
		parent::__construct();
	}

	public function addGeoDetails():void{
		$this->params['addGeoDetails']=1;
	}

	public function addAuthorDetails():void{
		$this->params['addAuthorDetails']=1;
	}

	public function addStreamDetails():void{
		$this->params['addStreamDetails']=1;
	}

	public function addEmbedDetails():void{
		$this->params['addEmbedDetails']=1;
	}

	public function addStatusDetails():void{
		$this->params['addStatusDetails']=1;
	}

	public function addRestrictionDetails():void{
		$this->params['addRestrictionDetails']=1;
	}

	public function addAwards():void{
		$this->params['addAwards']=1;
	}

	public function addFaceDetails():void{
		$this->params['addFaceDetails']=1;
	}

	public function addPodcastDetails():void{
		$this->params['addPodcastDetails']=1;
	}

	public function addRenditionDetails():void{
		$this->params['addRenditionDetails']=1;
	}

	public function addTranscodingDetails():void{
		$this->params['addTranscodingDetails']=1;
	}

	public function addIngestDetails():void{
		$this->params['addIngestDetails']=1;
	}

	public function addInteractionOptions():void{
		$this->params['addInteractionOptions']=1;
	}

	/**
	 * @throws \Exception on invalid Comment Context
	 */
	public function addComments(string $context=commentcontexts::ALL):void{
		if(in_array($context,commentcontexts::getAllTypes())){
			$this->params['addComments']=$context;
		}else{
			throw new \Exception("Comment Context is unknown");
		}
	}

	public function addAnnotations():void{
		$this->params['addAnnotations']=1;
	}

	public function addStatistics():void{
		$this->params['addStatistics']=1;
	}

	public function addPaymentData():void{
		$this->params['addPaymentData']=1;
	}

	/**
	 * @throws \Exception on invalid connectedMediaDetails Format
	 */
	public function addConnectedMedia($options="all",$connectedMediaDetails=""):void{
		if(is_array($options)){
			$options=implode(",",$options);
		}
		$this->params['addConnectedMedia']=$options;
		if(!empty($connectedMediaDetails)){
			if(in_array($connectedMediaDetails,connectedmediadetails::getAllTypes())){
				$this->params['connectedMediaDetails']=$connectedMediaDetails;
			}else{
				throw new \Exception("Detail Level is unknown");
			}
		}
	}

	/**
	 * @throws \Exception on invalid parentMediaDetails Format
	 */
	public function addParentMedia($options="all",$parentMediaDetails=""):void{
		if(is_array($options)){
			$options=implode(",",$options);
		}
		$this->params['addParentMedia']=$options;
		if(!empty($parentMediaDetails)){
			if(in_array($parentMediaDetails,connectedmediadetails::getAllTypes())){
				$this->params['parentMediaDetails']=$parentMediaDetails;
			}else{
				throw new \Exception("Detail Level is unknown");
			}
		}
	}

	/**
	 * @throws \Exception on invalid childMediaDetails Format
	 */
	public function addChildMedia($options="all",$childMediaDetails=""):void{
		if(is_array($options)){
			$options=implode(",",$options);
		}
		$this->params['addChildMedia']=$options;
		if(!empty($childMediaDetails)){
			if(in_array($childMediaDetails,connectedmediadetails::getAllTypes())){
				$this->params['childMediaDetails']=$childMediaDetails;
			}else{
				throw new \Exception("Detail Level is unknown");
			}
		}
	}

	/**
	 * @throws \Exception on invalid referencingMediaDetails Format
	 */
	public function addReferencingMedia($options="all",$referencingMediaDetails=""):void{
		if(is_array($options)){
			$options=implode(",",$options);
		}
		$this->params['addReferencingMedia']=$options;
		if(!empty($referencingMediaDetails)){
			if(in_array($referencingMediaDetails,connectedmediadetails::getAllTypes())){
				$this->params['referencingMediaDetails']=$referencingMediaDetails;
			}else{
				throw new \Exception("Detail Level is unknown");
			}
		}
	}

	public function addMultiLanguageData():void{
		$this->params['addMultiLanguageData']=1;
	}

	public function addCustomAttributes():void{
		$this->params['addCustomAttributes']=1;
	}

	public function addExportDetails():void{
		$this->params['addExportDetails']=1;
	}

	public function addPreviewLinks():void{
		$this->params['addPreviewLinks']=1;
	}

	public function addBroadcastLinks():void{
		$this->params['addBroadcastLinks']=1;
	}

	public function addFileURLs():void{
		$this->params['addFileURLs']=1;
	}

	public function addStreamingURLs():void{
		$this->params['addStreamingURLs']=1;
	}

	public function addFeatures():void{
		$this->params['addFeatures']=1;
	}

	public function addInsights($options="all"):void{
		if(is_array($options)){
			$options=implode(",",$options);
		}
		$this->params['addInsights']=$options;
	}

	/**
	 * @throws \Exception on invalid CaptionFormat
	 */
	public function addTextTracks(string $format=texttrackformats::DATA):void{
		if(in_array($format,texttrackformats::getAllTypes())){
			$this->params['addTextTracks']=$format;
		}else{
			throw new \Exception("TextTrack Format string is unknown");
		}
	}

	public function addHotSpots():void{
		$this->params['addHotSpots']=1;
	}

	public function addBumpers():void{
		$this->params['addBumpers']=1;
	}

	//only valid for Persons
	public function addTaggedImages():void{
		$this->params['addTaggedImages']=1;
	}

	//only valid for Persons
	public function addTaggedVideos():void{
		$this->params['addTaggedVideos']=1;
	}

	//only valid for Series
	public function addSeasonList():void{
		$this->params['addSeasonList']=1;
	}

	//only valid for Series
	public function addEpisodesForSeason($season):void{
		if($season=='latest'){
			$this->params['addEpisodesForSeason']=$season;
		}else if(is_numeric($season)){
			$this->params['addEpisodesForSeason']=$season;
		}
	}
}