<?php


class YouTubeWP {  

    
   /*---------------------------------------------------------
   |  Construct function for adding hook
   ----------------------------------------------------------*/

    public function __construct() {
      
      // ini_set('display_errors', 1);
      // ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
        
     add_action( 'admin_menu', array($this,'yt_wp_add_admin_page') );
     
     add_action( 'wp_ajax_nopriv_check_credentials', array($this,'check_credentials') );
     add_action( 'wp_ajax_check_credentials', array($this,'check_credentials' ) );

     add_action( 'wp_ajax_nopriv_reset_crm_config', array($this,'reset_crm_config') );
     add_action( 'wp_ajax_reset_crm_config', array($this,'reset_crm_config' ) );

     add_action( 'wp_ajax_nopriv_sync_youtube_post', array($this,'sync_youtube_post') );
     add_action( 'wp_ajax_sync_youtube_post', array($this,'sync_youtube_post' ) );

     

     add_action( 'admin_enqueue_scripts', array($this,'yt_wp_enqueue_admin_style' ) );

     
    }




    /*--------------------------------------------------------
    | add js/css admin
    ----------------------------------------------------------*/


    public  function yt_wp_enqueue_admin_style() {

        wp_register_style( 'yt_wp_dtbl_css', plugin_dir_url( __FILE__ ) . 'template/css/jquery.dataTables.min.css', false, '1.0.0' );
        wp_register_style( 'yt_wp_btstp_css', plugin_dir_url( __FILE__ ). 'template/css/bootstrap.min.css', false, '1.0.0' );
        wp_register_style( 'yt_wp_tstr_css', plugin_dir_url( __FILE__ ) . 'template/css/toastr.css', false, '1.0.0' );
        wp_register_style( 'yt_wp_styl_css', plugin_dir_url( __FILE__ ) . 'template/css/style.css', false, '1.0.0' );
        wp_register_style( 'yt_wp_ftws_css', plugin_dir_url( __FILE__ ) . 'template/css/font-awesome.min.css', false, '1.0.0' );


      
        wp_register_script( 'yt_wp_js_btstrp', plugin_dir_url( __FILE__ ). 'template/js/bootstrap.min.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_dttbl', plugin_dir_url( __FILE__ ) . 'template/js/jquery.dataTables.min.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_dtbtn', plugin_dir_url( __FILE__ ) . 'template/js/dataTables.buttons.min.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_jszip', plugin_dir_url( __FILE__ ) . 'template/js/jszip.min.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_pdfmk', plugin_dir_url( __FILE__ ) . 'template/js/pdfmake.min.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_vsfnt', plugin_dir_url( __FILE__ ) . 'template/js/vfs_fonts.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_tstr', plugin_dir_url( __FILE__ ) . 'template/js/toastr.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_vldt', plugin_dir_url( __FILE__ ) . 'template/js/jquery.validate.js', false, '1.0.0' );
        wp_register_script( 'yt_wp_js_cstm', plugin_dir_url( __FILE__ ) . 'template/js/custom.js', false, '1.0.0' );

   
        wp_enqueue_script( 'yt_wp_js_btstrp' );
        wp_enqueue_script( 'yt_wp_js_dttbl' );
        wp_enqueue_script( 'yt_wp_js_dtbtn' );
        wp_enqueue_script( 'yt_wp_js_jszip' );
        wp_enqueue_script( 'yt_wp_js_pdfmk' );
        wp_enqueue_script( 'yt_wp_js_vsfnt' );
        wp_enqueue_script( 'yt_wp_js_tstr' );
        wp_enqueue_script( 'yt_wp_js_vldt' );
        wp_enqueue_script( 'yt_wp_js_cstm' );
        
        wp_enqueue_style( 'yt_wp_dtbl_css' );
        wp_enqueue_style( 'yt_wp_btstp_css' );
        wp_enqueue_style( 'yt_wp_tstr_css' );
        wp_enqueue_style( 'yt_wp_styl_css' );
        wp_enqueue_style( 'yt_wp_ftws_css' );

      }





    /*---------------------------------------------------------
    |  Update List with CF7 config setting
    ----------------------------------------------------------*/

    public function save_list(){

        $data = [];

        if(null != sanitize_text_field($_POST['cf7_id']) && !empty(sanitize_text_field($_POST['cf7_id']) ) ){

            $post_id     = sanitize_text_field($_POST['cf7_id']);
            $list_key_id = sanitize_text_field($_POST['hls_list_id']);
            $list_found  = $this->get_list_name_by_id($list_key_id);

           

              if(metadata_exists('post', $post_id, 'hlolead_list_key')) {
                delete_post_meta($post_id,'hlolead_list_key');
              }
              global $wpdb;
              $tablename  = $wpdb->prefix.'postmeta';
              $list_exist = $wpdb->get_results("SELECT * FROM $tablename WHERE `meta_key` = 'hlolead_list_key'");

              if($list_exist){
                foreach ($list_exist as $key => $list_e) {
                  if($list_e->meta_value == $list_key_id){
                    delete_post_meta($list_e->post_id,'hlolead_list_key');
                    
                  }
                }
              }

              update_post_meta($post_id,'hlolead_list_key',$list_key_id);
           


              $data['status'] = true;
              $data['msg']    = " mapped to ";

        

            

        }else{
                $data['status'] = false;
                $data['msg']    = "Invalid parameters. Please try again.";
        }

        echo json_encode($data);exit;
    }


   
    
    
    /*---------------------------------------------------------
    |  Reset config setting
    ----------------------------------------------------------*/

