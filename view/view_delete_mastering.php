<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Delete Place</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <?php include('menu.php'); ?>
    <div class="main">
        <p>Do you really want to delete this skill "<?= $mastering->getSkillName() ?>" and all of its dependencies ?</p>
        <p>This process cannot be undone.</p>
        <br>
        <form method='post' action='mastering/delete' enctype='multipart/form-data'>
            <input type='hidden' name='userId' id="userId" required value='<?= $mastering->getUser() ?>'>
            <input type='hidden' name='skillId' id="skillId" required value='<?= $mastering->getSkill() ?>'>
            <button type="submit">DELETE</button>
            <button type="submit" formaction="mastering/index">CANCEL</button>
        </form>
    </div>
</body>

</html>