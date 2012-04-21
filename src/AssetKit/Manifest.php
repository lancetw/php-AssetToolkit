<?php
namespace AssetKit;
use ZipArchive;
use Exception;

class Manifest
{
    public $stash;
    public $file;

    public function __construct($file)
    {
        $this->file = $file;
        $this->stash = yaml_parse(file_get_contents($file));
        $this->dir = dirname(realpath($file));
    }

    public function initResource()
    {
        if( ! isset($this->stash['resource']) )
            return false;

        $r = $this->stash['resource'];
        if( isset($r['url']) ) {
            $url = $r['url'];

            $filename = basename($url);
            $targetFile = $this->dir . DIRECTORY_SEPARATOR . $filename;
            system("curl --location $url > " . $targetFile );

            if( isset($r['zip']) ) {
                $zip = new ZipArchive;
                if( $zip->open( $targetFile ) === TRUE ) {
                    echo "Extracting to {$this->dir}\n";
                    $zip->extractTo( $this->dir );
                    $zip->close();
                    unlink( $targetFile );
                }
                else {
                    throw new Exception('Zip fail');
                }
            }
        }
        elseif( isset($r['git']) ) {
            $url = $r['git'];
            $target = $this->dir . DIRECTORY_SEPARATOR . basename($url,'.git');
            system("git clone $url $target");
        }
    }
}



