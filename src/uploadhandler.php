<?php
namespace nexxomnia;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use nexxomnia\enums\covercontexts;
use nexxomnia\enums\streamtypes;
use nexxomnia\result\result;

class uploadhandler{

	private ?apiclient $apiclient=NULL;

	public function __construct(?apiclient $apiclient=NULL){
		if($apiclient){
			$this->setAPIClient($apiclient);
		}
	}

	public function setAPIClient(apiclient $apiclient):void{
		$this->apiclient=$apiclient;
		$this->apiclient->setTimeout(30);
	}

	private function getConfig(string $localPath):?array{
		$config=NULL;
		$apicall=new apicalls\domaincall();
		$apicall->uploadConfiguration($localPath);
		$result=$this->apiclient->call($apicall);
		if($result->isSuccess()){
			$this->apiclient->log("UPLOAD CONFIGURATION RECEIVED SUCCESSFULLY.");
			$config=$result->getResult();
		}
		return($config);
	}

	private function doUpload(string $localPath,array $config):bool{
		$isSuccess=FALSE;
		$urlparts=parse_url($config['endpoint']);
		$uploadPath=explode("/",$urlparts['path']);

		$blobmanager=BlobRestProxy::createBlobService("BlobEndpoint=https://".$urlparts['host'].";SharedAccessSignature=".$config['token']);
		$handle=fopen($localPath, "r");
		try{
			$this->apiclient->log("UPLOADING FILE TO AZURE.");
			$blobmanager->createBlockBlob($uploadPath[1],$uploadPath[2]."/".$config['file'],$handle);
			$isSuccess=TRUE;

			if(($handle)&&(is_resource($handle))){
				fclose($handle);
			}
		}catch(\Exception $e){
			if(($handle)&&(is_resource($handle))){
				fclose($handle);
			}
			$this->apiclient->log("UPLOAD ERROR FROM BLOB: ".$e->getMessage(),\Psr\Log\LogLevel::ERROR);
		}
		return($isSuccess);
	}

	/**
	 * @throws \Exception on any Processing Error
	 */
	public function uploadMedia($localPath,$streamtype=streamtypes::VIDEO,bool $useQueue=TRUE,?bool $autoPublish=NULL, string $refnr="",int $queueStart=0, string $asVariantFor="", int $asVariantOf=0):?result{
		$media=NULL;
		if($this->apiclient){
			if(!$useQueue){
				$this->apiclient->setTimeout(300);
			}
			if((file_exists($localPath))&&(filesize($localPath)>100)&&(!is_dir($localPath))){
				$config=$this->getConfig($localPath);
				if($config){
					$success=$this->doUpload($localPath,$config);
					if($success){
						$uploadcall=new apicalls\mediamanagementcall();
						$uploadcall->setStreamtype($streamtype);
						try{
							$uploadcall->createFromURL($config['endpoint']."/".$config['file'],$useQueue,$autoPublish,$refnr,$queueStart,$asVariantFor,$asVariantOf);
							$uploadcall->getParameters()->set('filename',pathinfo($localPath)['basename']);
							$uploadresult=$this->apiclient->call($uploadcall);
							if($uploadresult->isSuccess()){
								$media=$uploadresult;
							}else{
								throw new \Exception("internal error.");
							}
						}catch(\Exception $e){
							throw new \Exception($e->getMessage());
						}
					}else{
						throw new \Exception("internal error.");
					}
				}else{
					throw new \Exception("internal error.");
				}
			}else{
				throw new \Exception("given Path must exist.");
			}
		}else{
			throw new \Exception("APIClient must be configured and ready.");
		}
		return($media);
	}

