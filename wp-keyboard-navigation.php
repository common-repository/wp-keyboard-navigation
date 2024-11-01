<?php
/*
	Plugin Name: WP Keyboard Navigation
	Plugin URI: http://leondoornkamp.nl
	Description: Let's your visitors navigate to different pages with the keyboard arrows and numbers
	Author: Leon Doornkamp
	Author URI: http://leondoornkamp.nl
	Version: 1.0.1
*/

/*
This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

add_action( 'wp_footer', 'wpkn_footer' );

function wpkn_footer(){
	$next_page_url = false;
	$previous_page_url = false;
	global $wp_query;
	if( is_singular() ){	 
	 	$previous_post = get_previous_post();
	 	$next_post = get_next_post();

	 	if( $next_post )
	 		$previous_page_url = get_permalink( $next_post->ID );
		if( $previous_post )
			$next_page_url = get_permalink( $previous_post->ID );	
	}else{
		global $paged;
	 	if ( !$max_page )
			$max_page = $wp_query->max_num_pages;
		if ( !$paged )
	 		$paged = 1;

	 	$nextpage = intval($paged) + 1;	
	 	$next_page_url =  next_posts( $max_page, false );
		$previous_page_url = ( ! is_single() &&  $paged > 1 ) ? previous_posts( false ) : false;
	}
?>
<script>
var keyarr = [];
var keys = {
	48:'0',49:'1',50:'2',51:'3',52:'4',53:'5',	54:'6',55:'7',56:'8',	57:'9',
	96:'0',97:'1',98:'2',99:'3',100:'4',101:'5',102:'6',103:'7',104:'8',105:'9'
};
<?php if( ! is_singular() ){?>
	function do_navigation(){
		<?php
			$large = 999999999999;
			$link = str_replace( $large, '%#%', esc_url( get_pagenum_link( $large ) ) );
		?>
		link = '<?php echo $link;?>';

		pageurl = '';

		for( t=0;t<keyarr.length;t++){
			pageurl += keys[keyarr[t]];
		}

		if( pageurl != undefined && pageurl <= <?php echo $wp_query->max_num_pages;?>){
			link = link.replace( '%#%', pageurl );
			window.location.href = link;
		}
		keyarr = [];
	}
<?php } ?>
document.onkeydown = checkKey;
function checkKey(e) {
var el = document.activeElement.tagName;
if( el.toLowerCase() == 'body' ){	
e = e || window.event;
<?php if( ! is_singular() ){?>
if( (e.keyCode >= 48 && e.keyCode <= 57 ) || ( e.keyCode >= 96 && e.keyCode <= 105 ) )
{
try{
	if(typeof(num) !== undefined){
		window.clearTimeout(num);
	}
}
catch(e){}
	keyarr.push(e.keyCode);
	num = window.setTimeout(do_navigation, 500);
}	
<?php } ?>

<?php if( $previous_page_url ){ ?>
if(e.keyCode == 37){
window.location.href = '<?php echo $previous_page_url; ?>';
}
<?php } ?>
<?php if( $next_page_url ){ ?>
if(e.keyCode == 39){
window.location.href = '<?php echo $next_page_url; ?>';
}
<?php } ?>
}
}
</script>

<?php
}

?>