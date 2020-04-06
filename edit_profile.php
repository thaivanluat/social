<?php
include "includes/header.php";
include "includes/classes/User.php";
include "includes/classes/Post.php";

if(isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];

    // Get current user
    $user = new User($con, $userId);
    $isClosed = $user->isClosed();

    $userInfo = $user->getEditableUserInfo();
}
else {
    header('Location: 404.php');
}
?>
<style>
    .wrapper {
        margin: 0px;
    }

    .btn {
        cursor: pointer;
    }

    .loading, .main-loading {
        width: 100%;
        text-align: center;
    }

    .badge {
        font-size: 20px;
    }

    .loading * {
        display: none;
    }   

    .main-loading * {
        display: none;
    }

    .message {
        color: white;
        margin: auto;
    }

    .spinner-border {
        margin: auto;
    }
</style>
<div class="main_column column" id="main_column">
    <h4>Edit profile</h4>
    <hr>
    <h5>Profile avatar</h5>
    <input type="hidden" id="user_id" value="<?php echo $userId; ?>">
    <div>
        <img class="avatar" src="<?php echo $user->getProfilePic(); ?>">
        <a href="upload.php" class="btn btn-light">Upload profile avatar</a>
    </div>
    <hr>
    <h5>Basic Infomation</h5>
    <form method="POST">
        <div class="form-group">
            <label for="">First Name</label>
            <input type="text" class="form-control" name="first-name" value="<?php echo $userInfo['first_name']; ?>">
        </div>
        <div class="form-group">
            <label for="">Last Name</label>
            <input type="text" class="form-control" name="last-name" value="<?php echo $userInfo['last_name']; ?>">
        </div>
        <div class="form-group">
            <label for="">Birthday</label>
            <input type="date" class="form-control" name="birthday" value="<?php echo $userInfo['birthday']; ?>">
        </div>
        <div class="form-group">
            <label for="">Sex</label><br>
            <div class="form-check form-check-inline" style="padding-left: 25px;">
                <input class="form-check-input" type="radio" name="sex" value="female" <?php if($userInfo['sex'] == 'female') echo 'checked'; ?>>
                <label class="form-check-label" for="exampleRadios1" style="padding: 0px;">
                    Female
                </label>
            </div>
            <div class="form-check form-check-inline" style="padding-left: 25px;">
                <input class="form-check-input" type="radio" name="sex" value="male" <?php if($userInfo['sex'] == 'male') echo 'checked'; ?>>
                <label class="form-check-label" for="exampleRadios1" style="padding: 0px;">
                    Male
                </label>
            </div>  
        </div>
        <hr>
        <h5>Other action</h5>
        <div class="form-group">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#changePassword">Change Password</button>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-<?php if($isClosed) echo 'warning'; else echo 'danger'?>" data-toggle="modal" data-target="#closeUser">
            <?php
                if($isClosed) {
                    echo 'Open Account';            
                }    
                else {
                    echo 'Close Account';
                } 
            ?>
            </button>
            <?php
                if(!$isClosed)
                    echo '<small class="form-text text-muted">Your post will no longer appear</small>';
            ?>
        </div>
        <div class="main-loading" style="padding-bottom: 10px;">
            <span class="badge badge-pill message"></span>
            <div class="spinner-border"></div>
        </div>
        <button type="button" class="btn btn-primary" id="editProfile">Edit</button>
    </form>
</div>


    <!-- Change Password Modal -->
    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="postForm" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postForm">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="profile_post" action="" method="POST">
                        <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>    

                        <div class="form-group">
                            <label for="exampleInputPassword1">Re-Password</label>
                            <input type="password" class="form-control" name="re-password" placeholder="Re-password">
                        </div>             
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="loading">
                        <span class="badge badge-pill message">Fuck</span>
                        <div class="spinner-border"></div>
                    </div>
                    <button type="button" class="btn btn-primary" id="btnSavePassword" disabled>Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Close User Modal -->
    <div class="modal fade" id="closeUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php if($isClosed) echo 'Open Account'; else echo 'Close Account';?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if(!$isClosed) echo 'Your account will be closed';?>
            </div>
            
            <div class="modal-footer">
                <div class="loading">
                    <span class="badge badge-pill message"></span>
                    <div class="spinner-border"></div>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <?php
                    if($isClosed) {
                        echo '<button type="button" class="btn btn-warning" id="btnOpenAccount">Open Account</button>  ';
                    }
                    else {
                        echo '<button type="button" class="btn btn-danger" id="btnCloseAccount">Close Account</button>';
                    }
                ?>
            </div>
            </div>
        </div>
    </div>


    <!-- JS -->
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/js/edit_profile.js"></script>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>