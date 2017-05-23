<?php
/**
 * Single Collective Template - this is the template for the single collective post.
 */

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		//get all the page data needed and set it to an object that can be used in other files
		$pexeto_page=array();
		$pexeto_page['sidebar']=pexeto_option( 'post_sidebar' );
		$pexeto_page['slider']='none';
		$pexeto_page['layout']=pexeto_option( 'post_layout' );
		

		$pexeto_page['style'] = pexeto_get_single_meta($post->ID, 'post_style');
		if($pexeto_page['style'] === 'header'){
			$pexeto_page['header_display'] = array('show_title'=>true);
			$pexeto_page['title']=$post->post_title;
			$pexeto_page['subtitle'] =  pexeto_get_single_meta($post->ID, 'subtitle');
			$pexeto_page['hide_thumbnail'] = true;
			$pexeto_page['hide_title'] = true;
		}else{
			$pexeto_page['header_display'] = array('show_title'=>false);
			$pexeto_page['title']='';
		}

		//include the before content template
		locate_template( array( 'includes/html-before-content.php' ), true, true );

		//include the post template
		locate_template( array( 'includes/post-template.php' ), true, false );

		//include the comments template
		comments_template();
	}
} ?>

</div> <!-- end main content holder (#content/#full-width) -->

<?php // echo collective extra fields
$prefix = '_ab_collective_';
$fields = array(
	'city' => array(
		'label' => __('City','abarrios'),
		'group' => 'geo',
		'format' => 'icon'
	),
	'country' => array(
		'label' => __('Country','abarrios'),
		'group' => 'geo',
		'type' => 'icon'
	),
	'interview' => array(
		'label' => sprintf( __('Conversation with %s','abarrios'),get_the_title() ),
		'group' => 'unique',
		'type' => 'wysiwyg'
	),
	'bio' => array(
		'label' => __('Bio','abarrios'),
		'group' => 'unique',
		'type' => 'wysiwyg'
	),
	'fb' => array(
		'label' => __('Facebook','abarrios'),
		'group' => 'social',
		'type' => 'link'
	),
	'tw' => array(
		'label' => __('Twitter','abarrios'),
		'group' => 'social',
		'type' => 'link'
	)
);
$f_list = '';
$f_geo = '';
$f_unique = '';
$f_social = '';
foreach ( $fields as $k => $f ) {
	$v = ($f['type'] == 'wysiwyg') ? apply_filters( 'the_content',get_post_meta($post->ID,$prefix.$k,true) ) : get_post_meta($post->ID,$prefix.$k,true);
	switch ($f['group']) {
	case 'geo';
		if ( $v != '' ) { $f_geo[] = '<span class="collective-'.$k.'">'.$v.'</span>'; }
		break;
	case 'unique':
		if ( $v != '' ) { $f_unique[] .= '<div class="collective-card-space collective-'.$k.'"><h5 class="collective-card-subtit">'.$f['label'].'</h5><div class="'.$k.'-text collective-card-text">'.$v.'</div>'; }
		break;
	case 'social':
		if ( $v != '' ) { $f_social[] .= '<li><a href="'.$v.'">'.$f['label'].'</a></li>'; }
	}
}
$f_geo_out = ( is_array($f_geo) ) ? '<div class="collective-geo">'.implode(', ',$f_geo).'</div>' : '';
$f_social_out = ( is_array($f_social) ) ? '<ul class="collective-social list-inline">'.implode('',$f_social).'</ul>' : '';
$f_unique_out = ( is_array($f_unique) ) ? implode('',$f_unique) : '';
if ( $f_geo_ou != '' || $f_social_out != '' ) {
	echo '<div id="sidebar" class="sidebar"><aside class="sidebar-box collective-card">
		<h4 class="collective-card-tit">'.sprintf(__('About %s'),get_the_title() ).'</h4>'
		.$f_geo_out
		.$f_social_out
		.$f_unique_out.
	'</aside></div>';
} ?>
<div class="clear"></div>
<?php get_footer();   ?>