	/**
	 * @throws \Exception on any Processing Error
	 */
	public function replaceMedia($localPath,$streamtype=streamtypes::VIDEO,$mediaid=0):bool{
		$isSuccess=FALSE;
		if($this->apiclient){
			if((file_exists($localPath))&&(filesize($localPath)>100)&&(!is_dir($localPath))){
				if($mediaid>0){
					$config=$this->getConfig($localPath);
					if($config){
						$success=$this->doUpload($localPath,$config);
						if($success){
							try{
								$uploadcall=new apicalls\mediamanagementcall();
								$uploadcall->setItem($mediaid,$streamtype);
								$uploadcall->updateItemFile($config['endpoint']."/".$config['file']);
								$uploadresult=$this->apiclient->call($uploadcall);
								$isSuccess=$uploadresult->isSuccess();
							}catch(\Exception $e){
								throw new \Exception($e->getMessage());
							}
						}else{
							throw new \Exception("internal error.");
						}
					}else{
						throw new \Exception("internal error.");
					}
				}else{
					throw new \Exception("Media ID cant be empty.");
				}
			}else{
				throw new \Exception("given Path must exist.");
			}
		}else{
			throw new \Exception("APIClient must be configured and ready.");
		}
		return($isSuccess);
	}

	/**
	 * @throws \Exception on any Processing Error
	 */
	public function setMediaCover($localPath,$streamtype=streamtypes::VIDEO,$mediaid=0,string $coverContext=covercontexts::COVER):bool{
		$isSuccess=FALSE;
		if($this->apiclient){
			if((file_exists($localPath))&&(filesize($localPath)>100)&&(!is_dir($localPath))){
				if($mediaid>0){
					$config=$this->getConfig($localPath);
					if($config){
						$success=$this->doUpload($localPath,$config);
						if($success){
							try{
								$uploadcall=new apicalls\mediamanagementcall();
								$uploadcall->setItem($mediaid,$streamtype);
								$url=$config['endpoint']."/".$config['file'];
								switch($coverContext){
									case covercontexts::COVER:
										$uploadcall->setItemCover($url);
									break;
									case covercontexts::ALTERNATIVE:
										$uploadcall->setItemCoverAlternative($url);
									break;
									case covercontexts::ABTEST:
										$uploadcall->setItemCoverABTest($url);
									break;
									case covercontexts::ACTIONSHOT:
										$uploadcall->setItemCoverActionShot($url);
									break;
									case covercontexts::BANNER:
										$uploadcall->setItemCoverBanner($url);
									break;
									case covercontexts::QUAD:
										$uploadcall->setItemCoverQuad($url);
									break;
									case covercontexts::FAMILYSAFE:
										$uploadcall->setItemCoverFamilySafe($url);
									break;
								}
								$uploadresult=$this->apiclient->call($uploadcall);
								$isSuccess=$uploadresult->isSuccess();
							}catch(\Exception $e){
								throw new \Exception($e->getMessage());
							}
						}else{
							throw new \Exception("internal error.");
						}
					}else{
						throw new \Exception("internal error.");
					}
				}else{
					throw new \Exception("Media ID cant be empty.");
				}
			}else{
				throw new \Exception("given Path must exist.");
			}
		}else{
			throw new \Exception("APIClient must be configured and ready.");
		}
		return($isSuccess);
	}

	/**
	 * @throws \Exception on any Processing Error
	 */
	public function addMediaCaptions($localPath,$streamtype=streamtypes::VIDEO,$mediaid=0,string $language='',bool $withAudioDescription=FALSE):bool{
		$isSuccess=FALSE;
		if($this->apiclient){
			if((file_exists($localPath))&&(filesize($localPath)>100)&&(!is_dir($localPath))){
				if($mediaid>0){
					$config=$this->getConfig($localPath);
					if($config){
						$success=$this->doUpload($localPath,$config);
						if($success){
							$uploadcall=new apicalls\mediamanagementcall();
							$uploadcall->setItem($mediaid,$streamtype);
							try{
								try{
									$uploadcall->addCaptionsFromURL($config['endpoint']."/".$config['file'],$language,"",$withAudioDescription);
									$uploadresult=$this->apiclient->call($uploadcall);
									$isSuccess=$uploadresult->isSuccess();
								}catch(\Exception $e){
									throw new \Exception($e->getMessage());
								}
							}catch(\Exception $e){
								throw new \Exception("config error.");
							}
						}else{
							throw new \Exception("internal error.");
						}
					}else{
						throw new \Exception("internal error.");
					}
				}else{
					throw new \Exception("Media ID cant be empty.");
				}
			}else{
				throw new \Exception("given Path must exist.");
			}
		}else{
			throw new \Exception("APIClient must be configured and ready.");
		}
		return($isSuccess);
	}

}