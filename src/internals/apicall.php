<?php

namespace nexxomnia\internals;

use nexxomnia\internals\modifiers;
use nexxomnia\internals\parameters;
use nexxomnia\enums\defaults;

class apicall{

	protected ?parameters $parameters=NULL;
	protected ?modifiers  $modifiers=NULL;

	protected string $verb;
	protected string $path;

	public function __construct(){
		$this->verb=defaults::VERB_GET;
		$this->parameters=new parameters();
		$this->modifiers=new modifiers();
	}

	public function getPath():string{
		return($this->path);
	}

	public function getVerb():string{
		return($this->verb);
	}

	public function setParameters(parameters $params):void{
		$this->parameters=$params;
	}

	public function setModifiers(modifiers $modifiers):void{
		$this->modifiers=$modifiers;
	}

	public function getParameters():?parameters{
		return($this->parameters);
	}

	public function getModifiers():?modifiers{
		return($this->modifiers);
	}

}