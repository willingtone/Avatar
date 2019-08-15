<?php
include_once("../../globals.php");
require_once("$srcdir/patient.inc");
?>

<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Image Upload and Crop</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/layout.css">
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">  
		<link rel="stylesheet" href="cropper/cropper.css">
</head>
    <body class="a2z-wrapper">
      <?php 
    //$pid = query("SELECT pid FROM patient_data");
    $res = sqlStatement("SELECT fname,mname,lname,phone_home,DOB, patient_data.pid,
      snapshot.Image FROM patient_data
      left join snapshot on snapshot.pid=patient_data.pid WHERE patient_data.pid = $pid");
    $result = SqlFetchArray($res); 
    ?>
    <div class="row">
      <div class="col-lg-12">
                  <div class="panel-panel btn-white" style="max-width: 100rem;">
          <div class="card-header" style="color: #ffffff; font-size:30;">Taken Picture for <?php echo $result['fname'] . '&nbsp' . $result['mname'] . '&nbsp;' . $result['lname'] .'&nbsp;' . $result['pid'];?>
          </div>
        </div>
      </div>
    </div>

<!--Start a2z-area-->
<section class="a2z-area">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-4">
				<div class="avatararea">
					<!--h3>Upload and Crop your Avatar</h3-->
					<div class="row">
					<div class="imagearea col-lg-12 col-md-12 col-sm-12">
						<div class="avatarimage" id="drop-area"> 
						<img src="assets/img/avatar.jpg" alt="avatar"  id="avatarimage" />
						<!--p>Drop your avatar here</p-->
						</div>
						<div class="buttonarea"> 
						<!-- <button class="btn upload" data-toggle="modal" data-target="#myModal" onclick="cropImage()"><i class="fa fa-upload"></i> &nbsp; Upload</button> -->
						
						<label class="btn upload"> <i class="fa fa-upload"></i> &nbsp; Upload<input type="file" class="sr-only" id="input" name="image" accept="image/*"></label>
						<button class="btn camera" data-backdrop="static" data-toggle="modal" data-target="#cameraModal"><i class="fa fa-camera"></i> &nbsp;  Camera</button>
						</div>
						<div class="alert" role="alert"></div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

		
<!-- The Make Selection Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Make a selection</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div id="cropimage">
		<img id="imageprev" src="assets/img/bg.png"/>
		</div>
		
		<div class="progress">
		  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
		</div>
      </div>
	
      <!-- Modal footer -->
      <div class="modal-footer">
		<div class="btngroup">
        <button type="button" class="btn upload1 float-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btnsmall" id="rotateL" title="Rotate Left"><i class="fa fa-undo"></i></button>
        <button type="button" class="btn btnsmall" id="rotateR" title="Rotate Right"><i class="fa fa-repeat"></i></button>
		<button type="button" class="btn btnsmall" id="scaleX" title="Flip Horizontal"><i class="fa fa-arrows-h"></i></button>
		<button type="button" class="btn btnsmall" id="scaleY" title="Flip Vertical"><i class="fa fa-arrows-v"></i></button>  
		<button type="button" class="btn btnsmall" id="reset" title="Reset"><i class="fa fa-refresh"></i></button> 
		<button type="button" class="btn camera1 float-right" id="saveAvatar">Save</button>
		</div>
      </div>

    </div>
  </div>
</div>

<!-- The Camera Modal -->
<div class="modal" id="cameraModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $result['fname'] . '&nbsp' . $result['mname'] . '&nbsp;' . $result['lname'] .'&nbsp;' . $result['pid'];?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
			<div id="my_camera"></div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn upload" data-dismiss="modal">Close</button>
        <button type="button" class="btn camera" onclick="take_snapshot()">Take a picture</button>
      </div>

    </div>
  </div>
</div>

<script src="assets/js/jquery-1.12.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/dropzone.js"></script>
<!-- Script -->
<script src="webcamjs/webcam.min.js"></script>
<script src="cropper/cropper.js"></script>


<script language="JavaScript">
		
		// Configure a few settings and attach camera
		function configure(){
			Webcam.set({
				width: 640,
				height: 480,
				image_format: 'jpeg',
				jpeg_quality: 100
			});
			Webcam.attach('#my_camera');
		}
		// A button for taking snaps
		
		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
				// display results in page
				$("#cameraModal").modal('hide');
				$("#myModal").modal({backdrop: "static"});
				$("#cropimage").html('<img id="imageprev" src="'+data_uri+'"/>');
				cropImage();
				//document.getElementById('cropimage').innerHTML = ;
			} );

			Webcam.reset();
		}

		function saveSnap(){
			// Get base64 value from <img id='imageprev'> source
			var base64image =  document.getElementById("imageprev").src;

			 Webcam.upload( base64image, 'upload.php', function(code, text) {
				 console.log('Save successfully');
				 //console.log(text);
            });

		}
	
