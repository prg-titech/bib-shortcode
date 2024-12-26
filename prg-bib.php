<?php
/*
 * Plugin Name:       PRG Bibliography Shortcode
 * Plugin URI:        https://github.com/prg-titech/bib-shortcode/
 * Description:       Shortcode to embed publication lists.
 * Version:           0.1.11-alpha
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Hidehiko Masuhara
 * Author URI:        https://prg.is.titech.ac.jp/people/masuhara
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/prg-titech/prg-papers-bib/issues/65
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 * Requires Plugins:  
 */


/**
 * Our theme/plugin enqueue scripts function.
 * see https://www.briancoords.com/how-to-enqueue-js-for-a-shortcode/
 */
function prg_bib_enqueue_scripts() {
    
    // Register the script in the normal WordPress way.
    wp_register_script( 'resize-iframe-js',
                        plugins_url('resize-iframe.js',__FILE__));

    // Register the style in the normal WordPress way.
    wp_register_style( 'prg-bib-style',
                        plugins_url('prg-bib.css',__FILE__));
    
    // Grab the global $post object.
    global $post;
    
    // See if the post HAS content and, if so, see if it has our shorcode.
    if ( isset( $post->post_content ) &&
         has_shortcode( $post->post_content, 'prg-bib' ) ) {
        wp_enqueue_script( 'resize-iframe-js' );
        wp_enqueue_style( 'prg-bib-style' );
    }
}
add_action( 'wp_enqueue_scripts', 'prg_bib_enqueue_scripts' );

// show 'more...' text when it is shown in the front page
function prg_bib_show_more( $atts ) {
    $more_link_text = __( '(more&hellip;)' );
    return apply_filters(
        'the_content_more_link', 
        ' <a href="' . get_permalink() .
            "#more-{$post->ID}\" class=\"more-link\">$more_link_text</a>",
        $more_link_text );
}

// list of persons
function prg_bib_personal_list( $atts ) {
    $bib_url = 'https://prg.is.titech.ac.jp/papers/bibtexbrowser.php';
    $author_param = 'author=' . str_replace(' ','+',$atts['author']);
    $bib_param_e = 'bib=prg-e.bib;thesis-b.bib;thesis-m.bib;thesis-d.bib';
    $bib_param_ej = $bib_param_e . ';prg-j.bib';
    $code = <<<HTML
<div>
  <div class='linksblock'>
    <a title='publications of the group' href='https://prg.is.titech.ac.jp/papers/'>👥</a>
    <a title='publications both in English and Japanese' 
       href='$bib_url?frameset&amp;$bib_param_ej'>
        <div style='position: absolute; padding-left: 8px; padding-top: 2px;'>
          <img src='https://prg.is.titech.ac.jp/wp-content/plugins/qtranslate/flags/jp.png' />
        </div>
        <div style='position: absolute; padding-left: 2px; padding-top: 10px;'>
          <img src='https://prg.is.titech.ac.jp/wp-content/plugins/qtranslate/flags/gb.png' />
        </div>
        <span class='transparent'>👥</span> 
    </a> 
    <a title='full page view' 
       href='$bib_url?$author_param&amp;$bib_param_e'>
       ⛶
    </a>
  </div>
  <iframe class='bibtexbrowser' 
        src='$bib_url?$author_param&amp;$bib_param_e&amp;notitle=true'>
  </iframe>
</div>
HTML;
    return $code;
}

// per article pages
function prg_bib_articles( $atts ) {
    $code = "";
    foreach(preg_split("/\,/", $atts['key']) as $key) {
        $code .= "
<iframe src='https://prg.is.titech.ac.jp/papers/bibtexbrowser.php?key=". $key ."&bib=prg-e.bib;prg-j.bib;thesis-d.bib;thesis-m.bib;thesis-b.bib' class='bibtexbrowser' onload='resizeIframe(this)'></iframe>";
    }
    return $code;
}

// Add Shortcode
function prg_bib_shortcode( $atts ) {
    global $post;

	// Attributes
	$atts = shortcode_atts(
		array(
			'key' => '',
            'more' => 'true',
            'author' => '',
		),
		$atts
	);
    $code = "";
    if($atts['more']=='true' && is_front_page() ){
        // when the post/page is shown on the front page, this will
        // produce only a "(more...)" link to the standalone page.
        // Otherwise (i.e., when it is shown as a standalone page), the
        // frame is shown.
        $code .= prg_bib_show_more($atts);
    } else if ($atts['author']!='') {
        // when there is an author parameter, show a list of all
        // papers authored by the given name
        $code .= prg_bib_personal_list($atts);
    } else {
        // otherwise, there should be a key parameter, and show
        // per-article pages
        $code .= prg_bib_articles($atts);
    }
	return $code;
    
}
add_shortcode( 'prg-bib', 'prg_bib_shortcode' );
