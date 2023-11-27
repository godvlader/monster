<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <title>Manage Skills</title>
</head>
<body>
<?php include('menu.php'); ?>
<?php foreach ($skills as $skill) : ?>
    <form class="skills-form" method='post' action='skill/edit' enctype='multipart/form-data'>
        <input type='hidden' name='id' id="id" required value='<?= $skill->getId() ?>'>
        <input type='text' name='name' id="name" required value='<?= $skill->getName() ?>'>
        <button type="submit">save</button>
        <button type="submit" ><a href="skill/delete_confirm/<?= $skill->getId() ?>">delete</a></button>
        <?php $numberOfExperiences = $skill->countExperience() ?>
        <?php $numberOfUsers = $skill->countUsers() ?>
        <p class="skills-form-p">mastered by <a href="manageusers/index/<?= $skill->getId(); ?>"><?= $numberOfUsers ?> user<?php if($numberOfUsers > 1){ echo's';} ?></a>, used in <?= $numberOfExperiences ?> experience<?php if($numberOfExperiences > 1){ echo's';} ?></p>
    </form>
<?php endforeach; ?>
<form class="skills-form-add" method='post' action='skill/add' enctype='multipart/form-data'>
    <input type='text' name='name' id="name" placeholder="Skill Name">
    <button type="submit">add</button>
</form>
<br>

<?php if (!empty($errors) && count($errors) != 0): ?>
    <div class='errors'>
        <p>Please correct the following error(s) :</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php elseif (isset($success) && strlen($success) != 0): ?>
    <p><span class='success'><?= $success ?></span></p>
<?php endif; ?>

</body>
</html>