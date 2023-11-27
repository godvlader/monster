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


    <div class="login-page">
        <div class="login-form">
            <div class="sing-in-header">
                <h2>Sing in</h2>
            </div>
            <form action="main/login" method="post">
                <table>
                    <tr>
                        <td><input class="sign" id="mail" name="mail" placeholder="example@email.com" type="email" value="<?= $mail ?>"></td>
                    </tr>
                    <tr>
                        <td><input class="sign" id="password" placeholder="Password" name="password" type="password" value="<?= $password ?>"></td>
                    </tr>
                </table>
                <input class="login-button" type="submit" value="Log In">
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