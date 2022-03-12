<?php

/**
 * @author      Enea Krähenbühl <inquiry@3n3a.ch>
 * @copyright   Copyright (c), 2022 Enea Krähenbühl
 * @license     MIT public license
 */
namespace M133;

require_once __DIR__ . '/Filehandler.php';

/**
 * TemplateY
 * Wrapper for easy templating
 * @example An example template tag `{{name}}`
 */
class Template {
 
    private $filepath = "";
    private $filehandler = NULL;
    private $fileContents = "";

    private $templateTagRegex = '/{{(.*)}}/m';
    private $actionDelimiter = ':';
    
    /**
     * __construct
     *
     * @param  mixed $filename Path to the template file
     * @return void
     */
    function __construct() {
        $this->filehandler = new \M133\Filehandler();
    }
    
    /**
     * render
     * Renders a template
     * @param  string $filename Path to template file
     * @param  array $data Array of key:value render vars
     * @param  bool $return_string Defines if should be returned or echoed
     * @return void
     */
    public function render($filename, $data, $return_string=FALSE) {
        $this->filepath = $filename;
        $this->fileContents = $this->filehandler->read($filename);

        $tag_matches = $this->match($this->templateTagRegex, $this->fileContents);
        $this->fileContents = $this->renderTags($tag_matches, $data, $this->fileContents);

        if ($return_string) {
            return $this->fileContents;
        } else {
            echo $this->fileContents;
        }
    }

    private function renderTags($matched_tags, $data, $content) {
        $rendered_tags = array();

        foreach ($matched_tags as $tag) {
            // skip already matched
            if ( in_array($tag, $rendered_tags) ) {
                continue;
            }

            array_push($rendered_tags, $tag);

            [$tag, $tag_key] = $tag;

            $content = $this->replace($tag, $tag_key, $data, $content);
        }

        return $content;
    }
    
    /**
     * match
     * Matches template tags
     * @param  string $re Regex to match tags
     * @return array $matches Array of matched tags
     */
    private function match($re, $contents) {
        preg_match_all($re, $contents, $matches, PREG_SET_ORDER, 0);
        return $matches;
    }
    
    /**
     * replace
     *
     * @param  string $template_tag The full template tag
     * @param  string $data_key The name of the template tag
     * @param  array $data_arr The array of template tag keys and their values
     * @param  string $content The text to replace on
     * @return void
     */
    private function replace($template_tag, $data_key, $data_arr, $content) {

        if (stristr($data_key, $this->actionDelimiter)) {
            [$key_action, $key_name] = explode( $this->actionDelimiter, $data_key );
        } else {
            $key_action = "v";
            $key_name = $data_key;
        }

        $replacement = "";
        switch ($key_action) {
            case 'r':
                // require
                $replacement = $this->filehandler->read(
                    $data_arr[$key_name]
                );

                // render tags in subview
                $matched_tags = $this->match($this->templateTagRegex, $replacement);
                $replacement = $this->renderTags($matched_tags, $data_arr, $replacement);
                break;
            
            case 'v':
                // variable
                $replacement = $data_arr[$data_key];
                break;
            
            default:
                $replacement = "";
                break;
        }

        return str_replace($template_tag, $replacement, $content);
    }
}