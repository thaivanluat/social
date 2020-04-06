$(function(){
    $('#post_text').on('keyup',function() {
        let postTextValue = $('#post_text').val();

        postTextValue =  $.trim(postTextValue);
        if(postTextValue != '') {
            $('#post_button').attr('disabled', false);
        }
        else {
            $('#post_button').attr('disabled', true);
        }
    });

    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 1) {
            var nextPage = parseInt($('#page_no').val())+1;
            var userId = $('#user_id').val();
            var loadMore = $('.loadMore').val();
            if(loadMore == "true") {
                $("#loader").show();
                $.ajax({
                    type: 'POST',
                    url: 'includes/handlers/ajax_load_posts.php',
                    data: {
                        page_no: nextPage,
                        user_id: userId
                    },
                    success: function(response){
                        if(response != ''){							 
                            $('.posts_area').append(response);
                            $('#page_no').val(nextPage);
                            $("#loader").hide();
                        } else {								 
                            $("#loader").hide();
                            $(".noMorePosts").show();
                            $('.loadMore').val('false');
                        }
                    }
                });            
            }
            
        }
    });
  
    $('.like-button').on('click', function() {
        $(this).toggleClass("active");
        let likeCount = $(this).siblings('span').text();
        let userId = $('#user_id').val();
        let postId = $(this).parent().siblings('.post_id').val();

        if($(this).hasClass("active")) {
            likeCount++;
            $(this).siblings('span').text(likeCount);

            $.ajax({
                type: 'GET',
                url: 'like.php',
                data: "type=like&user_id=" + userId + "&post_id=" + postId,
                success: function(response){
                    if(response != true){							 							 
                        alert("Error occurred!");
                    }
                },
                error: function() {
                    alert("Error occurred!");
                }
            });  
        }
        else {
            likeCount--;
            $(this).siblings('span').text(likeCount);
            
            $.ajax({
                type: 'GET',
                url: 'like.php',
                data: "type=unlike&user_id=" + userId + "&post_id=" + postId,
                success: function(response){
                    if(response != true){							 								 
                        alert("Error occurred!");
                    }
                },
                error: function() {
                    alert("Error occurred!");
                }
            }); 
        }
    });

    $('.btnHidePost').on('click', function() {
        let userId = $('#user_id').val();
        let postId = $(this).closest('.posted_by').siblings('.post_id').val();
        let buttonElement = $(this);
        let loadingIcon = $('.main-loader');

        loadingIcon.show();
        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_action_post.php',
            dataType: "json",
            data: {
                action: 'hide',
                user_id: userId,
                post_id: postId
            },
            success: function(response){
                loadingIcon.hide();
                if(response.success){							 							     
                    buttonElement.closest('.status_post').hide();
                    buttonElement.closest('.status_post').next('.post_comment').hide();
                }
                else {
                    alert("Error occurred!");
                }
            },
            error: function() {
                alert("Error occurred!");
            }
        });
        
        return false;
    });

    $('.btnDeletePost').on('click', function() {
        let userId = $('#user_id').val();
        let postId = $(this).closest('.posted_by').siblings('.post_id').val();
        let buttonElement = $(this);
        let loadingIcon = $('.main-loader');

        loadingIcon.show();
        $.ajax({
            type: 'POST',
            url: 'includes/handlers/ajax_action_post.php',
            dataType: "json",
            data: {
                action: 'delete',
                user_id: userId,
                post_id: postId
            },
            success: function(response){
                loadingIcon.hide();
                if(response.success){							 							     
                    buttonElement.closest('.status_post').hide();
                    buttonElement.closest('.status_post').next('.post_comment').hide();
                }
                else {
                    alert("Error occurred!");
                }
            },
            error: function() {
                alert("Error occurred!");
            }
        });

        return false;
    });
});