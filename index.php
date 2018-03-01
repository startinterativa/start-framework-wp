<?php 
/*
Plugin Name: Start
Plugin URI: http://startinterativa.com
Description: Base Features for Start.
Version: 0.1.0
Author: Vitor Marcelino
Author URI: http://startinterativa.com
License: GPL2
*/

add_action("admin_menu", "addMenu");

require_once(ABSPATH . '/vendor/autoload.php' );

if(!isset($GLOBALS['start']['render'])) {
    $twigPath = ABSPATH . 'wp-content/';
    $loader = new \Twig_Loader_Filesystem($twigPath);
    $GLOBALS['start']['render'] = new \Twig_Environment($loader, array('debug' => true));
}

function addMenu() {
    add_menu_page("Start", "Start", "edit_posts",
        "start-base-settings", "displayPage", null);
}

add_action( 'init', 'create_post_type_pessoa' );
function create_post_type_pessoa() {
    register_post_type( 'pessoa',
        array(
            'labels' => array(
                'name' => __( 'Pessoas' ),
                'singular_name' => __( 'Pessoa' ),
                'add_new' => _x('Adicionar Nova', 'pessoa'),
                'add_new_item' => __('Adicionar nova Pessoa')   
            ),
            'public' => true,
            'register_meta_box_cb' => 'process_meta_box',
            'supports' => array( 'title', 'editor', 'thumbnail')
        )
    );
}

function process_meta_box(){
    add_meta_box('bio', 'Bio', 'getForm', 'pessoa');
}

function getForm(){
    global $post;
    ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="bio">Valor: </label>
                </th>
                <td>
                    <input type="text" class="large-text" name="bio" id="bio" value="<?php echo $post->__get('BIO'); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="showBio">Exibir</label>
                </th>
                <td>
                    <input type="checkbox" name="showBio" id="showBio" <?php if ($post->__get('showBio')) echo 'checked'; ?>/>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}

add_action('save_post', 'save_pessoa_post');
    
function save_pessoa_post(){
    global $post;
    update_post_meta($post->ID, 'bio', $_POST['bio']);
    update_post_meta($post->ID, 'showBio', ($_POST['showBio'] && $_POST['showBio'] = 'on') ? 1 : 0);
}
