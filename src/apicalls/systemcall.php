<?php
namespace nexxomnia\apicalls;

use nexxomnia\enums\streamtypes;

class systemcall extends \nexxomnia\internals\apicall{

	public function __construct(){
		parent::__construct();
		$this->path="system/";
	}

	public function videoGenres():void{
		$this->path.="videogenres";
	}

	public function audioGenres():void{
		$this->path.="audiogenres";
	}

	public function videoContentTypes():void{
		$this->path.="videocontenttypes";
	}

	public function imageContentTypes():void{
		$this->path.="imagecontenttypes";
	}

	public function youtubeCategories():void{
		$this->path.="youtubecategories";
	}

	public function facebookCategories():void{
		$this->path.="facebookcategories";
	}

	public function countryCodes():void{
		$this->path.="countrycodes";
	}

	public function languageCodes():void{
		$this->path.="languagecodes";
	}

	public function regionCodesForCountry(string $country):void{
		if(strlen($country)==2){
			$this->path.="regioncodesfor/".$country;
		}
	}

	public function personTypes():void{
		$this->path.="persontypes";
	}

	public function ttsVoices():void{
		$this->path.="ttsvoices";
	}

	/**
	 * @throws \Exception on invalid Streamtype
	 */
	public function editableAttributesFor(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->path.="editableattributesfor/".$streamtype;
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	/**
	 * @throws \Exception on invalid Streamtype
	 */
	public function editableRestrictionsFor(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->path.="editablerestrictionsfor/".$streamtype;
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	public function currentIncidents():void{
		$this->path.="currentincidents";
	}

}