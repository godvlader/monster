<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/changepass.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <title>Change Password</title>
</head>
<?php include('menu.php'); ?>

<body>
    </main>

    <div class="chpass_page">
        <div class="chpass-container">
            <form class="chpass-form" method="POST" enctype="multipart/form-data">
                <div class="chpass-title">
                    <h2>Change password for <?= $user->getFullName() ?></h2>
                </div>
                <?php if ($askForPassword) : ?>
                    <div class="form-floating">
                        <?php echo "<input class='chpass-form-items' type='password' name='currentPassword' id='currentPassword' placeholder='Current Password'>"; ?>
                    </div>
                <?php endif; ?>
                <input class="chpass-form-items" type="password" placeholder="New Password" name="newPassword" id="newPassword" required>
                <input class="chpass-form-items" type="password" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" required>
                <input class="chpass-form-btn" type="submit" value="Save">
            </form>
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
    </div>

</body>

</html>