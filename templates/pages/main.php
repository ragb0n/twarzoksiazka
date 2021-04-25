<div>
<div class="side_content">
        <div class="profile_images">
            <div class="profile_images_background">
                <div class="profile_images_photo">
                    <img src="../../public/images/profile_test.PNG">
                </div>
            </div>
        </div>
        <div class="profile_welcome">
            Dzień dobry,
            <span class="profile_name"><?php echo $params['logged_user_name']; ?></span>
        </div>
    </div>
    <div>
        <div class="post post_create">
            <div class="post_author_image">
                <img src="../../public/images/profile_test.PNG">
            </div>
            <div class="post_info">
                <div class="post_author"><?php echo $params['logged_user_name'] . ' ' . $params['logged_user_surname']; ?></div>
                <div class="post_date">20.04.2021, 19:10</div>
            </div>
            <div class="post_content">
                <form class="post_create_form" action="/?action=main" method="post" id="new_post_form">
                    <textarea name="new_post_text" form="new_post_form">Podziel się czymś ze swoimi znajomymi!</textarea>   
                    </br>
                    <button type="submit" form="new_post_form">Opublikuj</button>
                </form>
            </div>
        </div>
        <?php foreach($params['posts'] ?? [] as $post): ?>
        <div class="post">
            <div class="post_author_image">
                <img src="../../public/images/profile_test.PNG">
            </div>
            <div class="post_info">
                <div class="post_author">Łukasz Wajda</div>
                <div class="post_date"><?php echo htmlentities($post['creation_date']); ?></div>
            </div>
            <div class="post_content">
                <?php echo htmlentities($post['post_text']); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div> 
</div>