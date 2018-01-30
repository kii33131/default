function selectFileImage(fileObj) {
  var file = fileObj.files;
  //图片方向角 added by lzk
  var Orientation = null;
  var yx_sendimg=$('#yximgclick').find('img').length;

  if (yx_sendimg>=3){
    alert('最多上传三张图片');
    return;
  }
//  console.info(file);
  for (var i=0;i<file.length;i++){
    if (file[i]) {
     // console.log("正在上传,请稍后...");
      var rFilter = /^(image\/jpeg|image\/png)$/i; // 检查图片格式
      EXIF.getData(file[i], function() {
        EXIF.getAllTags(this);
        Orientation = EXIF.getTag(this, 'Orientation');
      });

      var oReader = new FileReader();
      oReader.onload = function(e) {
        var image = new Image();
        image.src = e.target.result;
        image.onload = function() {
          var expectWidth = this.naturalWidth;
          var expectHeight = this.naturalHeight;

          if (this.naturalWidth > this.naturalHeight && this.naturalWidth > 800) {
            expectWidth = 800;
            expectHeight = expectWidth * this.naturalHeight / this.naturalWidth;
          } else if (this.naturalHeight > this.naturalWidth && this.naturalHeight > 1200) {
            expectHeight = 1200;
            expectWidth = expectHeight * this.naturalWidth / this.naturalHeight;
          }
          var canvas = document.createElement("canvas");
          var ctx = canvas.getContext("2d");
          canvas.width = expectWidth;
          canvas.height = expectHeight;
       //   console.log(expectWidth);
          ctx.drawImage(this, 0, 0, expectWidth, expectHeight);
          var base64 = null;
          //修复ios
          if (navigator.userAgent.match(/iphone/i)) {
           // console.log('iphone');
            //alert(expectWidth + ',' + expectHeight);
            //如果方向角不为1，都需要进行旋转 added by lzk
            if(Orientation != "" && Orientation != 1){
              switch(Orientation){
                case 6://需要顺时针（向左）90度旋转
                  rotateImg(this,'left',canvas);
                  break;
                case 8://需要逆时针（向右）90度旋转
                  rotateImg(this,'right',canvas);
                  break;
                case 3://需要180度旋转
                  rotateImg(this,'upside',canvas);
                  break;
              }
            }

            /*var mpImg = new MegaPixImage(image);
             mpImg.render(canvas, {
             maxWidth: 800,
             maxHeight: 1200,
             quality: 0.8,
             orientation: 8
             });*/
            base64 = canvas.toDataURL("image/jpeg", 0.9);
          }else if (navigator.userAgent.match(/Android/i)) {// 修复android
            //var encoder = new JPEGEncoder();
            //base64 = encoder.encode(ctx.getImageData(0, 0, expectWidth, expectHeight), 80);
            base64 = canvas.toDataURL("image/jpeg", 0.8);
          }else{
          //  alert(Orientation);
            if(Orientation != "" && Orientation != 1){
              //alert('旋转处理');
              switch(Orientation){
                case 6://需要顺时针（向左）90度旋转
                  rotateImg(this,'left',canvas);
                  break;
                case 8://需要逆时针（向右）90度旋转
                  rotateImg(this,'right',canvas);
                  break;
                case 3://需要180度旋转
                  rotateImg(this,'upside',canvas);
                  break;
              }
            }
            base64 = canvas.toDataURL("image/jpeg", 0.8);
          //  console.info(woyaode )
          }
          var imgsrc= '<img src="'+base64+'" id="picture" alt="">';
          var $imgdemo = $("<div class='imgparent'></div>");
          var $spandemo=$("<p class='delects'><i class='icon iconfont icon-chadiao'></i></p>");
          $imgdemo.append(imgsrc);
          $imgdemo.append($spandemo);
          $('#yximgclick').append($imgdemo);
        };
      };
      oReader.readAsDataURL(file[i]);
    }
  }

}
//对图片旋转处理 added by lzk
function rotateImg(img, direction,canvas) {
  //alert(img);
  //最小与最大旋转方向，图片旋转4次后回到原方向
  var min_step = 0;
  var max_step = 3;
  //var img = document.getElementById(pid);
  if (img == null)return;
  //img的高度和宽度不能在img元素隐藏后获取，否则会出错
  var height = img.height;
  var width = img.width;
  if (width > height && width > 800) {
    width = 800;
    height = width * img.height / img.width;
  } else if (width > width && width > 1200) {
    height = 1200;
    width = height * img.width / img.height;
  }
  var step = 2;
  if (direction == 'right') {
    step++;
    //旋转到原位置，即超过最大值
    step > max_step && (step = min_step);
  }else if(direction=="upside"){
    1==1;
  } else {
    step--;
    step < min_step && (step = max_step);
  }
  //img.setAttribute('step', step);
  /*var canvas = document.getElementById('pic_' + pid);
   if (canvas == null) {
   img.style.display = 'none';
   canvas = document.createElement('canvas');
   canvas.setAttribute('id', 'pic_' + pid);
   img.parentNode.appendChild(canvas);
   }  */
  //旋转角度以弧度值为参数
  var degree = step * 90 * Math.PI / 180;
  //alert(degree+"---"+direction);
  var ctx = canvas.getContext('2d');

  switch (step) {
    case 0:
      canvas.width = width;
      canvas.height = height;

      ctx.drawImage(img, 0, 0,width,height);
      break;
    case 1:
      canvas.width = height;
      canvas.height = width;
      ctx.rotate(degree);
      ctx.drawImage(img, 0, -height,width,height);
      break;
    case 2:
      canvas.width = width;
      canvas.height = height;
      ctx.rotate(degree);
      ctx.drawImage(img, -width, -height,width,height);
      break;
    case 3:
      canvas.width = height;
      canvas.height = width;
      ctx.rotate(degree);
      ctx.drawImage(img, -width, 0,width,height);
      break;
  }
}
