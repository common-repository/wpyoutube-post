 /*-------------------------------------------------------------
  |  Intialize datatable
  -------------------------------------------------------------*/

  jQuery(document).ready(function () {
         

      jQuery('#loader').hide();
        
      /*-------------------------------------------------------------
      |  setting Form validation
      -------------------------------------------------------------*/

      jQuery("#update_config").validate({
          // Specify validation rules
          rules: {
            yt_chl_id: "required",
            api_key: "required",
            
          },
          messages: {
            yt_chl_id: {
            required: "Please enter Youtube Chanel Id",
           },
           api_key: {
            required: "Please enter Youtube Api Key",
           },      

          },
        
        });

      /*-------------------------------------------------------------
      |  Connect CRM with List
      -------------------------------------------------------------*/

      jQuery("#get_list").click(function(){

         
         var list_key  = '';
         var yt_chl_id =  jQuery('#yt_chl_id').val();
         var api_key   =  jQuery('#api_key').val();
         

        if(yt_chl_id && api_key){
                 
             jQuery('#loader').show();

             jQuery.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "check_credentials","yt_chl_id":yt_chl_id,"api_key":api_key},
                dataType:"json",
                success: function(res) {
                  jQuery('#loader').hide();
                  
                  if(res.status == true){
                    jQuery("#get_list").attr('disabled','disabled');
                    jQuery("#get_list").removeAttr('id');
                    toastr.success(res.msg);
                    var html = "&nbsp;&nbsp;<button type='button' id='sync_youtube_post' class='btn btn-success mt-2'>Sync Youtube Post</button>";
                    jQuery("#reset_config").after(html);
                    location.reload();
                    
                  }else{

                    jQuery("#msg_error").removeAttr('style');
                    jQuery("#msg_error").addClass('error');
                    jQuery("#msg_error").text(res.msg);
                    jQuery(".show_error").removeClass('hide');
                    
                  }
                }
            });

       }

      })







   


      


      /*-------------------------------------------------------------
      |  Reset CRM Config
      -------------------------------------------------------------*/

      jQuery("#reset_config").click(function(){

        if(confirm("Are you sure you want to reset details ?")){

          jQuery('#loader').show();

             jQuery.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "reset_crm_config","token":"reset"},
                dataType:"json",
                success: function(res) {
                  
                    jQuery('#loader').hide();
                    toastr.success(res.msg);
                    location.reload();
                  
                }
            });
 

        }else{

          return false;
        }

      });



      /*-------------------------------------------------------------
      |  Sunc Post from youtube 
      -------------------------------------------------------------*/

      jQuery("#sync_youtube_post").click(function(){

        
                 
             jQuery('#loader').show();

             jQuery.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "sync_youtube_post","sync":true},
                dataType:"json",
                success: function(res) {
                  jQuery('#loader').hide();
                  
                  if(res.status == true){
                    toastr.success(res.msg);
                    window.location.href=admin_posts;
                    
                  }else{

                    jQuery("#msg_error").removeAttr('style');
                    jQuery("#msg_error").addClass('error');
                    jQuery("#msg_error").text(res.msg);
                    jQuery(".show_error").removeClass('hide');
                    
                  }
                }
            });


      })

     

      



 });    