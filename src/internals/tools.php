<?php

namespace nexxomnia\internals;

use nexxomnia\enums\defaults;

class tools{

	public static function isManageCall(string $endpoint):bool{
		return(strpos($endpoint,defaults::API_KIND_MANAGE)===0);
	}

	public static function isProcessingCall(string $endpoint):bool{
		return(strpos($endpoint,defaults::API_KIND_PROCESSING)===0);
	}

	public static function isSessionCall(string $endpoint):bool{
		return(strpos($endpoint,defaults::API_KIND_SESSION)===0);
	}

	public static function isStatisticsCall(string $endpoint):bool{
		return(strpos($endpoint,defaults::API_KIND_STATISTICS)===0);
	}

	public static function isDomainCall(string $endpoint):bool{
		return(strpos($endpoint,defaults::API_KIND_DOMAIN)===0);
	}

	public static function isSystemCall(string $endpoint):bool{
		return(strpos($endpoint,defaults::API_KIND_SYSTEM)===0);
	}

	public static function isMediaCall(string $endpoint):bool{
		return((!self::isDomainCall($endpoint))&&(!self::isManageCall($endpoint))&&(!self::isSessionCall($endpoint))&&(!self::isStatisticsCall($endpoint))&&(!self::isSystemCall($endpoint))&&(!self::isProcessingCall($endpoint)));
	}

	public static function callShouldIncreaseTimeout(string $endpoint):bool{
		return((self::isManageCall($endpoint))||(self::isStatisticsCall($endpoint)));
	}

}