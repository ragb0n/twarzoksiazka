<div>
    <div class="side_content">
        <div>
            <div class="profile_images">
                <div class="profile_images_background" style="background-image: url(data:image/jpg;charset=utf8;base64,<?php echo base64_encode($params['backgroundPhoto']['image']); ?>);">
                    <div class="profile_images_photo">
                        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($params['profilePhoto']['image']); ?>">
                    </div>
                </div>
            </div>
            <div class="profile_welcome">
                Dzień dobry,
                <span class="profile_name"><?php echo $params['logged_user_name']; ?></span>
            </div>
        </div>
        <div>
            <a href="">
                <div class="side_menu_tile">
                <i class="fas fa-bell"></i> Powiadomienia
                </div>
            </a>
        </div>
    </div>
    <div>
        <div class="post post_create">
            <div class="post_info">
                <div class="post_info_content post_author_image">
                    <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($params['profilePhoto']['image']); ?>">
                </div>
                <div class="post_info_content">
                    <div class="post_author">
                        <?php echo $params['logged_user_name'] . ' ' . $params['logged_user_surname']; ?>
                    </div>
                    <div class="post_date">
                        <script>
                            var today = new Date();
                            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                            var time = today.getHours() + ":" + today.getMinutes();
                            var dateTime = date+' '+time;
                            document.write(dateTime);
                        </script>
                    </div>
                </div>
            </div>
            <div class="post_content">
                <form class="post_create_form" action="/?action=main" method="post" id="new_post_form">
                <textarea name="new_post_text" form="new_post_form" placeholder="Podziel się czymś ze swoimi znajomymi!"></textarea>   
                </br>
                <button type="submit" form="new_post_form">Opublikuj</button>
                </form>
            </div>
        </div>
        <?php foreach($params['posts'] ?? [] as $post): ?>
        <div class="post" id="postId=<?php echo $post['post_id']; ?>">
            <div class="post_info">
                <div class="post_info_content post_author_image">
                    <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($post['authorPhoto']); ?>">
                </div>
                <div class="post_info_content post_info_block">
                    <div class="post_author">
                        <a href="/?action=profile&id=<?php echo $post['author_id']; ?>"><?php echo htmlentities($post['name'] . " " . $post['surname']); ?></a>
                    </div>
                    <div class="post_date">
                        <?php echo htmlentities($post['creation_date']); ?>
                    </div>
                </div>
                <?php if($post['author_id'] == $_SESSION['id']): ?>
                    <div class="post_info_content post_options">
                        <form method="post" id="postEdit">
                            <input type="hidden" name="postEdit" value="edit">
                            <button type="submit" form="postEdit" class="post_option">
                                Edytuj post
                            </button>
                        </form>
                        <form method="post" id="postDelete">
                            <input type="hidden" name="postDelete" value="<?php echo $post['post_id'] ?>">
                            <button onclick="confirm('Na pewno chcesz usunąc tego posta?')" type="submit" form="postDelete" class="post_option">
                                Usuń post
                            </button>
                        </form>

                    </div>
                <?php endif; ?>
            </div>
            <div class="post_content">
                <?php echo nl2br(htmlentities($post['post_text'])); ?>
            </div>
            <div class="reactions-counter">
                    <button onclick="sendReact(<?php echo $post['post_id']; ?>)">
                        Daję lajka! <i class="fas fa-thumbs-up" style="color: #103A6E; padding: 0 0.5vw 0 0.5vw;"></i><span id="reactionCounter"></span><?php echo (htmlentities($post['reactions'])); ?>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div> 
</div>

<script>
    function sendReact(postId){
        $.ajax({
            type: 'POST',
            data: `react=${postId}`,
            url: '/?action=main',
            success: function() {   
                location.reload();
            }
        });
    }
</script>