    public function reset_crm_config(){

        $data = [];

        if(null != $_POST['token']  && !empty($_POST['token']) && $_POST['token'] == 'reset'){

            
            $metas   = array( '_youtube_chanel_id' => '',
                              '_youtube_api_key'   => '', 
                            );

            foreach($metas as $key => $value) {
                delete_option($key);
            }

            $data['status'] = true;
            $data['msg']    = "Configration has been reset !";

        }else{
                $data['status'] = false;
                $data['msg']    = "Something went wrong try again.";
        }

        echo json_encode($data);exit;


    }



    /*---------------------------------------------------------
    |  Update config setting
    ----------------------------------------------------------*/

    public function check_credentials(){

        $data = [];

        if(null != $_POST['yt_chl_id']  && !empty($_POST['yt_chl_id']) && null != $_POST['api_key']  && !empty($_POST['api_key']) ){

            $chanel_id   = sanitize_text_field($_POST['yt_chl_id']);
            $api_key     = sanitize_text_field($_POST['api_key']);
            $lists         = $this->check_youtube_credentials($chanel_id,$api_key);

            
            if(array_key_exists('status', $lists) && null == $lists['status']){

                $data['status'] = false;
                $data['msg']    = $lists['message']; //$lists["message"];

            }else{

                     $metas = array( 
                                      '_youtube_chanel_id' => $chanel_id,
                                      '_youtube_api_key'   => $api_key, 
                                  );

                      foreach($metas as $key => $value) {
                          update_option($key, $value);
                      }

                      $data['status'] = true;
                      $data['msg']    = "Setting updated successfully.";
                      


            }

                     

            


        }else{

              $data['status'] = false;
              $data['msg']    = "Invalid chanel id or key";
        }

        echo json_encode($data);exit;

    }
    
    /*---------------------------------------------------------
    |  Check youtube chanel id and key
    ----------------------------------------------------------*/

    public function check_youtube_credentials($chanel_id, $key){

      $data        = [];
      $Max_Results = YTBTOWP_MAXRESULT;


       $endpoint = 'https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$chanel_id.'&maxResults='.$Max_Results.'&key='.$key.'';

       $options = [
            'body'        => '',
        ];
         
        $response = wp_remote_get( $endpoint, $options );
        $apiData      = $response['body'];
        
         
        if($apiData){ 

            $videoList = json_decode($apiData,true); 
            $data['status']  = true;
            $data['message'] = "You are now connected to youtube successfully";
        }else{ 
                $data['status']  = false;
                $data['message'] = 'Invalid API key or channel ID.'; 
            
        }
        
      return $data;

        
    }

    
    /*---------------------------------------------------------
    |  Sync youtube post to wordpress
    ----------------------------------------------------------*/
    

     public function sync_youtube_post(){
       
          global $wpdb;
          $data        = [];
          $Max_Results = YTBTOWP_MAXRESULT;
          $post_id     = '';

          if(isset($_POST['sync']) && null != $_POST['sync']){

            $chanel_id    = get_option('_youtube_chanel_id');
            $key          = get_option('_youtube_api_key');

            if(null != $chanel_id && null != $key){


               $endpoint = 'https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$chanel_id.'&maxResults='.$Max_Results.'&key='.$key.'';

                $options = [
                    'body' => '',
                ];
                 
                $response = wp_remote_get( $endpoint, $options );
                $apiData  = $response['body'];
               
                if($apiData){ 

                    $videoList = json_decode($apiData,true); 

                    if($videoList){

                      array_pop($videoList['items']);

                      foreach ($videoList['items'] as $key => $value) {

                        $data  = @$value['snippet'];
                        $id    = @$value['id'];

                        $title = sanitize_text_field($data['title']);
                        $desc  = sanitize_text_field($data['description']);
                        $desc  .= '<iframe width="420" height="315"
                                    src="http://www.youtube.com/embed/'.$id['videoId'].'?autoplay=1">
                                    </iframe>';
                        $date  = sanitize_text_field(date('Y-m-d h:i:s',strtotime($data['publishedAt'])) );

                           $exist =  $wpdb->query("SELECT ID FROM $wpdb->posts WHERE post_title ='".$title."'  
                                AND post_type = 'post' AND post_status = 'publish' ");
                            

                            if ( $exist == 0 ) {

                                $new = array(
                                      'post_title'   => $title,
                                      'post_content' => $desc,
                                      'post_status'  => 'publish',
                                      'post_author'  => 1,
                                      'post_date'    => $date
                                  );
                                $post_id = wp_insert_post( $new );

                            }


                      }


                    }
                    
                    $data['status']  = true;
                    $data['msg'] = "Post sync successfully! ";

                }else{ 
                        $data['status']  = false;
                        $data['msg'] = 'Invalid API key or channel ID.'; 
                    
                }

            }else{
                   $data['status']  = false;
                   $data['msg'] = 'Invalid API key or channel ID.';
            }
           

          }else{
                $data['status']  = false;
                $data['msg'] = 'Invalid param try again';
          }

        echo json_encode($data);exit;
            
    }



   /*---------------------------------------------------------
    |  Plugin add admin menu page
    ----------------------------------------------------------*/

    public function yt_wp_add_admin_page(){
        
        add_menu_page( 'WPYoutube Posts', 'WPYoutube Posts', 'manage_options', 'ytwp-post', array($this,'Ytwp_config'),'dashicons-youtube');

    }

    /*---------------------------------------------------------
    |  Admin page callback
    ----------------------------------------------------------*/

    public function Ytwp_config(){

        ob_start();
        require_once YTBTOWP_PLUGIN_DIR . '/inc/template/view/config.php';
        $email_content = ob_get_contents();
        ob_end_clean(); 
        echo  html_entity_decode(esc_html($email_content));

    }

   

    /*-------------------------------------------------*/




}

new YouTubeWP();
