$(function(){
    $('#whcallme_cta').click(function(){
        if($("#whcallme_frame").hasClass('active')){
            $("#whcallme_frame").removeClass('active');
        }else{
            $("#whcallme_frame").addClass('active');
        }
    });
    
    $('.msg-box').click(function(){
        $(this).hide();
    })
});