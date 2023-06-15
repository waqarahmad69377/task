

$(document).ready(function(){
    // get Architecture post from projects 
try{

}catch(err){
    console.log(err);
}
    $.ajax({
        method:'GET',
        url: ajax_projects.ajax_url,
        dataType:'json',
        data:{
            'action':'ajax_projects',

        },
        success:function(res){
            console.log(res);
        },
        error:function(res){
            console.log(res);
        }
    })


});