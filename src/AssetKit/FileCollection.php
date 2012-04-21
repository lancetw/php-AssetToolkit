<?php
namespace AssetKit;

class FileCollection
{

    public $filters = array();

    public $compressors = array();

    public $files = array();

	// save manifest object
    public $manifest;

    public function __construct()
    {

    }

    static function create_from_manfiest($manifest)
    {
        $assets = array();
        foreach( $manifest->stash['assets'] as $config ) {
            $asset = new self;
            if( isset($config['filters']) )
                $asset->filters = $config['filters'];

            if( isset($config['compressors']) )
                $asset->compressors = $config['compressors'];

            if( isset($config['files']) )
                $asset->files = $config['files'];

            $asset->manifest = $manifest;
            $assets[] = $asset;
        }
        return $assets;
    }

	public function getFiles()
	{
		$dir = $this->manifest->dir;
		$baseDir = $this->manifest->config->baseDir;
		return array_map( function($file) use($dir,$baseDir){ 
				return $baseDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
			}, $this->files );
	}

	public function getContent()
	{
		$files = $this->getFiles();
		$loader = $this->manifest->loader;
		if( $loader->enableCompressor ) {
			foreach( $this->compressors as $c ) {
				if( $compressor = $loader->getCompressor($c) ) {
					$compressor->dump( $files );
				}
			}


		}
	}

}



