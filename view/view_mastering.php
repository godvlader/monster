<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <title>Mastered Skills</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<body>
    <?php include('menu.php'); ?>
    <div class="main">
        <h1><?= $user->getFullName() . ", " . $user->getTitle() ?></h1>
        <h2>Skills</h2>
        <ul class="skills">
            <br />
            <?php
            $i = 0;
            echo "<div id='reloader'>";
            foreach ($masterings as $mastering) :
                echo "<div class='skill-container'>";
                echo "<form method='post' action='Mastering/delete' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='skillId' class='skillId' required value='" . $mastering->getSkill() . "'>";
                echo "<button type='submit' name='decrease' formaction='mastering/decrease'>&#x25BC;</button>";
                echo "<button type='submit' name='increase' formaction='mastering/increase'>&#x25B2;</button>";
                echo "<span class='nojsStars' style='display:none'>";
                for ($j = 0; $j < $mastering->getLevel(); $j++) {
                    echo "<i  idOfSkill='" . $mastering->getSkill() . "' style='color:blue;'class='fa fa-star' name='star" . $i . "' aria-hidden='true' id='st" . $j . "_" . $i . "'></i>";
                }
                for ($j = $mastering->getLevel(); $j < 5; $j++) {
                    echo "<i idOfSkill='" . $mastering->getSkill() . "' class='fa fa-star' name='star" . $i . "' aria-hidden='true' id='st" . $j . "_" . $i . "'></i>";
                }
                echo "</span>";
                echo "<input type='hidden' name='userId' id='userId' required value='" . $user->getUserId() . "'>";
                echo "<i style='display:none' idOfSkill='" . $mastering->getSkill() . "' id='trash" . $i . "' class='fa fa-trash' aria-hidden='true'></i>";
                echo "<button class='nojstrash' type='submit'><i ' class='fa fa-trash' aria-hidden='true'></i></button>";
            ?>
                <button class='level' value='<?= $mastering->getLevel() ?>' formaction='<?= $loggedUser->isAdmin() ? "manageusers/index/" . $mastering->getSkill() : "mastering/index" ?>'><?= $mastering->getSkillName() ?></button>
            <?php
                echo "<span class='nojsaddskill'> Level : " . $mastering->getLevel() . " </span>";
                echo "</form>";
                echo "</div>";
                echo "<br>";
                $i++;
            endforeach;
            echo "</div>";
            ?>
        </ul>
    </div>
    <br>
    <hr>
    <div class="add-skill">
        <p>
        <h2>Add skill</h2>
        </p>
        <form method='post' action='mastering/add' enctype='multipart/form-data'>
            <p>Skill</p>
            <select class='selection' name="skillId" id="skillId">
                <option value="-1">Select a skill</option>
                <?php $skills = Skill::getSkills();
                $m = 0;
                ?>
                <?php foreach ($skills as $skill) : ?>
                    <option class="<?= $m ?>" value="<?= $skill->getId() ?>"><?= $skill->getName() ?></option>
                <?php
                    $m++;
                endforeach; ?>
            </select>
            <div class='nojsaddskill'>
                <p>Level (1-5)</p>
                <input type="range" name="level" id="level" min="1" max="5">
                <input type="hidden" name="userId" id="userId" value="<?= $user->getUserId(); ?>">
                <input type='submit' value='add'>
            </div>
        </form>
        <div class="container">
            <div id="fastarcontainer" class="con" style='display:none'>
                <i class="fa fa-star" aria-hidden="true" id="1"></i>
                <i class="fa fa-star" aria-hidden="true" id="2"></i>
                <i class="fa fa-star" aria-hidden="true" id="3"></i>
                <i class="fa fa-star" aria-hidden="true" id="4"></i>
                <i class="fa fa-star" aria-hidden="true" id="5"></i>
            </div>
        </div>
    </div>
    <div class='stopper'></div>
    <?php ?>



    <script>
        var i = 0;
        var j = 0;
        var skillID = 0;
        var option;
        var idUser = <?= $user->getUserId(); ?>;
        var selectedSkill;

        $(function() {
            //NO JS ELEMENT MANAGEMENT START
            $('.nojsStars').css('display', '');
            $('.fa.fa-trash').css('display', '');
            $('.nojsaddskill').css('display', 'none');
            $('.nojstrash').css('display', 'none');
            $('button[name="decrease"]').css('display', 'none');
            $('button[name="increase"]').css('display', 'none');
            $('#fastarcontainer').css('display', '');
            //NO JS ELEMENT MANAGEMENT STOP

            //
            $('select.selection').change(function() {
                selectedSkill = $(this).children("option:selected").val();
            });

            skillListMod();

            $("#fastarcontainer").children().each(
                function() {
                    $(this).mousedown(function() {
                        $(this).prevUntil('.container').css("color", "blue");
                        $(this).css("color", "blue");
                    }).mouseup(function() {
                        $(this).nextUntil('.stopper').css("color", "black");
                        if (typeof selectedSkill != 'undefined') {
                            addSkill($(this).attr("id"));
                        }
                    }).hover(function() {
                        $(this).prevUntil('.container').css("color", "purple");
                        $(this).css("color", "purple");
                    }, function() {
                        $(this).nextUntil('.stopper').css("color", "black");
                    });
                });

            function skillListMod() {
                $('div#reloader').children('.skill-container').each(function() {
                    for (let j = 0; j < 5; j++) {
                        $("#st" + j + "_" + i).mousedown(function() {
                            skillID = $(this).attr('idOfSkill');
                            $(this).css("color", "blue");
                        }).mouseup(function() {
                            updateSkillLevel(j + 1);
                            $(this).nextUntil('button').css("color", "black");
                        }).hover(function() {
                            $(this).prevUntil('.container').css("color", "purple");
                            $(this).css("color", "purple");
                        }, function() {
                            $(this).nextUntil('.stopper').css("color", "black");
                            reloadMasterings();
                        });;
                    }
                    $("#trash" + i).click(function() {
                        skillID = $(this).attr('idOfSkill');
                        deleteSkill(skillID);
                    });

                    i++;
                });
                i = 0;
            };

            function updateSkillLevel(level) {
                $.post("Mastering/updateLevel", {
                    skillId: skillID,
                    userId: idUser,
                    level: level
                });
            };

            function addSkill(level) {
                $.post("Mastering/addService", {
                    skillId: selectedSkill,
                    userId: idUser,
                    level: level,
                }, function(data) {
                    reloadMasterings();
                });
            };

            function deleteSkill(skill) {
                $.post("Mastering/deleteService", {
                    skillId: skill,
                    userId: idUser,
                }, function(data) {
                    reloadMasterings();
                });
            }

            // This .load function reloads the content of what's contained between 
            // .skills and #reloader in order to update skillList dynamically without reloading the entire page
            // On Callback we need to call skillListMod again in order to be able to modify list after reloading that specific content
            function reloadMasterings() {
                $('.skills').load(' div#reloader', function() {
                    skillListMod();
                    // We need to reload display attributes for noJS version
                    $('.nojsStars').css('display', '');
                    $('button[name="decrease"]').css('display', 'none');
                    $('button[name="increase"]').css('display', 'none');
                    $('.fa.fa-trash').css('display', '');
                    $('.nojstrash').css('display', 'none');
                    $('.nojsaddskill').css('display', 'none');
                });
            }

        });
    </script>
</body>

</html>