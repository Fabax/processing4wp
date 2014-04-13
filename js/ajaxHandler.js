jQuery(document).ready(function($) {

    var fbAdminJs = {

        init: function() {
            this.checkDir();
        },

        checkDir: function() {
            if($("#fb_sketch_title").val() == ""){
                $("#fb_form_bottom").hide();
                $("#fb_sketch_options .hndle").css("background-color","red");

            }else{
                $("#fb_form_bottom").show();
                var sketchTitle = $("#fb_sketch_title").val();
                var callfunction = "getFolderDir";
                 $.ajax({
                    url: ajaxurl,
                    data: {'callfunction':callfunction,'removeSketch':'yes', 'sketchTitle':sketchTitle},
                    success:function(data) {
                        // This outputs the result of the ajax request
                        if(data !=0){
                            if(data == "true0"){
                                $("#fb_sketch_options .hndle").css("background-color","green");
                                $("#fb_remove_sketch").show();
                                $("#fb_zip_file").hide();
                                $(".fb_zip_fin_text").hide();
                            
                            }else if (data =="false0"){
                                $("#fb_sketch_options .hndle").css("background-color","red");
                                $("#fb_remove_sketch").hide();
                                $("#fb_zip_file").show();
                                $(".fb_zip_fin_text").show();
         
                            }
                            
                        }else{
                            alert("there is no sketch to remove");
                        }
                        
                    },
                    error: function(errorThrown){
                        console.log(errorThrown);
                    }
                });
            }
        },

        removeSketch: function(){
            var sketchTitle = $("#fb_sketch_title").val();
            var callfunction = "removeSketch";
             $.ajax({
                url: ajaxurl,
                data: {'callfunction':callfunction,'removeSketch':'yes', 'sketchTitle':sketchTitle},
                success:function(data) {
                    // This outputs the result of the ajax request
                    if(data !=0){
                        alert(data);
                    }else{
                        alert("there is no sketch to remove");
                    }
                    
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        }
    };

    fbAdminJs.init();


  

    // We'll pass this variable to the PHP function example_ajax_request
    $("#fb_remove_sketch").on('click',function(){
        fbAdminJs.removeSketch();
    }) ;  

    $("#fb_sketch_title").live('change',function(){
        fbAdminJs.checkDir();
    });
    
});

