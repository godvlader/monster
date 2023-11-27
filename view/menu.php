<div class="navbar">
    <div class="navbar-container">
        <div class="logo-container">
            <h1 class="logo"><i class="fa-solid fa-cheese"></i> Munster.be</h1>
        </div>
        <div class="menu-container">
            <ul class="menu-list">
                <li class="menu-list-item"><a href="mastering/index">Skills</a></li>
                <li class="menu-list-item"><a href="experience/index">Experiences</a></li>
                <?php if ($loggedUser->isAdmin()) : ?>
                    <li class="menu-list-item"><a href="skill/skills">Manage skills</a></li>
                    <li class="menu-list-item"><a href="place/index">Manage places</a></li>
                    <li class="menu-list-item"><a href='manageusers/index'>Manage users</a></li>
                <?php endif; ?>
                <li class="menu-list-item"><a href="profile/index">
                        <?php echo $loggedUser->isAdmin() ?
                            '<i class="fa-solid fa-user-shield"></i> ' :
                            '<i class="fa-solid fa-user"></i> ';
                        echo $loggedUser->getFullName(); ?>
                    </a>
                </li>
                <li class="menu-list-item"><a class="logout-icon" href="user/logout"><i class="fa-solid fa-right-from-bracket"></i></a></li>
            </ul>
        </div>
    </div>
</div>