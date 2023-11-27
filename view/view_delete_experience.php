<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Delete Experience</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<?php include('menu.php'); ?>

<body>
    <div class="main">
        <?php
        echo $user->getFullName() . ", " . $user->getTitle();
        if (isset($experience)) {
            echo "<br>Deleting experience : " . $experience->getTitle();
            echo "<br>";
        }
        ?>
        <br>
        <form action='experience/delete' method='post'>
            <input type='hidden' value='<?= $experience->getId() ?>' name='experienceId'>
            <input type='hidden' value='<?= $user->getUserId() ?>' name='userId'>
            <input type='submit' value='DELETE'>
        </form>
        <form action='experience/toIndex' method='post'>
            <button id='experiencecanceladdbutton' disabled><a style='color:white; text-decoration:none;' href='experience/index/<?= $user->getUserId() ?> '>CANCEL</a></button>
        </form>
    </div>
</body>

</html>