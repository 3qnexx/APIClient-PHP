<?php
namespace nexxomnia\enums;

class defaults extends \nexxomnia\internals\enum{

	const CLIENT_VERSION='1.5.5';

	const API_URL='api.nexx.cloud';
	const API_VERSION='3.1';

	const API_KIND_MEDIA='media';
	const API_KIND_DOMAIN='domain';
	const API_KIND_MANAGE='manage';
	const API_KIND_SESSION='session';
	const API_KIND_SYSTEM='system';
	const API_KIND_STATISTICS='statistics';
	const API_KIND_PROCESSING='processing';

	const VERB_GET="GET";
	const VERB_POST="POST";
	const VERB_PUT="PUT";
	const VERB_DELETE="DELETE";

	const MAX_RESULT_LIMIT=100;
	const MAX_RESULT_LIMIT_CHILDREN=250;
	const MAX_RESULT_LIMIT_STATISTICS=100000;

	public static function getAllVerbs():array{
		return([self::VERB_GET,self::VERB_POST,self::VERB_PUT,self::VERB_DELETE]);
	}

}