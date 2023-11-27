<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Add Experience</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/inputs.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="lib/just-validate.production.patched.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="lib/just-validate.production.patched.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-plugin-date.production.min.js" type="text/javascript"></script>
    <script src="scripts/validations.js" type="text/javascript"></script>

    <?php
    if (Configuration::get('just_validate_switch')) {
        $switch = 1;
    } else {
        $switch = 0;
    }
    ?>
    <script>
        user = <?= $user->getUserId() ?>;
        let switchJustValidate;
        switchJustValidate = <?= $switch ?>;
        $(function() {
            if (switchJustValidate == 1) {
                iteration3(user);
            } else {
                console.log('entered switch');
                $('.validate-it2').css('display', '');
                $('.just-validate-errors').css('display', 'none');
                iteration2(user, null, null, null, null, null);
            }

        });
    </script>

</head>
<?php include('menu.php'); ?>

<body>
    <div class="main">
        <?php
        echo "<h2> Adding a new experience for " . $user->getFullName() . ", " . $user->getTitle() . "</h2>";
        ?>
        <br>

        <form id='expForm' method='post' action='experience/add'>

            <div id='formswitcher'>
                <div class="validate-it2">
                    <p>Start date: </p>
                    <input id="StartDateit2" type='date' name='start'>
                    <span class="errors" id="errStartDate"></span>
                </div>
                <div class="validate-it2">
                    <p>End date (optional) : </p>
                    <input id="endDateit2" type='date' name='stop' value="null">
                    <span class="errors" id="errEndDate"></span>
                </div>
                <div class="validate-it2">
                    <p>Title : </p>
                    <input id='titleit2' type='text' name='title'>
                    <span class="errors" id="errTitle"></span>
                </div>
                <div class="validate-it2">
                    <p>Description (optional) :</p>
                    <textarea id="descriptionit2" name="description" placeholder="Description must be between 10 and 128 characters. However, it is not required." value=""></textarea>
                    <span class="errors" id="errDescription"></span>
                </div>
            </div>

            <div style="float:right;" id="counter">
                <span id="current">0</span>
                <span id="maximum">/60</span>
            </div>

            <br>
            <div class="validate-it2">
                <p>Place : </p>
                <select name="place" id="placeSelection">
                    <option value="-1">Select Your place</option>
                    <?php if ($places) : ?>
                        <?php foreach ($places as $place) : ?>
                            <option value="<?= $place->getId() ?>"><?= $place->getName() . " (" . $place->getCity() . ")" ?></option>
                        <?php endforeach ?>
                    <?php else : echo "There's no places to choose.";
                    endif; ?>
                </select>
                <span class="errors" id="errPlace"></span>
            </div>
            <p>Skills used : </p>
            <br>
            <?php
            if ($skills) {
                foreach ($skills as $skill) {
                    echo "<input type='checkbox' name='" . $skill->getId() . "' id='" . $skill->getId() . "' value='" . $skill->getId() . "'>";
                    echo "<label for='" . $skill->getId() . "'>" . $skill->getName() . "</label>";
                }
            }
            ?>
            <br>

            <input type='hidden' value='<?= $user->getUserId() ?>' name='userId'>
            <input id="submit-btn" style="float:right;" type='submit' value='ADD'>
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
</body>

</html>