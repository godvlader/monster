<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <title>Manage Places</title>
</head>
<?php include('menu.php'); ?>

<body>
    <br>
    <?php foreach ($places as $place) : ?>
        <form class="places-form" method='post' action='place/edit' enctype='multipart/form-data'>
            <input type='hidden' name='id' id="id" required value='<?= $place->getId() ?>'>
            <input type='text' name='name' id="name" required value='<?= $place->getName() ?>'>
            <input type='text' name='city' id="city" required value='<?= $place->getCity() ?>'>
            <button type="submit">save</button>
            <button disabled><a href="place/delete_confirm/<?= $place->getId() ?>">delete</a></button>
            <?php $numberOfExperiences = $place->countExperience() ?>
            <p class="places-form-p">use in <?= $numberOfExperiences ?> experience<?php if ($numberOfExperiences > 1) {
                                                                                        echo 's';
                                                                                    } ?></p>
        </form>
    <?php endforeach; ?>
    <form class="places-form-add" method='post' action='place/add' enctype='multipart/form-data'>
        <input type='text' name='name' id="name" placeholder="New Place">
        <input type='text' name='city' id="city" placeholder="City">
        <button type="submit">add</button>
    </form>
    <br>

    <?php if (!empty($errors) && count($errors) != 0) : ?>
        <div class='errors'>
            <p>Please correct the following error(s) :</p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (isset($success) && strlen($success) != 0) : ?>
        <p><span class='success'><?= $success ?></span></p>
    <?php endif; ?>

</body>

</html>