$('#cameraModal').on('show.bs.modal', function () {
  configure();
})

$('#cameraModal').on('hide.bs.modal', function () {
  Webcam.reset();
  $("#cropimage").html("");
})

$('#myModal').on('hide.bs.modal', function () {
 $("#cropimage").html('<img id="imageprev" src="assets/img/bg.png"/>');
})


/* UPLOAD Image */	
var input = document.getElementById('input');
var $alert = $('.alert');


/* DRAG and DROP File */
$("#drop-area").on('dragenter', function (e){
	e.preventDefault();
});

$("#drop-area").on('dragover', function (e){
	e.preventDefault();
});

$("#drop-area").on('drop', function (e){
	var image = document.querySelector('#imageprev');
	var files = e.originalEvent.dataTransfer.files;
	
	var done = function (url) {
          input.value = '';
          image.src = url;
          $alert.hide();
		  $("#myModal").modal({backdrop: "static"});
		  cropImage();
        };
		
	var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];

          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }	
	
	e.preventDefault();	
	
});

/* INPUT UPLOAD FILE */
input.addEventListener('change', function (e) {
		var image = document.querySelector('#imageprev');
        var files = e.target.files;
        var done = function (url) {
          input.value = '';
          image.src = url;
          $alert.hide();
		  $("#myModal").modal({backdrop: "static"});
		  cropImage();
		  
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];

          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
      });
/* CROP IMAGE AFTER UPLOAD */	
function cropImage() {
      var image = document.querySelector('#imageprev');
      var minAspectRatio = 0.5;
      var maxAspectRatio = 1.5;
	  
      var cropper = new Cropper(image, {
		aspectRatio: 11 /12,  
		minCropBoxWidth: 20,
		minCropBoxHeight: 40,

        ready: function () {
          var cropper = this.cropper;
          var containerData = cropper.getContainerData();
          var cropBoxData = cropper.getCropBoxData();
          var aspectRatio = cropBoxData.width / cropBoxData.height;
          //var aspectRatio = 4 / 3;
          var newCropBoxWidth;
		  cropper.setDragMode("move");
          if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
            newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);

            cropper.setCropBoxData({
              left: (containerData.width - newCropBoxWidth) / 2,
              width: newCropBoxWidth
            });
          }
        },

        cropmove: function () {
          var cropper = this.cropper;
          var cropBoxData = cropper.getCropBoxData();
          var aspectRatio = cropBoxData.width / cropBoxData.height;

          if (aspectRatio < minAspectRatio) {
            cropper.setCropBoxData({
              width: cropBoxData.height * minAspectRatio
            });
          } else if (aspectRatio > maxAspectRatio) {
            cropper.setCropBoxData({
              width: cropBoxData.height * maxAspectRatio
            });
          }
        },
		
		
      });
	  
	  $("#scaleY").click(function(){ 
		var Yscale = cropper.imageData.scaleY;
		if(Yscale==1){ cropper.scaleY(-1); } else {cropper.scaleY(1);};
	  });
	  
	  $("#scaleX").click( function(){ 
		var Xscale = cropper.imageData.scaleX;
		if(Xscale==1){ cropper.scaleX(-1); } else {cropper.scaleX(1);};
	  });
	  
	  $("#rotateR").click(function(){ cropper.rotate(45); });
	  $("#rotateL").click(function(){ cropper.rotate(-45); });
	  $("#reset").click(function(){ cropper.reset(); });
	  
	  $("#saveAvatar").click(function(){
		  var $progress = $('.progress');
		  var $progressBar = $('.progress-bar');
		  var avatar = document.getElementById('avatarimage');
		  var $alert = $('.alert');
          canvas = cropper.getCroppedCanvas({
            width: 220,
            height: 240,
          });
          
          $progress.show();
          $alert.removeClass('alert-success alert-warning');
          canvas.toBlob(function (blob) {
            var formData = new FormData();

            formData.append('avatar', blob, 'avatar.jpg');
            $.ajax('upload.php', {
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,

              xhr: function () {
                var xhr = new XMLHttpRequest();

                xhr.upload.onprogress = function (e) {
                  var percent = '0';
                  var percentage = '0%';

                  if (e.lengthComputable) {
                    percent = Math.round((e.loaded / e.total) * 100);
                    percentage = percent + '%';
                    $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                  }
                };

                return xhr;
              },

              success: function () {
                //$alert.show().addClass('alert-success').text('Upload success');
              },

              error: function () {
                avatar.src = initialAvatarURL;
                $alert.show().addClass('alert-warning').text('Upload error');
              },

              complete: function () {
				$("#myModal").modal('hide');  
                $progress.hide();
				initialAvatarURL = avatar.src;
				avatar.src = canvas.toDataURL();
              },
            });
          });
	  
	  });
};	

</script>
		
</body>
</html>