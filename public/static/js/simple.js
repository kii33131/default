/**
 * Created by Administrator on 2016/10/8.
 */
$(function(){
    var s_height=$('.swiper-container').height();
    console.info('ss',s_height);
    $('.swiper-slide').css({"height":s_height+"px"});
    //moderoomlists(1)
    $('.rhz-header').attr('page',1)
    waterfall ()
    var rhzchoiceqie=true;
    $('.rhz-dectial_main').on('click', function () {

        if (rhzchoiceqie){
            $('.rhz-product_coutent').hide();
            $('.rhz-shop_product').show();
            rhzchoiceqie=false;
        }else {
            $('.rhz-product_coutent').show();
            $('.rhz-shop_product').hide();
            rhzchoiceqie=true;
        }
    })
    /*超出字段截取*/
    var rhzstr = $(".rhz-product_count").text().substr(0,45);
    $(".rhz-product_count").text(rhzstr);
    /*点击选择选项弹出*/
    $(".rhz-nb_left,.rhz-nb_right").on("click",function(event){


        if ($(this).hasClass('rhz-after')){
            $(this).removeClass('rhz-after');
            $(this).find('.rhz-nblist').hide();
        }else{
            $(this).addClass('rhz-after').siblings().removeClass('rhz-after');
            $(this).siblings().find('.rhz-nblist').hide();
            $(this).find('.rhz-nblist').show();
        }

        //alert($(this).attr('key'))
        event.stopPropagation();
    });
    /*当选择选中的时候变色*/
    var rhz_choicelist=$('.rhz-choicelist').children();
    $('.rhz-choice_all').on("click", function () {
        if($(this).hasClass('rhz-choice')){

        }else {
            $(this).addClass('rhz-choice');

            for(var i=0; i<rhz_choicelist.length; i++){
                $(rhz_choicelist[i]).removeClass('rhz-choice');
            }
        }
    });

    for(var i=0; i<rhz_choicelist.length; i++){
        $(rhz_choicelist[i]).on("click",function(){
            if($(this).hasClass('rhz-choice')){

            }else {
                //alert( $(this).attr('key'))
                $(this).addClass('rhz-choice').siblings().removeClass('rhz-choice');
                $('.rhz-choice_all').removeClass('rhz-choice');
            }
        });
    }


    $('.rhz-chioce_list_l li').click(function(){
        waterfall();
        $('.rhz-header').attr('cate1',$(this).attr('key'))
        $('.rhz-header').attr('page',1)
        var aa = $('.rhz-header').attr('cate2');
        if(aa==undefined){
            aa ='';
        }
        var $attes = $(this).attr('key') + ',' + aa;
        moderoomlists(1,$attes);
        setTimeout(function(){
            waterfall();
        },500);
        waterfall();
    });

    $('.rhz-chioce_list_l .rhz-choice_all').click(function(){
        waterfall();
        var $attes =  $('.rhz-header').attr('cate1') + ',' ;

        moderoomlists(1,$attes);
        setTimeout(function(){
            waterfall();
        },500);
        waterfall();
        $('.rhz-header').attr('cate1','');
        $('.rhz-header').attr('page',1);
    })

    $('.rhz-chioce_list_r .rhz-choice_all').click(function(){
        waterfall();
        var $attes = ',' + $('.rhz-header').attr('cate2');

        moderoomlists(1,$attes);
        setTimeout(function(){
            waterfall();
        },500);
        $('.rhz-header').attr('cate2','');
        $('.rhz-header').attr('page',1);
        waterfall();
    })

    $('.rhz-chioce_list_r li').click(function(){
        waterfall();
        $('.rhz-header').attr('cate2',$(this).attr('key'));
        $('.rhz-header').attr('page',1);
        var aa = $('.rhz-header').attr('cate1');
        if(aa==undefined){
            aa ='';
        }
        var $attes =  aa+ ',' + $(this).attr('key') ;
        moderoomlists(1,$attes);
        setTimeout(function(){
            waterfall();
        },500);
        waterfall();
    })

    /*滚动条滚到底部*/
    $(window).scroll(function () {
        waterfall();
        var dataInt = { "data":[{"src":"2.jpg"}]}
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
            /*
            $.each(dataInt.data,function (key,value) {
                var oBox=$("<div>").addClass("rhz-box").appendTo($("#rhz-main"));

                var oPic=$("<div>").addClass('rhz-pic').appendTo($(oBox));
                var oPic=$("<p>").addClass('rhz-btcont').appendTo($(oBox));
                $("<img>").attr("src","img/"+$(value).attr("src")).appendTo(oPic);
                $("<p>").html('搞笑').appendTo(oPic);
            });
            */
            var $page =parseInt($('.rhz-header').attr('page')) +1;
            var cate1 =$('.rhz-header').attr('cate1')
            var cate2 =$('.rhz-header').attr('cate2')
            //（5,17,）
            var $attes = cate1+','+cate2;
            if(cate1==undefined) var $attes = ','+cate2;
            if(cate2==undefined) var $attes = cate1+',';
            if(cate1==undefined && cate2==undefined) var $attes='';
            //if()
            moderoomlistsw($page,$attes);
            waterfall();
        }
    });
})
/*流式布局主函数;*/
function waterfall () {
    var $boxs=$("#rhz-main>div");
    var w=$boxs.eq(0).outerWidth();
    var cols=Math.floor($(window).width()/w);
    $("#rhz-main").width(w*cols).css("margin",".9rem auto");
    var hArr=[];
    $boxs.each(function (index,value) {
        var h=$boxs.eq(index).outerHeight();
        if (index<cols) {
            hArr[index]=h;
        } else{
            var minH=Math.min.apply(null,hArr);
            var minHIndex=$.inArray(minH,hArr);
            $(value).css({
                "position":"absolute",
                "top":minH+"px",
                "left":minHIndex*w+"px"
            });
            hArr[minHIndex]+=$boxs.eq(index).outerHeight();
        };
    });
};
/* 检测是否滚到底部*/
function checkScrollSlide () {
    var $lastBox=$("#rhz-main>div").last();
    var lastBoxDis=$lastBox.offset().top+Math.floor($lastBox.outerHeight()/2);
    var scrollTop=$(window).scrollTop();
    var documentH=$(window).height();
    return (lastBoxDis<scrollTop+documentH)?true:false;
}
//var api = 'http://www.yuexing.com/';
 // 筛选条件 开始载入
