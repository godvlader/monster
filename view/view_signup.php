<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <div class="navbar">
        <div class="navbar-container">
            <div class="logo-container">
                <h1 class="logo"><i class="fa-solid fa-cheese"></i> Munster.be</h1>
            </div>
            <div class="login-signup-container">
                <a class="logout-icon" href="user/index"><i class="fa-solid fa-right-from-bracket"></i></a>
                <a class="signup-icon" href="main/signup"><i class="fa-solid fa-user-plus"></i></a>
            </div>
        </div>
    </div>

    <div class="menu">
    </div>
    <div class="signup_page">
        <div class="sing-up-container">
            <form class="signup-form" method="post" action="main/signup" enctype="multipart/form-data">
                <div class="signup-title">
                    <h2>Sign up</h2>
                </div>
                <input class="signup-form-items" type="email" placeholder="Email" name="mail" id="mail" value="<?= $mail ?>" required>
                <input class="signup-form-items" type="text" placeholder="Full Name" name="fullName" id="fullName" value="<?= $fullName ?>" required>
                <input class="signup-form-items" type="text" placeholder="Title" name="title" id="title" value="<?= $title ?>" required>
                <input class="signup-form-items" type="date" name="birthdate" id="birthdate" <?php echo $birthdate !== "" ? 'value="' . $birthdate->format('Y-m-d') . '"' : "";  ?> required>
                <input class="signup-form-items" type="password" placeholder="Password" name="password" id="password" value="<?= $password ?>" required>
                <input class="signup-form-items" type="password" placeholder="Confirm Password" name="password_confirm" id="password_confirm" value="<?= $password_confirm ?>" required>
                <input class="signup-form-btn" type="submit" value="SIGN UP">
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
            <?php endif; ?>
        </div>
    </div>
</body>

</html>