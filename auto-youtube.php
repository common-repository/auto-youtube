<?php
/*
Plugin Name: Auto youtube
Plugin URI: http://kalanjdjordje.wordpress.com/works/auto-youtube/
Description: Youtube auto search videos
Version: 0.0.3
Author: Kalanj Djordje
Author URI: http://kalanjdjordje.wordpress.com/

*/


class AutoYoutubeSinCustoms extends WP_Widget {
    /** constructor */
    function AutoYoutubeSinCustoms() {
        parent::WP_Widget('autoyoutubesincustoms', $name = 'Auto Youtube', array('description' => 'Youtube auto search videos', 'class' => 'AutoYoutubeSinCustoms'));
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
		extract( $args );
		if((is_home() && $instance['show_on_home']) || is_single()){		
			$title = apply_filters('widget_title', $instance['title']);
			$search_string ='';
			if($instance['use_tags']){
				$posttags = get_the_tags($post->ID);
				if(!empty($posttags)){
					$tagdisplay='';
					foreach($posttags as $this_tag){
						$tagdisplay = $tagdisplay . "".$this_tag->name." ";
					}
					$search_string = $search_string." ".$tagdisplay;
				}
			}
			if($instance['use_categories']){
				$category = get_the_category($post->ID); 
				if(!empty($category)){
					$catdisplay ='';
					foreach($category as $cat){
						$catdisplay = $catdisplay."".$cat->cat_name." ";
					}
					$search_string = $search_string." ".$catdisplay;
				}
			}
			//echo $search_string;
			if($instance['use_title']){
				$displaytitle = get_the_title($post->ID);
				$search_string = $search_string." ".$displaytitle;
			}
		
			?>
				  <?php echo $before_widget; ?>
					  <?php if ( $title )
							echo $before_title . $title . $after_title;
							add_scripts($search_string,$instance['large_list'],$instance['horizontal'],$instance['small_thumbnail']);
							add_holder();
							?>
				  <?php echo $after_widget; ?>
			<?php
		}
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['show_on_home'] 		= strip_tags($new_instance['show_on_home']);
		$instance['use_tags'] 			= strip_tags($new_instance['use_tags']);
		$instance['use_categories'] 	= strip_tags($new_instance['use_categories']);
		$instance['use_title'] 			= strip_tags($new_instance['use_title']);
		$instance['small_thumbnail'] 	= strip_tags($new_instance['small_thumbnail']);
		$instance['horizontal'] 		= strip_tags($new_instance['horizontal']);
		$instance['large_list'] 		= strip_tags($new_instance['large_list']);
		return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
        $show_on_home = $instance['show_on_home'];
        $use_tags = $instance['use_tags'];
        $use_categories = $instance['use_categories'];
        $use_title = $instance['use_title'];
        $small_thumbnail = $instance['small_thumbnail'];
        $horizontal = $instance['horizontal'];
        $large_list = $instance['large_list'];
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
			<input id="<?php echo $this->get_field_id('show_on_home'); ?>" name="<?php echo $this->get_field_name('show_on_home');?>" type="checkbox" <?php if($show_on_home){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('show_on_home'); ?>"><?php _e('Show on homepage');?></label>

        </p>
		<p>
			<input id="<?php echo $this->get_field_id('use_tags'); ?>" name="<?php echo $this->get_field_name('use_tags');?>" type="checkbox" <?php if($use_tags){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('use_tags'); ?>"><?php _e('Use tags');?></label>
        </p>
		<p>
			<input id="<?php echo $this->get_field_id('use_categories'); ?>" name="<?php echo $this->get_field_name('use_categories');?>" type="checkbox" <?php if($use_categories){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('use_categories'); ?>"><?php _e('Use categories');?></label>
        </p>
		<p>
			<input id="<?php echo $this->get_field_id('use_title'); ?>" name="<?php echo $this->get_field_name('use_title');?>" type="checkbox" <?php if($use_title){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('use_title'); ?>"><?php _e('Use title');?></label>
        </p>
		<p>
			<input id="<?php echo $this->get_field_id('small_thumbnail'); ?>" name="<?php echo $this->get_field_name('small_thumbnail');?>" type="checkbox" <?php if($small_thumbnail){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('small_thumbnail'); ?>"><?php _e('Small thumbnails');?></label>
        </p>
		<p>
			<input id="<?php echo $this->get_field_id('horizontal'); ?>" name="<?php echo $this->get_field_name('horizontal');?>" type="checkbox" <?php if($horizontal){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('horizontal'); ?>"><?php _e('Horizontal');?></label>
        </p>
		<p>
			<input id="<?php echo $this->get_field_id('large_list'); ?>" name="<?php echo $this->get_field_name('large_list');?>" type="checkbox" <?php if($large_list){ echo 'checked="checked"';} ?> value="One" />
			<label for="<?php echo $this->get_field_id('large_list'); ?>"><?php _e('Large list');?></label>
        </p>		
 
        <?php 
    }
}
	function add_scripts($search_string,$largeResultSet,$horizontal,$small_thumbnail){ ?>
 <script src="http://www.google.com/uds/api?file=uds.js&v=1.0"
    type="text/javascript"></script>
  <link href="http://www.google.com/uds/css/gsearch.css" rel="stylesheet"
    type="text/css"/>
  <script src="http://www.google.com/uds/solutions/videobar/gsvideobar.js"
    type="text/javascript"></script>
  <link href="http://www.google.com/uds/solutions/videobar/gsvideobar.css"
    rel="stylesheet" type="text/css"/>
	
  <style type="text/css">

    #videoBar {
      width : 100%;
      margin-right: 5px;
      margin-left: 5px;
      padding-top : 4px;
      padding-right : 4px;
      padding-left : 4px;
      padding-bottom : 0px;
    }
	#videoBar .resultTable_gsvb {
	  width : 480px;
    
	}
	
    .playerBox_gsvb div.alldone_gsvb {
		font-size:20px;
	}
	#videoBar-player .playerInnerBox_gsvb .player_gsvb {
      width : 480px;
      height : 380px;
    }


  </style>
  <script type="text/javascript">
    function LoadVideoBar() {
      var videoBar;
      var barContainer = document.getElementById("videoBar");
	  var videoBar_player = document.getElementById("videoBar-player")
      var options = {
		largeResultSet : <?php if($largeResultSet == true)echo 'true'; else echo 'false' ;?>,
        horizontal: <?php if($horizontal == true) echo 'true'; else echo 'false' ?>,
        thumbnailSize : GSvideoBar.<?php if($small_thumbnail == true) echo "THUMBNAILS_SMALL"; else echo "THUMBNAILS_MEDIUM";?>,
		string_allDone: "<?php _e("Close Video.");?>",
        autoExecuteList : {
				executeList : [  "<?php echo $search_string;?>"]
				}
      }

      videoBar = new GSvideoBar(barContainer, videoBar_player, options);
	  
    }
    GSearch.setOnLoadCallback(LoadVideoBar);
  </script>

	<?php
	}
	function add_holder(){ ?>
	    <div id="videoBar"><?php _e('Loading');?>...</div>
	    <div id="videoBar-player"></div>
		
	<?php
	}
add_action('widgets_init', create_function('', 'return register_widget("AutoYoutubeSinCustoms");')); 

?>