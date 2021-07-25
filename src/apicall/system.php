<?php

namespace nexxOMNIA\apicall;

use nexxOMNIA\enums\streamtypes;

class system extends \nexxOMNIA\internal\apicall{

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

	public function youtubeCategories():void{
		$this->path.="youtubecategories";
	}

	public function facebookCategories():void{
		$this->path.="facebookcategories";
	}

	public function rokuCategories():void{
		$this->path.="rokucategories";
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

	public function editableAttributesFor(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->path.="editableattributesfor/".$streamtype;
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	public function editableRestrictionsFor(string $streamtype):void{
		if(in_array($streamtype,streamtypes::getAllTypes())){
			$this->path.="editablerestrictionsfor/".$streamtype;
		}else{
			throw new \Exception("Streamtype not supported");
		}
	}

	public function openOperations():void{
		$this->path.="openoperations";
	}

	public function operationStateFor(int $id):void{
		$this->path.="operationstate/".$id;
	}

}