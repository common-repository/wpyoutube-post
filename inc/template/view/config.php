<?php
require_once YTBTOWP_PLUGIN_DIR . '/inc/template/header.php';
?>
<?php


$chanel_id    = get_option('_youtube_chanel_id');
$api_key      = get_option('_youtube_api_key');

?>




<div class="container">
  <div class="row main_container">

    <p class="font_size">This plugin is used for creating wordpress post from the youtube, based on video title and description. If you have any query then contact us <a href="https://nktechnologys.com/contact-us/" target="_blank">Contact Us</a></p>

    <div class="col-md-12 mt-5">
      
      <p class="font_size ">Get you youtube API key from this link <a target="_blank" href="https://console.cloud.google.com/apis/dashboard?project=youtubetowppost">Get API Key</a> All you need to do is 
        <br/>1. Create a new Project 
        <br/>2. Then choose Youtube Data Api v3 
        <br/>3. Then under credential menu on left hand side, Click Create Credentials and Choose API Key   </p>
        <br/>4. Get your youtube chanel id via login on youtube.
     <br/><br/>
    </div>

    <div class="col-md-12">
      
      <div class="table_container">

       <form action="javascript:void(0);" id="update_config">

            <div class="form-group">
               <label for="email">Enter Youtube Chanel Id:</label>
               <input type="text" name="yt_chl_id" class="form-control" id="yt_chl_id" value="<?php if(isset($chanel_id)&& !empty($chanel_id)){echo esc_html_e($chanel_id,'text-domain' );}?>" >
            </div>


            <div class="form-group">
               <label for="key">Enter API Key:</label>
               <input type="text" name="api_key" class="form-control" id="api_key" value="<?php if(isset($api_key)&& !empty($api_key)){ echo esc_html_e($api_key,'text-domain' );}?>" >
            </div>


             <div class="form-group show_error hide" id="show_error">
              <label id="msg_error" class="" for="msg error"></label>
             </div>
            <button type="submit" class="btn btn-primary <?php if(null != $chanel_id && null != $api_key ){echo _e('mt-2');} ?>" id="<?php if(null == $chanel_id && null == $api_key ){echo _e('get_list');}?>"  <?php if(null != $chanel_id && null != $api_key ){echo _e('disabled');} ?> >Connect</button>
            &nbsp;&nbsp;<button type="reset" class="btn btn-warning" id="reset_config">Reset / Reconfigure</button>

            <?php if(null != $chanel_id && null != $api_key ): ?>

             &nbsp;&nbsp;<button type="button" id="sync_youtube_post" class="btn btn-success mt-2">Sync Youtube Post</button>

            <?php endif; ?>
          </form>

       </div>

    </div>

    
  </div>
  
</div>



<div id="loader">
</div>




<style type="text/css">
  	 #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.75) url("<?php echo plugin_dir_url( __FILE__ ) .'../img/loading.gif'; ?>") no-repeat center center;
            z-index: 10000;
        }
</style>



