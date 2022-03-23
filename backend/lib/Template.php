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
    private $current_page;

    private $templateTagRegex = '/{{(.*)}}/m';
    private $actionDelimiter = ':';
    
    /**
     * __construct
     *
     * @param  mixed $filename Path to the template file
     * @return void
     */
    function __construct(
        private string $views_path
    ) {
        $this->filehandler = new \M133\Filehandler();
        $this->current_page = $_SERVER["SCRIPT_NAME"];
    }

    public function renderIntoBase( $additional_tags, $menus, $user ) {
        [$mobile_personmenu, $desktop_personmenu] = $this->renderPersonMenu( $menus['person'] );

        [$desktop_menu, $mobile_menu] = $this->renderMainMenu( $menus['main'] );

        $user_modal = $this->render( 'components/modal.html',[
            'id' => 'profile-modal',
            'modal_title' => 'Profile',
            'modal_body' => 'components/modal_body_user.html',
            'modal_ok_btn' => 'Close',
            'modal_no_btn' => 'Cancel',
            'username' => $user['username'],
            'full_name' => $user['first_name']. " ". $user['last_name'],
            'email' => $user['email'],
        ], true);

        $tags = [
            'head_scripts' => 'head_scripts.html',
            'content' => 'base_app.html',
            'footer' => 'footer.html',
            'address' => '123 street 4',
            'mobile_personmenu' => $mobile_personmenu,
            'desktop_personmenu' => $desktop_personmenu,
            'desktop_menu' => $desktop_menu,
            'mobile_menu' => $mobile_menu,
            'user_modal' => $user_modal,
            'username' => $user['username'],
            'full_name' => $user['first_name']. " ". $user['last_name'],
            'email' => $user['email'],
        ];

        $tags = array_merge( $tags, $additional_tags );


        $this->render('base.html', $tags);
    }
    
    private function renderMainMenu( $menu_arr ) {
        $desktop_menu = "";
        $mobile_menu = "";

        foreach ($menu_arr as $title => $url) {
            if ( $this->isActivePage($url) ) {
                $desktop_menu .= $this->renderMenuItem( $title, $url, 'components/desktop_menu_item_active.html');
                $mobile_menu .= $this->renderMenuItem( $title, $url, 'components/mobile_menu_item_active.html');
            } else {
                $desktop_menu .= $this->renderMenuItem( $title, $url, 'components/desktop_menu_item.html');
                $mobile_menu .= $this->renderMenuItem( $title, $url, 'components/mobile_menu_item.html');
            }
        }

        return [$desktop_menu, $mobile_menu];
    }

    private function renderPersonMenu( $menu_arr ) {
        $mobile_personmenu = "";
        $desktop_personmenu = "";

        foreach ($menu_arr as $title => $url) {
            $mobile_personmenu .= $this->renderMenuItem( $title, $url, 'components/mobile_personmenu_item.html');
            $desktop_personmenu .= $this->renderMenuItem( $title, $url, 'components/desktop_personmenu_item.html');
        }

        return [$mobile_personmenu, $desktop_personmenu];
    }

    private function isActivePage( $url ) {
        return $url == $this->current_page;
    }

    private function renderMenuItem( $title, $url, $template ) {
        return $this->render($template, [
            'title' => $title,
            'url' => $url
        ], true);
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
        $this->filepath = $this->views_path . $filename;
        $this->fileContents = $this->filehandler->read($this->filepath);

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
                    $this->views_path . $data_arr[$key_name]
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