function moderoomlists($page,$attes) {
    waterfall ();
    var data = '';
    if($attes){
        var data = {page:$page,attrs:$attes}
    }else{

        var data = {page:$page}
    }
    $.ajax({
        type: "GET",
        url: 'index.php?mod=moderoom&act=moderoomlists'+'&sign='+sign,
        data: data,
        //cache: false,
        dataType: 'json',
        //jsonp:"callback",
        success: function(data){
           // console.log(data)
            if(data.error==0){

                var s = '';
                var urls = '/index.php?mod=simple&act=moderoomdetail&id=';
                for(var b in data.data.modelData) {

                    var mall = data.data.modelData[b];

                    s += '<div class="rhz-box"><div class="rhz-pic"><a href="'+urls+mall.id+'&title='+mall.title  +'"> <img src="'+mall.img  + '" alt=""></a>'

                    s += '</div><p class="rhz-btcont">'+mall.title +'</p></div>';
                }
                waterfall ();
                $('#rhz-main').html(s);
                waterfall ();
                //alert(data.data.modelData.page)
                $('.rhz-header').attr('page',data.data.page);
                waterfall ();
            }
        },

    });

}


function moderoomlistsw($page,$attes) {
    waterfall ();
    var data = '';
    if($attes){
        var data = {page:$page,attrs:$attes}
    }else{

        var data = {page:$page}
    }
    $.ajax({
        type: "GET",
        url:'/index.php?mod=moderoom&act=moderoomlists'+'&sign='+sign,
        data: data,
        //cache: false,
        dataType: 'json',
        //jsonp:"callback",
        success: function(data){
             console.log(data)
            if(data.error==0){

                var s = '';
                var urls = '/index.php?mod=simple&act=moderoomdetail&id=';
                for(var b in data.data.modelData) {

                    var mall = data.data.modelData[b];

                    s += '<div class="rhz-box"><div class="rhz-pic"><a href="'+urls+mall.id+'&title='+mall.title +'"> <img src="'+mall.img  + '" alt=""></a>'

                    s += '</div><p class="rhz-btcont">'+mall.title +'</p></div>'
                }
                $("#rhz-main").append(s);
                waterfall ();
                $('.rhz-header').attr('page',data.data.page);
                //alert(s)
                waterfall ();
            }
        },

    });
}
