<?php

/**
 * @author      Enea Krähenbühl <inquiry@3n3a.ch>
 * @copyright   Copyright (c), 2022 Enea Krähenbühl
 * @license     MIT public license
 */
namespace M133;

/**
 * FileHandler
 * Wrapper for easy file handling
 */
class Filehandler {
    /**
     * @var string The path to the file which is to be worked on
     */
    private $filename = '';

    /**
     * @var Handle The active file handle
     */
    private $current_file;
        
    /**
     * __construct
     *
     * @param  string $filename Path to file
     * @return void
     */
    function __construct($filename=null) {
        $this->open($filename);
    }
    
    /**
     * open
     * Opens the $filename in r+ mode
     * @return void
     */
    public function open($filename=null) {
        if ( ! empty($filename) ) {
            $this->filename = $filename;
            $this->current_file = fopen($this->filename, 'r+') or die('Unable to open file');
        }
    }
    
    /**
     * read
     * Reads a file
     * @param  bool $reverse Outputs lines in reverse
     * @return void
     */
    public function read($filepath=null, $reverse=false) {
        if ( ! empty($filepath) ) {
            $this->open($filepath);
        }

        if($reverse) {
            return implode('', $this->read_reverse());
        } else {
            $file_size = filesize($this->filename);
            return fread( $this->current_file, $file_size);
        }
    }
    
    /**
     * read2arr
     * Reads a files' lines into an array
     * @return void
     */
    public function read2arr() {
        $out = array();

        while (!feof($this->current_file)) {
            array_push($out, fgets($this->current_file));
        }

        // move file ptr back to beginning, so others may use
        rewind( $this->current_file );

        return $out;
    }
    
    /**
     * read_html
     * Reads a file and outputs an HTML string with `<br>` between
     * the lines.
     * @param  bool $reverse Outputs lines in reverse
     * @return void
     */
    public function read_html($reverse=false) {
        if ($reverse) {
            $lines_arr = $this->read_reverse();
        } else {
            $lines_arr = $this->read2arr();
        }
        return implode( '<br>', $lines_arr );
    }
    
    /**
     * read_html_list
     * Output file's lines as an HTML List (without <ul></ul>)
     * @param  string $additional_tag Tag in list tag
     * @param  string $additional_atts Add attributes to tag (above) if includes `%1`
     * @param  bool $reverse Outputs lines in reverse
     * @return void
     */
    public function read_html_list($additional_tag=NULL, $additional_atts=NULL, $reverse=false) {
        $out = "";
        $lines = $this->read2arr($reverse);

        $additional_atts = !empty($additional_atts) ? $additional_atts : '';
        $additional_tag = !empty($additional_tag) ? 
            ["<$additional_tag $additional_atts>", "</$additional_tag>"] : ['', ''];

        foreach ($lines as $line) {
            if ( !empty($line) && $line != "\n" ) {
                $out .= "<li>" .
                    ( $additional_atts != '' ? str_replace('%1', $line, $additional_tag[0]) : $additional_tag[0])
                . $line . $additional_tag[1] . "</li>";
            }
        }

        return $out;
    }
        
    /**
     * read_reverse
     * Outputs reverse line array
     * @return void
     */
    public function read_reverse() {
        $lines_arr = $this->read2arr();
        return array_reverse($lines_arr);
    }
    
    /**
     * writeln
     * Appends a line to a file
     * @param  string $line
     * @return void
     */
    public function writeln($line) {
        fseek($this->current_file, -1, SEEK_END);
        fwrite($this->current_file,  PHP_EOL . $line . PHP_EOL);
        rewind($this->current_file);
    }
    
    /**
     * write
     * Replaces the contents of a file
     * @param  string $new_content
     * @return void
     */
    public function write( $new_content ) {

        // not directly modifying on handle
        // because didn't work
        fclose($this->current_file);

        $temp_f = fopen($this->filename, 'w');
        fwrite( $temp_f, $new_content );
        fclose($temp_f);

        $this->open();
    }

    function __destruct() {
        fclose($this->current_file);
    }
}