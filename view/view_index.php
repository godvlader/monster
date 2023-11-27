<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome </title>
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
                <a class="login-icon" href="main/login"><i class="fa-solid fa-right-to-bracket"></i></a>
                <a class="signup-icon" href="main/signup"><i class="fa-solid fa-user-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="title">Hello guest, please <a href="main/login">log in</a> or <a href="main/signup">sign up</a> !</div>
</body>

</html>