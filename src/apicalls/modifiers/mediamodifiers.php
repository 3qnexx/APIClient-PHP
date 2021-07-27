<?php

namespace nexxomnia\apicalls\modifiers;

use nexxomnia\internals\modifiers;

class mediamodifiers extends modifiers{

	public function __construct(){
		parent::__construct();
	}

	public function addGeoDetails():void{
		$this->params['addGeoDetails']=1;
	}

	public function addPersonDetails():void{
		$this->params['addPersonDetails']=1;
	}

	public function addShowDetails():void{
		$this->params['addShowDetails']=1;
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

	public function addItemDetails():void{
		$this->params['addItemDetails']=1;
	}

	public function addTrailerDetails():void{
		$this->params['addTrailerDetails']=1;
	}

	public function addFaceDetails():void{
		$this->params['addFaceDetails']=1;
	}

	public function addPostcastDetails():void{
		$this->params['addPostcastDetails']=1;
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

	public function addLinkedMedia():void{
		$this->params['addLinkedMedia']=1;
	}

	public function addComments(bool $onlyFromLoggedInUser=FALSE):void{
		$this->params['addComments']=1;
		if($onlyFromLoggedInUser){
			$this->params['addCommentsFromLoggedinUserOnly']=1;
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

	public function addParentReferences():void{
		$this->params['addParentReferences']=1;
	}

	public function addTranslations():void{
		$this->params['addTranslations']=1;
	}

	public function addItemData():void{
		$this->params['addItemData']=1;
	}

	public function addReferencingMedia():void{
		$this->params['addReferencingMedia']=1;
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

	public function addFileDetails():void{
		$this->params['addFileDetails']=1;
	}

	public function addStreamingURLs():void{
		$this->params['addStreamingURLs']=1;
	}

	public function addFeatures():void{
		$this->params['addFeatures']=1;
	}

	public function addInsights():void{
		$this->params['addInsights']=1;
	}

	public function addScenes():void{
		$this->params['addScenes']=1;
	}

	public function addChapters():void{
		$this->params['addChapters']=1;
	}

	public function addHotSpots():void{
		$this->params['addHotSpots']=1;
	}

	public function addBumpers():void{
		$this->params['addBumpers']=1;
	}

	public function addVariantDetails():void{
		$this->params['addVariantDetails']=1;
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