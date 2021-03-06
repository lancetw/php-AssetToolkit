<?php
namespace AssetToolkit;
use Exception;
use IteratorAggregate;

class Collection
    implements IteratorAggregate
{

    public $filters = array();

    public $compressors = array();

    public $files = array();

    public $asset;

    public $isJavascript;

    public $isStylesheet;

    public $isCoffeescript;

    public $content;

    public $filetype;

    // attributes for assets rendering
    public $attributes = array();

    // cache
    private $_lastmtime = 0;

    const FILETYPE_FILE   = 1;
    const FILETYPE_JS     = 2;
    const FILETYPE_CSS    = 3;
    const FILETYPE_SASS   = 4;
    const FILETYPE_SCSS   = 5;
    const FILETYPE_COFFEE = 6;

    /**
     * Return source path (with relative or absolute path)
     *
     * @param bool $absolute Should return absolute or relative.
     * @return string
     */
    public function getSourcePaths($absolute = false)
    {
        $dir = $this->asset->getSourceDir($absolute);
        return array_map(function($file) use ($dir) {
                return $dir . DIRECTORY_SEPARATOR . $file;
            }, $this->files);
    }


    /**
     * @return array return the collection file list
     */
    public function getFilePaths()
    {
        return $this->files;
    }

    public function addFile($path)
    {
        $this->files[] = $path;
        return $this;
    }


    public function hasCompressor($name)
    {
        return in_array( $name, $this->compressors );
    }

    public function hasFilter($name)
    {
        return in_array( $name, $this->filters );
    }

    public function getCompressors()
    {
        return $this->compressors;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function addFilter($filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function getLastModifiedTime()
    {
        if( $this->_lastmtime ) {
            return $this->_lastmtime;
        }

        if( ! empty($this->files) ) {
            $dir = $this->asset->getSourceDir(true);
            $mtimes = array();
            foreach( $this->files as $file ) {
                $filepath = $dir . DIRECTORY_SEPARATOR . $file;
                $mtimes[] = filemtime($filepath);
            }
            rsort($mtimes, SORT_NUMERIC);
            return $mtimes[0];
        }
    }

    public function setContent($content)
    {
        $this->content = $content;
    }


    public function getContent()
    {
        if( $this->content !== null ) {
            return $this->content;
        }

        $sourceDir = $this->asset->getSourceDir(true);
        $content = '';
        foreach( $this->getFilePaths() as $file ) {
            $abspath = $sourceDir . DIRECTORY_SEPARATOR . $file;

            if( ! file_exists($abspath) )
                throw new Exception("$abspath does not exist.");
            $content .= file_get_contents( $abspath );
        }
        return $this->content = $content;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getSourcePaths(true));
    }

}

