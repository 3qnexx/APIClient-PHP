<?php
namespace nexxomnia\apicalls;

use nexxomnia\enums\defaults;
use nexxomnia\apicalls\helpers\task;

class processingcall extends \nexxomnia\internals\apicall{

	public function __construct(){
		parent::__construct();
		$this->path="processing/";
	}

	/**
	 * @throws \Exception on invalid Task Array
	 */
	public function multiTask(array $tasks=[]):void{
		if(!empty($tasks)){
			$finals=[];
			foreach($tasks as $task){
				if($task instanceof task){
					if($task->isValid()){
						array_push($finals,$task->get());
					}
				}
			}
			if(!empty($finals)){
				$this->verb=defaults::VERB_POST;
				$this->path.="multitask";
				$this->getParameters()->set("tasks",json_encode($finals));
			}else{
				throw new \Exception("at least one valid Task must be given");
			}
		}else{
			throw new \Exception("at least one Task must be given");
		}
	}

	/**
	 * @throws \Exception on empty Page ID
	 */
	public function getPage($pageID=0):void{
		if(!empty($pageID)){
			$this->path.="page/".$pageID;
		}else{
			throw new \Exception("Page ID must be set");
		}
	}

	/**
	 * @throws \Exception on invalid Row ID
	 */
	public function getRow($rowID=0):void{
		if(!empty($rowID)){
			$this->path.="row/".$rowID;
		}else{
			throw new \Exception("Row ID must be set");
		}
	}

}