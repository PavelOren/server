<?php
require_once __DIR__ . '/kBaseConfCache.php';
require_once __DIR__ . '/kMapCacheInterface.php';

class kFileSystemConf extends kBaseConfCache implements kMapCacheInterface
{
	function __construct()
	{
		if(!class_exists('Zend_Config_Ini'))
		{
			require_once 'Zend/Config/Exception.php';
			require_once 'Zend/Config/Ini.php';
		}
		parent::__construct();
	}

	public function getFileNames ($mapName , $hostname)
	{
		$configDir = kEnvironment::getConfigDir();
		$iniFiles = array();
		if ($mapName == 'local')
			$iniFiles[] = "$configDir/base.ini";
		$iniFiles[] = "$configDir/$mapName.ini";
		if($hostname)
		{
			$configPath = "$configDir/hosts";
			if ($mapName != 'local')
				$configPath .= "/$mapName";

			if(is_dir($configPath))
			{
				$localConfigFile = "$hostname.ini";
				$configDir = dir($configPath);
				while (false !== ($iniFile = $configDir->read()))
				{
					$iniFileMatch = str_replace('#', '*', $iniFile);
					if(!fnmatch($iniFileMatch, $localConfigFile))
						continue;
					$iniFiles[] = "$configPath/$iniFile";
				}
				$configDir->close();
			}
		}
		return $iniFiles;
	}

	protected function mergeMaps(array $mapNames, $isLocal)
	{
		$result = array();
		if ($isLocal)
			$result = kEnvironment::getEnvMap();
		foreach ($mapNames as $iniFile)
		{
			if(file_exists($iniFile))
			{
				$config = new Zend_Config_Ini($iniFile);
				if($result)
				{
					$result = kEnvironment::mergeConfigItem($result, $config->toArray());
				}
				else
				{
					$result = $config->toArray();
				}
			}
		}
		return $result;
	}

	public function load ($key, $mapName)
	{
		$hostname = $this->getHostName();
		return $this->loadByHostName($mapName,$hostname);
	}

	public function loadByHostName ($mapName , $hostname)
	{
		$iniFiles = $this->getFileNames ($mapName , $hostname);
		$this->orderMap($iniFiles);
		$mergedMaps = $this->mergeMaps($iniFiles,($mapName=='local'));
		return $mergedMaps;
	}

	public function store($key, $mapName, $map, $ttl = 0)
	{
		return true;
	}
}
