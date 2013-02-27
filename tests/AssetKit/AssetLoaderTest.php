<?php

class AssetLoaderTest extends PHPUnit_Framework_TestCase
{


    public function testAssetLoader()
    {
        $configFile = "tests/.assetkit";
        if( file_exists($configFile) ) {
            unlink($configFile);
        }

        $config = new AssetKit\Config($configFile,array(  
            'cache' => true,
            'cache_id' => 'asset-loader-test',
            'cache_expiry' => 3600
        ));
        ok($config);

        // test force reload
        $config->setBaseUrl('/assets');
        $config->setBaseDir('tests/assets');
        $config->addAssetDirectory('vendor/assets');

        $loader = new AssetKit\AssetLoader($config);

        $assets = $config->getRegisteredAssets();
        ok( empty($asset) );
        $config->save();
        unlink($configFile);
    }


    /*
    public function setup()
    {
        $config = $this->getConfig();
        $loader = $this->getLoader($config);

        $jquery = $loader->load('jquery');
        $jqueryui = $loader->load( 'jquery-ui' );

        // install resource
        $updater = new \AssetKit\ResourceUpdater;
        $updater->update($jquery);
        $updater->update($jqueryui);

        $installer = new AssetKit\Installer;
        $installer->enableLog = false;
        $installer->install( $jquery );
        $installer->install( $jqueryui );
    }


    public function getLoader($config)
    {
        return new AssetKit\AssetLoader($config, array( 'assets','tests/assets' ) );
    }

    public function getConfig()
    {
        return new AssetKit\Config('.testassetkit');
    }

    public function testLoader()
    {
        $config = $this->getConfig();
        $loader = $this->getLoader($config);
        $asset = $loader->load('jquery-ui');
        ok( $asset );

        $collections = $asset->getCollections();
        foreach( $collections as $collection ) {
            $files = $collection->getFilePaths();
            ok( $files );
            foreach( $files as $file ) {
                file_exists($file);
            }
        }
        foreach( $collections as $collection ) {
            $content = $collection->getContent();
            ok( $content );
            ok( strlen( $content ) > 0 );
        }
    }

    public function testWriter()
    {
        $config = $this->getConfig();
        $loader = $this->getLoader($config);

        $writer = new AssetKit\AssetWriter( $config );
        $writer->enableCompressor = false;
        ok( $writer );

        $jquery = $loader->load('jquery');
        $jqueryui = $loader->load('jquery-ui');


        ok( $jquery );
        ok( $jqueryui );

        is( 'public/assets/jquery', $jquery->getPublicDir() );
        is( 'assets/jquery', $jquery->getSourceDir() );

        $installer = new AssetKit\Installer;
        $installer->enableLog = false;
        $installer->install( $jquery );
        $installer->install( $jqueryui );

        $assets = array();
        $assets[] = $jquery;
        $assets[] = $jqueryui;

        $manifest = $writer 
            ->name( 'app' )
            ->writeForProduction( $assets );

        ok( $manifest['javascripts'] );
        ok( $manifest['stylesheets'] );

        foreach( $manifest['javascripts'] as $file ) {
            ok( $file['url'] );
            file_ok( $file['path'] );
        }

        foreach( $manifest['stylesheets'] as $file ) {
            ok( $file['url'] );
            file_ok( $file['path'] );
        }


        $render = new AssetKit\IncludeRender;
        $html = $render->render( $manifest );

        like( '#<link rel="stylesheet" type="text/css"  href="assets/app-\w+.css"/>
<script type="text/javascript"  src="assets/app-\w+.js" > </script>#', $html );

    }

    function tearDown()
    {
#          $installer->uninstall( $jquery );
#          $installer->uninstall( $jqueryui );
    }
    */
}

