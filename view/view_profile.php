<!doctype html>
<html lang="en">

<head>
    <base href="<?= $web_root ?>" />
    <meta charset="UTF-8">
    <title><?= $user->getFullName() ?>'s Profile!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/profile.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <?php include('menu.php'); ?>
    <div class="sidenav">
        <div class="profile">
            <img src="https://wallpapers.com/images/high/code-symbols-programming-screen-words-colorful-9qvybw7ztiyn5sj2.jpg" alt="" width="150" height="150">
            <div class="name">
                <?php echo $user->getFullName(); ?>
            </div>
            <div class="job">
                <?php echo $user->getTitle(); ?>
            </div>
        </div>

        <div class="sidenav-url">
            <div class="url">
                <a href="#profile" class="active">Profile</a>
                <hr align="center">
            </div>
            <div class="url">
                <div>
                    <h2>Settings</h2>
                    <a href="profile/edit_profile/<?= $user->getUserId() ?>">Edit profile</a>
                    <a href="profile/change_password/<?= $user->getUserId() ?>">Change password</a>
                    <hr align="center">
                </div>
            </div>
        </div>
    </div>
    <div class="main">
        <h2>IDENTITY</h2>
        <div class="card">
            <div class="card-body">
                <a href="profile/edit_profile"><i class="fa fa-pen fa-xs edit"></i></a>
                <table>
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>:</td>
                            <td><?php echo $user->getFullName(); ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo $user->getMail(); ?></td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>:</td>
                            <td><?php echo $user->getTitle(); ?></td>
                        </tr>
                        <tr>
                            <td>Registered at</td>
                            <td>:</td>
                            <td><?php echo $user->getRegisteredAt()->format("Y-m-d H:i:s"); ?></td>
                        </tr>
                        <tr>
                            <td>Birthday</td>
                            <td>:</td>
                            <td><?php echo $user->getBirthdate()->format('Y-m-d'); ?></td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td>:</td>
                            <td><?php echo $user->getRole(); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</body>

</html>