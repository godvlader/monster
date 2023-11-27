<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Experience</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/inputs.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
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
        var user = <?= $user->getUserId() ?>;
        var expId = <?= $experience->getId() ?>;
        var startForEdit = "<?= $experience->getStart() ?>";
        console.log(startForEdit);
        var stop = "<?= null == !$experience->getStop() ? $experience->getStop() : '' ?>";
        var title = "<?= $experience->getTitle() ?>";
        var description = "<?= null == !$experience->getDescription() ? $experience->getDescription() : '' ?>";

        let switchJustValidate;
        switchJustValidate = <?= $switch ?>;
        $(function() {
            if (switchJustValidate == 1) {
                iteration3(user, expId, startForEdit, stop, title, description);
            } else {
                console.log('entered switch');
                $('.validate-it2').css('display', '');
                $('.just-validate-errors').css('display', 'none');
                iteration2(user);
            }

        });
    </script>
</head>

<body>
    <?php include('menu.php'); ?>
    <div class="main">
        <?php
        echo "<h3>" . $user->getFullName() . ", " . $user->getTitle() . "</h3>";
        if (isset($experience)) {
            echo "<br>Editing experience : " . $experience->getTitle();
            echo "<br>";
        }
        $start = $experience->getStart();
        $stop = $experience->getStop();
        ?>
        <form id='expForm' method='post' action='experience/edit' enctype='multipart/form-data'>
            <input type='hidden' value='<?= $experience->getId() ?>' name='experienceId'>
            <input type='hidden' value='<?= $user->getUserId() ?>' name='userId'>
            <div id='formswitcher'>
                <div class="validate-it2">
                    <p>Start date: </p>
                    <input id="StartDateit2" type='date' name='start' value='<?= $start ?>'>
                    <span class="errors" id="errStartDate"></span>
                </div>
                <div class="validate-it2">
                    <p>End date (optional) : </p>
                    <input id="endDateit2" type='date' name='stop' value='<?= $stop ?>'>
                    <span class="errors" id="errEndDate"></span>
                </div>
                <div class="validate-it2">
                    <p>Title : </p>
                    <input id='titleit2' type='text' name='title' value='<?= $experience->getTitle() ?>'>
                    <span class="errors" id="errTitle"></span>
                </div>
                <div class="validate-it2">
                    <p>Description (optional) :</p>
                    <textarea id="descriptionit2" name="description" placeholder="Description must be between 10 and 128 characters. However, it is not required." <?= null == !$experience->getDescription() ? $experience->getDescription() : '' ?>></textarea>
                    <span class="errors" id="errDescription"></span>
                </div>
            </div>

            <div style="float:right;" id="counter">
                <span id="current">0</span>
                <span id="maximum">/60</span>
            </div>
            <br>
            <p>Place : </p>
            <select name="place" id="place" required>
                <option value='<?= $experience->getPlace()->getId() ?>' selected><?= $experience->getPlaceName() . "(" . $experience->getPlaceCity() . ")"  ?></option>
                <?php
                if ($places) {
                    foreach ($places as $place) {
                        echo "<option value='" . $place->getId() . "'> " . $place->getName() . "(" . $place->getCity() . ")" . " </option>",  "\n";
                    }
                }
                ?>
            </select>
            <p>Skills used : </p>
            <?php
            if ($skills) {
                foreach ($skills as $skill) {
                    if (!empty($usedSkills) && in_array($skill->getId(), $usedSkills)) {
                        echo "<input type='checkbox' checked name='" . $skill->getId() . "' id='" . $skill->getId() . "' value='" . $skill->getId() . "'>";
                        echo "<label for='" . $skill->getId() . "'>" . $skill->getName() . "</label>";
                    } else {
                        echo "<input type='checkbox' name='" . $skill->getId() . "' id='" . $skill->getId() . "' value='" . $skill->getId() . "'>";
                        echo "<label for='" . $skill->getId() . "'>" . $skill->getName() . "</label>";
                    }
                }
            }
            ?>
            <br>
            <button id="submit-btn-edit" type='submit' value='SAVE' formaction="experience/edit/<?= $experience->getId() ?>">SAVE</button>
            <button style="float:right" type='submit' value='CANCEL' formaction="experience/index/<?= $experience->getUser() ?>">CANCEL</button>
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
</body>

</html>