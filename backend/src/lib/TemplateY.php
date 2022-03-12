<?php

/**
 * @author      Enea Krähenbühl <inquiry@3n3a.ch>
 * @copyright   Copyright (c), 2022 Enea Krähenbühl
 * @license     MIT public license
 */
namespace M133;

require_once __DIR__ . '/FileX.php';

/**
 * TemplateY
 * Wrapper for easy templating
 * @example An example template tag `{{name}}`
 */
class TemplateY {
 
    private $filepath = "";
    private $filehandler = NULL;
    private $file_contents = "";

    private $template_tag_re = '/{{(.*)}}/m';
    
    /**
     * __construct
     *
     * @param  mixed $filename Path to the template file
     * @return void
     */
    function __construct($filename) {
        $this->filepath = $filename;
        $this->filehandler = new \M133\FileX($filename);
        $this->file_contents = $this->filehandler->read();
    }
    
    /**
     * render
     * Renders a template
     * @param  array $data Array of key:value render vars
     * @return void
     */
    public function render($data) {
        $tag_matches = $this->match($this->template_tag_re);
        $rendered_tags = array();

        foreach ($tag_matches as $tag) {
            // skip already matched
            if ( in_array($tag, $rendered_tags) ) {
                continue;
            }

            array_push($rendered_tags, $tag);

            [$tag, $tag_key] = $tag;

            $this->replace($tag, $tag_key, $data);
        }

        return $this->file_contents;
    }
    
    /**
     * match
     * Matches template tags
     * @param  string $re Regex to match tags
     * @return array $matches Array of matched tags
     */
    private function match($re) {
        preg_match_all($re, $this->file_contents, $matches, PREG_SET_ORDER, 0);
        return $matches;
    }
    
    /**
     * replace
     *
     * @param  string $template_tag The full template tag
     * @param  string $data_key The name of the template tag
     * @param  array $data_arr The array of template tag keys and their values
     * @return void
     */
    private function replace($template_tag, $data_key, $data_arr) {
        $this->file_contents = str_replace($template_tag, $data_arr[$data_key], $this->file_contents);
    }
}