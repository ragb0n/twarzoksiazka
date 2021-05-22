<div>
    <form action="/?action=friends" method="post" id="userSearch">
        <input type="text" placeholder="Rozpocznij wyszukiwanie swoich znajomych tutaj" name="searchQuery" id="search">
        <button type="submit" form="userSearch" style="height: 4vh; width: 100%; font-size: 1vw;">Szukaj osoby</button>
    </form>
    <span class="friends_header">Twoi znajomi</span>
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
                <p><?php echo $friend['name'] . " " . $friend['surname'] ?></p>
            </a>
        <?php endforeach ?>
    </div>
    <span class="friends_header">Wszyscy użytkownicy</span>
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
                    <p><?php echo $user['name'] . " " . $user['surname'] ?></p>
                </a>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>