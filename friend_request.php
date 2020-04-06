<?php
include "includes/header.php";
include "includes/classes/User.php";
?>
<style>
    .wrapper {
        margin: 0px;
    }
</style>
<div class="main_column column" id="main_column">
    <h4>Friend Request</h4>
    <?php
        $sql = "SELECT * FROM friend_request WHERE to_user = '$userId'";
        $query = mysqli_query($con, $sql);

        if(mysqli_num_rows($query) == 0) {
            echo "You have no friend requests at this time";
        }
        else {

            while($row = mysqli_fetch_array($query)) {
                $userFrom = $row['from_user'];
                $userTo = $row['to_user'];

                $userFromObj = new User($con, $userFrom);
                echo "<div class='friend_request'>";
                echo "<span>".$userFromObj->getFirstAndLastName() . " sent you a friend request!</span>";
    ?>
    <form action="" method="POST" class="response-request" style="margin: 10px 0px;">
        <input type="hidden" class="user_to" value="<?php echo $userTo; ?>">
        <input type="hidden" class="user_from" value="<?php echo $userFrom; ?>">
        <input type="submit" name="accept_request" class="accept-button" value="Accept">
        <input type="submit" name="ignore_request" class="ignore-button" value="Ignore">
    </form>
    </div>
    <br>
    <img src="assets/images/icons/loading.gif" class="loader" style="display: none;">
    <span class="message" style="display: none;">
        
    </span>
    
    <hr>
    <?php
        }
    }
    ?>
</div>

<script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>

<script>
    $('.response-request').on('submit', function(e) {
        e.preventDefault();
    });

    $('.accept-button').on('click', function() {
        let userToId = $(this).siblings('.user_to').val();
        let userFromId = $(this).siblings('.user_from').val();
        let buttonElement = $(this);

        $(this).closest('.friend_request').siblings('.loader').show();
        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_response_friend_request.php',
            dataType: "json",
            data: {
                response: 'accept',
                to_user_id: userToId,
                from_user_id: userFromId
            },
            success: function(response){
                if(response.success){							 							     
                    buttonElement.closest('.friend_request').hide();
                    buttonElement.closest('.friend_request').siblings('.message').text("You are friends now!").show();
                    buttonElement.closest('.friend_request').siblings('.loader').hide();
                }
                else {
                    alert("Error occurred!");
                }
            },
            error: function() {
                alert("Error occurred!");
            }
        });  

    });

    $('.ignore-button').on('click', function() {
        let userToId = $(this).siblings('.user_to').val();
        let userFromId = $(this).siblings('.user_from').val();
        let buttonElement = $(this);

        $(this).closest('.friend_request').siblings('.loader').show();
        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_response_friend_request.php',
            dataType: "json",
            data: {
                response: 'ignore',
                to_user_id: userToId,
                from_user_id: userFromId
            },
            success: function(response){
                if(response.success){							 							         
                    buttonElement.closest('.friend_request').hide();
                    buttonElement.closest('.friend_request').siblings('.loader').hide();
                    buttonElement.closest('.friend_request').siblings('.message').text("Request ignored!").show();
                }
                else {
                    alert("Error occurred!");
                }
            },
            error: function() {
                alert("Error occurred!");
            }
        });  
    });
</script>