<div>
    <form action="/?action=friends" method="post" id="userSearch">
        <input type="text" placeholder="Rozpocznij wyszukiwanie swoich znajomych tutaj" name="searchQuery" id="search">
        <button type="submit" form="userSearch">Szukaj</button>

    </form>

    Twoi znajomi
    <hr>
    <div class="friends">
        <?php foreach($params['friends'] as $friend): ?>
            <a href="/?action=profile&id=<?php echo $friend['user_id']; ?>" class="user_profile_href">

                    <div class="profile_images">
                        <div class="profile_images_background" style="background-image: url(data:image/jpg;charset=utf8;base64,<?php echo base64_encode($friend['backgroundPhoto']); ?>);">
                            <div class="profile_images_photo">
                                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($friend['profilePhoto']); ?>">
                            </div>
                        </div>
                    </div>
                    <?php echo $friend['name'] . " " . $friend['surname'] ?>
            </a>
            <?php endforeach ?>
    </div>

    Wszyscy użytkownicy
    <hr>
    <div class="friends">
        <?php if($params['users'] != null): ?>
            <?php foreach($params['users'] as $user): ?>
            <a href="/?action=profile&id=<?php echo $user['user_id']; ?>" class="user_profile_href">

                    <div class="profile_images">
                        <div class="profile_images_background" style="background-image: url(data:image/jpg;charset=utf8;base64,<?php echo base64_encode($user['backgroundPhoto']); ?>);">
                            <div class="profile_images_photo">
                                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($user['profilePhoto']); ?>">
                            </div>
                        </div>
                    </div>
                    <?php echo $user['name'] . " " . $user['surname'] ?>
            </a>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>