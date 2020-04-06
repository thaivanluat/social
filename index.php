<?php
include "includes/header.php";
include "includes/classes/User.php";
include "includes/classes/Post.php";

if(isset($_POST['post'])) {
    $post = new Post($con, $userId);
    $post->submitPost($_POST['post_text'], $userId);
    header("Location: ./");
}
?>
        <div class="user_details column">
            <a href="<?php echo $user['id']; ?>"><img class="avatar" src="<?php echo $user['avatar'] ?>"></a>

            <div class="user_infomation">
                <a href="<?php echo $user['id']; ?>">
                    <?php
                        echo $user['first_name']." ".$user['last_name']; 
                    ?>
                </a>
                <br>
                <?php
                    echo "Posts: ".$user['num_posts']. "<br>";
                ?>
            </div>
            
        </div>

        <div class="main_column column">
            <form action="index.php" method="POST" class="post_form">
                <textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
                <input type="submit" name="post" id="post_button" value="Post" disabled>
                <hr>
            </form>
            
            <div class="posts_area">
                <?php
                    $post = new Post($con, $userId);
                    $post->loadPost();
                ?>
            </div>
            <div class="loading_area">
                <img src="assets/images/icons/loading.gif" id="loader">
                <input type='hidden' class='loadMore' value='true'><p class="noMorePosts" style='text-align: center; display: none;'> No more posts to show! </p>
            </div>
            <input type="hidden" id="user_id" value="<?php echo $userId; ?>">
            <input type="hidden" id="page_no" value="1">
        </div>

        <div class="main-loader" style="display: none;">
            <img src="assets/images/icons/main-loading.gif" style="height: 60px;" class="loader">
        </div>
    </div>

    <!-- JS -->
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/index.js"></script>
</body>
</html>