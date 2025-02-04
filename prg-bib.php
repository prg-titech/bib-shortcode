<?php
/*
 * Plugin Name:       PRG Bibliography Shortcode
 * Plugin URI:        https://github.com/prg-titech/bib-shortcode/
 * Description:       Shortcode to embed publication lists.
 * Version:           0.1.16-alpha
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

// required JS and CSS file names
define('PRG_BIB_SCRIPT_FILE', 'resize-iframe.js');
define('PRG_BIB_STYLE_FILE', 'prg-bib.css');
// URL to the publications directory where bibtexbrowser PHP code exists
define('PAPERS_URL', '/papers/');
// PHP file names for showing the publication list, and the reference list
define('BIBTEX_BROWSER_URL', PAPERS_URL . 'bibtexbrowser.php');
define('BIBTEX_INDEX_URL', PAPERS_URL . 'bibindex.php');
// semicolon separated .bib file names of the publication list,
// English only and both English and Japanese papers
define('DOT_BIB_FILES','prg-e.bib;thesis-b.bib;thesis-m.bib;thesis-d.bib');
define('DOT_BIB_FILES_J', DOT_BIB_FILES . ';prg-j.bib');

// we register and requrie the script and style files under those names
define('PRG_BIB_SCRIPT', 'resize-iframe-js');
define('PRG_BIB_STYLE', 'prg-bib-style');

// An identity function for interpolating string with expressions.
// With this variable, we can write expressions in this way:
//   global $ev;
//   ... "   ... {$ev(SOME[$expression])} ...  " ....
$ev = function($val){return $val;};

/**
 * Our theme/plugin enqueue scripts function.
 * see https://www.briancoords.com/how-to-enqueue-js-for-a-shortcode/
 */
function prg_bib_enqueue_scripts() {
    
    // Register the script in the normal WordPress way.
    wp_register_script( PRG_BIB_SCRIPT,
                        plugins_url(PRG_BIB_SCRIPT_FILE,__FILE__));

    // Register the style in the normal WordPress way.
    wp_register_style( PRG_BIB_STYLE,
                        plugins_url(PRG_BIB_STYLE_FILE,__FILE__));
    
    // Grab the global $post object.
    global $post;

    // When the shortcode embeds a bibtexbrowser frame, these scripts
    // and styles shall be enqueued.  By theory, they are needed only
    // when a page contains the embedded frame.  However, we always
    // enqueue them because they are needed for the embedded frames
    // appearing on the front page, and we cannot find a good way to
    // determine whether any of the pages appearing on the front page
    // contains the embedded frame.

    // See if the post HAS content and, if so, see if it has our shorcode.
    // if ( isset( $post->post_content ) &&
    //      has_shortcode( $post->post_content, 'prg-bib' ) ) {
        wp_enqueue_script( PRG_BIB_SCRIPT );
        wp_enqueue_style( PRG_BIB_STYLE );
    // }
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
function prg_bib_personal_list( $atts, $lang ) {
    $bib_url = BIBTEX_BROWSER_URL;
    $author_param = 'author=' . str_replace(' ','+',$atts['author']);
    $bib_param_e = 'bib=' . DOT_BIB_FILES;
    $bib_param_ej = 'bib=' . DOT_BIB_FILES_J;
    if ($lang == "ja") {
        $bib_param_e = $bib_param_ej;
        $ej_button = "";
    } else {
        $ej_button = <<<HTML
    <a title='publications both in English and Japanese' 
       href='$bib_url?frameset&amp;$bib_param_ej'>
        <div style='position: absolute; padding-left: 8px; padding-top: 2px;'>
          <img src='/wp-content/plugins/qtranslate/flags/jp.png' />
        </div>
        <div style='position: absolute; padding-left: 2px; padding-top: 10px;'>
          <img src='/wp-content/plugins/qtranslate/flags/gb.png' />
        </div>
        <span class='transparent'>👥</span> 
    </a> 
HTML;
    }

    global $ev;
    $code = <<<HTML
<div>
  <div class='linksblock'>
    <a title='publications of the group' href='{$ev(PAPERS_URL)}'>👥</a>
    $ej_button
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
    global $ev;
    $code = "";
    foreach(preg_split("/\,/", $atts['key']) as $key) {
        $code .= "
<iframe id='". $key .
              "' src='{$ev(BIBTEX_BROWSER_URL)}?key=". $key ."&bib=" .
              DOT_BIB_FILES_J .
              "' class='bibtexbrowser' onload='resizeIframe(this)'></iframe>";
    }
    return $code;
}

// index
function prg_bib_embed_index( $atts , $no_jump_link, $referer_url ) {
    global $ev;
    $code = "
<iframe src='{$ev(BIBTEX_INDEX_URL)}?keys="
          . $atts['key'] . "&bib={$ev(DOT_BIB_FILES_J)}"
          . ($no_jump_link ? "" : "&parent=" . urlencode($referer_url)) .
          "' class='bibtexbrowser' onload='resizeIframe(this)'></iframe>";
    return $code;
    //NOTE: when $referer_url contains '&' (e.g.,
    //"/ja/?p=8738&preview=true"), the src URL for the iframe becomes
    //https://.../bibindex.php?...&parent=/ja/?p=8738&preview=true
    //where the preview parameter is not part of the parent but for
    //the iframe.  Hence converting & to an escape character by using
    //urlencode() is important.
}

// Add Shortcode
function prg_bib_shortcode( $atts, $content = null ) {
    global $post;

	// Attributes
	$atts = shortcode_atts(
		array(
			'key' => '',
            'more' => 'true',
            'author' => '',
            'index' => '',
		),
		$atts
	);
    $lang = get_bloginfo("language");

    $code = "";

    $show_more = $atts['more']=='true' && is_front_page() ;

    if($atts['index']==true) {
        $code .= prg_bib_embed_index($atts, $show_more ,
                                     $_SERVER['REQUEST_URI']);
        //NOTE: get_the_permalink gives a URI of the current page
        //without parameters whereas $_SERVER['REQUEST_URI'] includes
        //everything.
    }


    if($show_more){
        // when the post/page is shown on the front page, this will
        // produce only a "(more...)" link to the standalone page.
        // Otherwise (i.e., when it is shown as a standalone page), the
        // frame is shown.
        $code .= prg_bib_show_more($atts);
    } else {
        if($content != null) $code .= $content;
        if ($atts['author']!='') {
            // when there is an author parameter, show a list of all
            // papers authored by the given name
            $code .= prg_bib_personal_list($atts, $lang);
        } else {
            // otherwise, there should be a key parameter, and show
            // per-article pages
            $code .= prg_bib_articles($atts);
        }
    }
	return $code;
    
}
add_shortcode( 'prg-bib', 'prg_bib_shortcode' );
