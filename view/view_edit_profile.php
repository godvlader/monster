<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/chprofile.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <title>Edit Profile</title>
</head>

<body>
    <?php include('menu.php'); ?>
    <div class="chprofile">
        <div class="chprofile-container">
            <form class="chprofile-form" method="post" action='profile/edit_profile' enctype="multipart/form-data">
                <div class="chprofile-title">
                    <h2>Edit <?= $user->getFullName() ?>'s Profile </h2>
                </div>
                <p>Email: </p>
                <input class="chprofile-form-items" type="email" name="mail" id="mail" value="<?= $user->getMail() ?>">
                <p>Full name :</p>
                <input class="chprofile-form-items" type="text" name="fullName" id="fullName" value="<?= $user->getFullName() ?>">
                <p>Title :</p>
                <input class="chprofile-form-items" type="text" name="title" id="title" value="<?= $user->getTitle() ?>">
                <p>Birthdate :</p>
                <input class="chprofile-form-items" type="date" name="birthdate" id="birthdate" value="<?= $user->getBirthdate()->format('Y-m-d') ?>">
                <p>Role :</p>
                <?php if ($loggedUser->isAdmin() && $user->getUserId() !== $loggedUser->getUserId()) : ?>
                    <input class="chprofile-form-rad" type="radio" name="role" value="admin">
                    <label for="admin" id="admin">Admin</label>
                    <input class="chprofile-form-rad" type="radio" name="role" value="user">
                    <label for="user" id="user">User</label>
                <?php else : ?>
                    <input class="chprofile-form-items" type="text" value="<?= $user->getRole() ?>">
                <?php endif; ?>
                <br>
                <input class="chprofile-form-btn" type="submit" value="Save Profile">
            </form>
        </div>
        <?php if (count($errors) != 0) : ?>
            <div class='errors'>
                <p>Please correct the following error(s) :</p>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (strlen($success) != 0) : ?>
            <p><span class='success'><?= $success ?></span></p>
        <?php endif; ?>
    </div>
    </main>
</body>

</html>