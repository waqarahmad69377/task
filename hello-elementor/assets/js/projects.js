

$(document).ready(function(){
    // get Architecture post from projects 
    $.ajax({
        method:'GET',
        url: ajax_projects.ajax_url,
        dataType:'json',
        data:{
            'action':'ajax_projects',
        },
        success:function(res){

            var i;
            dataCount = res.data.length;
            for(i =0;i < dataCount;i++){
                var results = " <div class='project-col'><div class='project-card'><div class='title'><a href="+res.data[i].link+"><h2>"+res.data[i].title+"</h2></a></div><div class='button'><a href="+res.data[i].link+"><button>Read more</button></a></div></div></div>";
                $('#projects').append(results);
            }
        },
        error:function(res){
            console.log(res);
        }
    });
    var timeRuns = 0;
   var setTime =  setInterval(function(){
        timeRuns+=1;
        console.log(timeRuns);
        if(timeRuns <= 5){
            $.ajax({
                methods:'GET',
                url: ajax_projects.ajax_url,
                dataType:'json',
                data:{
                    'action':'get_quotes',
                },
                success:function(res){
                    console.log(res[0]);
                    console.log(res[0].body);
                    var i;
                    dataCount = res.length;
                    for(i =0;i < dataCount;i++){
                        var results = " <div class='project-col'><div class='project-card'><div class='title'><h2>"+res[i].body+"</h2></div></div></div>";
                        $('#quotes').append(results);
                    }
                },
                error:function(res){
                    console.log(res);
                }
            });
            
        }else{
            clearInterval(setTime);
        }
        
    }, 500);
   

});