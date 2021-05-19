<div class = "profile_main">
    <div class="profile_main_container">
        <div class="profile_background_photo" style="background-image: url(data:image/jpg;charset=utf8;base64,<?php echo base64_encode($params['backgroundPhoto']['image']); ?>);">
            <img class="profile_photo" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($params['profilePhoto']['image']); ?>" >
        </div>
        <div class="profile_name_main">
            <?php echo $params['profileData']['name'] . " " . $params['profileData']['surname']; ?>
        </div>
    </div>
    <div class="profile_content_left">
        <div class="profile_info">
            <div class="profile_info_header">
                Informacje
            </div>
            <br>
            <div class="profile_into_data">
                <?php if(!empty($params['profileData']['city'])): ?><div>Mieszka w: <?php echo $params['profileData']['city']; ?></div><?php endif; ?>
                <?php if(!empty($params['profileData']['birth_place'])): ?><div>Pochodzi z: <?php echo $params['profileData']['birth_place']; ?></div><?php endif; ?>
                <div>Urodziny: <?php echo $params['profileData']['birth_date']; ?></div>
                <?php if(!empty($params['profileData']['school'])): ?><div>Szkoła: <?php echo $params['profileData']['school']; ?></div><?php endif; ?>
                <?php if(!empty($params['profileData']['work'])): ?><div>Praca: <?php echo $params['profileData']['work']; ?></div><?php endif; ?>
                <div>Data dołączenia: <?php echo $params['profileData']['creation_date']; ?></div>
                <?php if(!empty($params['profileData']['hobby'])): ?><div>Hobby: <?php echo $params['profileData']['hobby']; ?></div><?php endif; ?>
                <?php if(!empty($params['profileData']['about'])): ?><div>O mnie: <?php echo $params['profileData']['about']; ?></div><?php endif; ?>
            </div>
        </div>
        <div class="profileSideMenu">
            <?php if ($params['profileData']['user_id'] == $_SESSION['id']): ?>
            <a href="/?action=editProfile">
                <div class="profile_page_tile">
                    <i class="fas fa-cog"></i> Edytuj profil
                </div>
            </a>
            <?php else: ?>
                <?php if($params['friendStatus'] == 1): ?>
                    <form method="post" id="inviteButton">
                        <input type="hidden" name="invite" value="delete">
                        <button type="submit" form="inviteButton" class="profile_page_tile">
                            <i class="fas fa-check" style="font-size: 1vw; color: green; padding-right: 1vw; vertical-align: 35%;"></i>
                                <div style="display: inline-block">
                                    Jesteście znajomymi
                                    </br>
                                    <small>Kliknij aby usunąć znajomego </small>
                                </div>
                        </button>
                    </form>
                <?php else: ?>
                    <?php if ($params['profileData']['user_id'] != $_SESSION['id']): ?>
                        <?php if($params['isInvited'] == 1): ?>
                                <form method="post" id="inviteButton">
                                    <input type="hidden" name="invite" value="abort">
                                    <button type="submit" form="inviteButton" class="profile_page_tile">
                                        <i class="fas fa-check" style="font-size: 1vw; color: green; padding-right: 1vw; vertical-align: 35%;"></i>
                                            <div style="display: inline-block">
                                                Zaproszono
                                                </br>
                                                <small>Kliknij aby anulować </small>
                                            </div>
                                    </button>
                                </form>
                        <?php elseif($params['pendingInvitation'] == 1): ?>
                            <form method="post" id="inviteButton">
                                <input type="hidden" name="invite" value="accept">
                                <button type="submit" form="inviteButton" class="profile_page_tile">
                                    <i class="fas fa-check" style="font-size: 1vw; color: green; padding-right: 1vw; vertical-align: 35%;"></i>
                                        <div style="display: inline-block">
                                            Zostałeś zaproszony
                                            </br>
                                            <small>Kliknij aby przyjąć zaproszenie </small>
                                        </div>
                                </button>
                            </form>
                        <?php elseif($params['isInvited'] == 0): ?>
                            <form action="/?action=profile&id=<?php echo$params['profileData']['user_id'] ?>" method="post" id="inviteButton">
                                <input type="hidden" name="invite" value="send">
                                <button type="submit" form="inviteButton" class="profile_page_tile">
                                    <i class="fas fa-user-plus" style="font-size: 1vw; color: #103A6E; padding-right: 1vw; vertical-align: 35%;"></i>
                                        <div style="display: inline-block">
                                            Zaproś do znajomych
                                        </div>
                                </button>
                            </form>
                        <?php endif ?>
                    <?php endif ?>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>

    <div class="profile_content_right">
        <div class="profile_posts">
            <div class="profile_post">
                <div class="post_content profile_posts_header">
                    Posty użytkownika <?php echo htmlentities($params['profileData']['name']); ?>
                </div>
            </div>
        <?php foreach($params['posts'] ?? [] as $post): ?>
            <div class="profile_post">
                <div class="post_info">
                    <div class="post_info_content post_author_image">
                        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($post['authorPhoto']); ?>">
                    </div>
                    <div class="post_info_content">
                        <div class="post_author">
                            <?php echo htmlentities($post['name'] . " " . $post['surname']); ?>
                        </div>
                        <div class="post_date">
                            <?php echo htmlentities($post['creation_date']); ?>
                        </div>
                    </div>
                    <?php if($post['author_id'] == $_SESSION['id']): ?>
                    <div class="post_info_content post_options">
                        <a href=''>Edytuj</a>
                         | 
                        <a href=''>Usuń</a>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="post_content">
                    <?php echo nl2br(htmlentities($post['post_text'])); ?>
                </div>
            </div>
        <?php endforeach ?>
        </div>
    </div>
</div>