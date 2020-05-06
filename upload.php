<?php
include "includes/header.php";
include "includes/classes/User.php";
include "includes/classes/Post.php";

if(isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];

    // Get current user
    $user = new User($con, $userId);
}
else {
    header('Location: 404.php');
}
?>
<style>
    .wrapper {
        margin: 0px;
    }

    .uploaded_image {
        text-align: center;
        margin: 10px;
    }

    .success-message {
        display: none;
        font-size: 25px;
        text-align: center;
    }

    .success-message a {
        color: #61375d;
    } 
</style>
<link rel="stylesheet" type="text/css" href="assets/vendor/croppie/css/croppie.css"/>
<meta http-equiv="Pragma" content="no-cache" />
<div class="main_column column" id="main_column">
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                <h4 class="card-title">Upload profile avatar</h4>
                <input type="file" name="upload_image" id="upload_image"  accept="image/*">
                <br>
                <div class="uploaded_image"></div>
                <div class="success-message">
                    <span class="badge badge-success">Update profile avatar successfully  <a href="edit_profile.php">Go back</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Upload modal -->
    <div id="uploadImageModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload & Crop Image</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>       
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 text-center">
                            <div id="image_demo"></div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success crop_image">Crop & save picture</button>
                        </div>
                        <br>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/js/edit_profile.js"></script>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/croppie/js/croppie.js"></script>

    <script>
        $(document).ready(function() {
            $image_crop = $('#image_demo').croppie({
                enableExif: true,
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'square'
                },
                boundary: {
                    width: 300,
                    height: 300,
                }
            });

            $('#upload_image').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function() {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadImageModal').modal('show');
            });

            $('.crop_image').click(function() {
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(response){
                    $.ajax({
                        url: "includes/handlers/ajax_upload_profile_pic.php",
                        type: "POST",
                        data: {"image": response},
                        success:function(res) {
                            res  = JSON.parse(res);
                            if(res.success) {
                                $('#uploadImageModal').modal('hide');
                                $('.uploaded_image').html(res.image);
                                $('.success-message').show();
                                $('#upload_image').hide();
                            }
                            else {
                                alert("Error occurred!");
                            }    
                        },
                        error: function() {
                            alert("Error occurred!");
                        }
                    })
                })
            });
        });
    </script>
</body>
</html>