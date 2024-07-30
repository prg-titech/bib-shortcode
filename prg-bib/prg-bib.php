<?php
/*
 * Plugin Name:       PRG Bibliography Shortcode
 * Plugin URI:        https://github.com/prg-titech/prg-papers-bib/issues/65
 * Description:       Shortcode to embed publication lists.
 * Version:           N/A
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

// Add Shortcode
function prg_bib_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'key' => '',
		),
		$atts
	);

    $code = "<script>  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
    obj.style.width = '100%';
    obj.style.border = 'none';
  }
</script>
<iframe src='https://prg.is.titech.ac.jp/papers/bibtexbrowser.php?key=". $atts['key'] ."&bib=prg-e.bib;prg-j.bib;thesis-d.bib;thesis-m.bib;thesis-b.bib' class='bibtexbrowser' onload='resizeIframe(this)'></iframe>";

    
	return $code; // '<p>Hello PRG Bib Shortcode</p>';

}
add_shortcode( 'prg-bib', 'prg_bib_shortcode' );
