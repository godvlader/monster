<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script>
        'use strict';
        let filterContainer;
        let tblUsers;
        let skills;
        let tableContainer;
        let selectedSkills = [];



        $(function() {

            tableContainer = $('#table-container');
            //getUsers();

            filterContainer = $('#filter-container');
            getSkills();

        })


        function skillList() {
            if ($(".skills-item").length) {
                $(".skills-item").change(function() {
                    if ($(this).prop("checked") === true) {
                        selectedSkills.push($(this).attr("id"));
                    }
                    if ($(this).prop("checked") === false) {
                        let id = selectedSkills.indexOf($(this).attr("id"));
                        if (id > -1) {
                            selectedSkills.splice(id, 1);
                        }
                    }
                    getUsers();
                });
            }
        }


        function getSkills() {
            $.post("skill/get_skills_service", function(data) {
                skills = data;
                displaySkills(skills);
                skillList();
            }, "json").fail(function() {
                filterContainer.html("<p>There is no skill to be selected.</p>");
            });
        }

        function displaySkills(skills) {
            filterContainer.empty();
            filterContainer.html("<p>Loading...</p>");
            let html = "<br><h4>Filter users by Skills </h4>";
            html += "<div id='skills-list'>";
            for (let skill of skills) {
                html += "<input class='skills-item' type='checkbox'  id='" + skill.id + "' value='" + skill.name + "'>";
                html += "<label for='" + skill.id + "'>" + skill.name + "</label>"
            }
            html += "</div>"
            filterContainer.empty();
            filterContainer.html(html);
        }



        function getUsers() {
            $.post("manageusers/get_visible_users_service", {
                filter: selectedSkills
            }, function(data) {
                let users = data;
                displayTable(users);
            }, "json").fail(function(err) {
                tableContainer.html("<p>Filter is fail!</p>");
            });
        }


        function displayTable(users) {
            tableContainer.empty();
            tableContainer.html("<p>Loading...</p>");
            let html;
            if (users.length) {
                html = "<table id='users_table' class='users_table'><tr><th>Mail</th>" +
                    "<th>FullName</th>" +
                    "<th>Title</th>" +
                    "<th>Birthdate</th>" +
                    "<th>Role</th>" +
                    "<th>Action</th></tr>";
                let i = 0;
                for (let u of users) {
                    html += "<tr>";
                    html += "<form action='manageusers/manage_users' method='post' id='form" + i + "'></form>";
                    html += "<input form='form" + i + "' type='hidden' name='userId' value='" + u.id + "'>";
                    html += "<td><input form='form" + i + "' name='mail' id='mail' type='text' value='" + u.mail + "'></td>";
                    html += "<td><input form='form" + i + "' name='fullName' id='fullName' type='text' value='" + u.fullName + "'></td>";
                    html += "<td><input form='form" + i + "' name='title' id='title' type='text' value='" + u.title + "'></td>";
                    html += "<td><input form='form" + i + "' name='birthdate' id='birthdate' type='date' value='" + u.birthdate + "'></td>";
                    html += "<td><select form='form" + i + "' name='role' id='role'>";
                    if (u.role === 'admin') {
                        html += "<option value='admin' selected>Admin</option>";
                        html += "<option value='user'>User</option>";
                    } else {
                        html += "<option value='user' selected>User</option>";
                        html += "<option value='admin'>Admin</option>";
                    }
                    html += "</select></td>";
                    html += "<td><ul class='actions-menu'><li><button form='form" + i + "' type='submit' formaction='user/edit_profile/" + u.id + "'>EDIT</button><ul>";
                    html += "<li><button disabled><a href='experience/index/" + u.id + "'>Show Experiences (" + u.experienceAmount + ")</a></button></li>";
                    html += "<li><button disabled><a href='mastering/index/" + u.id + "'>Show Skills (" + u.skillAmount + ")</a></button></li>";
                    html += "<li><button disabled><a href='profile/change_password/" + u.id + "'>Change Password</a></button></li>";
                    html += "<li><button disabled><a href='user/delete_confirm/" + u.id + "'>Delete</a></button></li>";
                    html += "</ul></li></ul></td>";
                    html += "</tr>";
                    i++;
                }
                html += "</table>";
            } else {
                html = "<p>There is no user with selected skill(s). please try again.</p>";
            }
            tableContainer.empty();
            tableContainer.html(html);
        }
    </script>
</head>

<body>
    <?php include('menu.php'); ?>
    <h2 class="title">Manage Users</h2>
    <div class="main" id="table-container">
        <?php
        if (!empty($userList)) : ?>
            <table id="users_table" class="users_table">
                <tr>
                    <th>Mail</th>
                    <th>FullName</th>
                    <th>Title</th>
                    <th>Birthdate</th>
                    <th>Role</th>
                    <th>Action</th>

                </tr>
                <?php foreach ($userList as $user) : ?>
                    <tr>
                        <form method="post" enctype="multipart/form-data">
                            <input type='hidden' name="userId" value='<?= $user->getUserId() ?>'>
                            <td><input name='mail' id="mail" type="text" value='<?= $user->getMail() ?>'></td>
                            <td><input name='fullName' id="fullName" type="text" value='<?= $user->getFullName() ?>'></td>
                            <td><input name='title' id="title" type="text" value='<?= $user->getTitle() ?>'></td>
                            <td><input name='birthdate' id="birthdate" type="date" value='<?= date_format($user->getBirthDate(), "Y-m-d") ?>'></td>
                            <td><select name='role' id='role'>
                                    <?php
                                    if ($user->getRole() == 'admin') {
                                        echo "<option value='admin' selected>";
                                        echo "Admin";
                                        echo "</option>";
                                        echo "<option value='user'>";
                                        echo "User";
                                        echo "</option>";
                                    } else {
                                        echo "<option value='admin' >";
                                        echo "Admin";
                                        echo "</option>";
                                        echo "<option value='user'selected>";
                                        echo "User";
                                        echo "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <ul class="actions-menu">
                                    <li><button type="submit" formaction="user/edit_profile/<?= $user->getUserID() ?>">EDIT</button>
                                        <ul>
                                            <li><button disabled><a href="experience/index/<?= $user->getUserId() ?>">Show Experiences (<?= $user->getExperienceAmount() ?>)</a></button></li>
                                            <li><button disabled><a href="mastering/index/<?= $user->getUserId() ?>">Show Skills (<?= $user->getSkillAmount() ?>)</a></button></li>
                                            <li><button disabled><a href="profile/change_password/<?= $user->getUserId() ?>">Change Password</a></button></li>
                                            <li><button disabled><a href="user/delete_confirm/<?= $user->getUserId() ?>">Delete</a></button></li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            <?php else : echo "No users corresponding to used filter.";
        endif; ?>
            </table>

    </div>
    <div class="filter" id="filter-container">
        <form action="manageusers/filter" method="post">
            <label for="filter">Filter users by Skill :</label>
            <select name="filter" id="filter">
                <?php
                echo $skillFilteredBy ? "<option>" . $skillFilteredBy->getName() . "</option>" : "<option value='-1'>Select a Skill</option>";
                foreach ($skills as $skill) {
                    echo "<option value=" . $skill->getId() . ">" . $skill->getName() . "</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
            <button type="submit" formaction="manageusers/index">Reset</button>
        </form>
    </div>
    <?php if (count($errors) != 0) : ?>
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