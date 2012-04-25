<?php

class AssetTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $config = new AssetKit\Config('.tests_assetkit');
        $config->public = 'public';

        $loader = new AssetKit\AssetLoader($config);
        ok( $loader );

        $as = new AssetKit\Asset('assets/jquery-ui/manifest.yml');
        $as->config = $config;
        ok( $as );

        $config->addAsset( 'jquery-ui', $as );


        $as->install();

        is('/assets/jquery-ui', $as->getBaseUrl() );
        foreach( $as->getFileCollections() as $c ) {
            $paths = $c->getPublicPaths();
            ok( $paths );
            foreach( $paths as $p ) { 
                file_ok( $p );
            }

            $urls = $c->getPublicUrls();
            var_dump( $urls ); 
            ok( $paths );
        }

        $files = $as->createFileCollection();
        ok( $files );
        $files->addFile( 'assets/jssha/jsSHA/src/sha1.js' );
        $files->addFile( 'assets/jssha/jsSHA/src/sha256.js' );
        $files->addFilter( 'yui_js' );
        $mtime = $files->getLastModifiedTime();
        ok( $mtime );
        

#          $files->addFile( '...' );
#          $files->addFile( '...' );
#          $files->addFilter( '...' );
#          $files->addCompressor( '...' );

    }
}

