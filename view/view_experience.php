<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Experience</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/viewExp.css" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="lib/jquery-ui-1.13.1/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.13.1/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui-1.13.1/jquery-ui.min.js" type="text/javascript"></script>
    <link href='lib/fullcalendar-scheduler/main.css' rel='stylesheet' />
    <script src='lib/fullcalendar-scheduler/main.js'></script>
    <link href="lib/jquery-ui-1.13.1/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.13.1/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-ui-1.13.1/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
    <script src="lib/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/jquery-ui-1.13.1/jquery-ui.min.js" type="text/javascript"></script>

</head>
<?php include('menu.php'); ?>

<body>
    <div class="main">
        <?php

        echo "<h1>" . $user->getFullName() . ", " . $user->getTitle() . "</h1>";
        echo "<h2>Experiences</h2>";
        echo "<div class='alignright'>";
        echo "<div id='viewSwitcher' class='viewchanger' style='display:none;'>CALENDAR VIEW</div>";
        echo "</div>";
        echo "<div id='calendar'></div>";

        echo "<div id='listview' view='listview'> ";

        echo "<br>";

        echo "<div class='filters' style='display:none'>";
        echo "Start : ";
        echo "<input type='number' onkeypress='return /^[0-9]*$/i.test(event.key)' name='startFilter'>";
        echo " End : ";
        echo "<input type='number' onkeypress='return /^[0-9]*$/i.test(event.key)' name='endFilter'>";
        echo "</div>";
        //next 5 lines for the years Slider.
        echo "<div class='slider' style='display:none'>";
        echo "<p><label for='period'>Year range:</label>";
        echo "<input type='text' id='period' readonly style='border:0; color:rgb(18 102 241); font-weight:bold;'></p>";
        echo "<div id='slider-range'></div>";
        echo "</div>";


        echo "<yearerror></yearerror>";

        echo "<br/>";
        echo "<i>Skills colored in purple are those used in your exeperiences but which are not in your skill list. </i>";

        echo "<div class=experienceAndBtns>";
        $i = 0;
        if (!empty($experiences)) {
            $startDate = new DateTime($experiences[0]->getStart());
            foreach ($experiences as $exp) {
                $start = new DateTime($exp->getStart());
                $start = $start->format('M Y');

                if (is_null($exp->getStop())) {
                    $stop = "";
                } else {
                    $stop = new DateTime($exp->getStop());
                    $stop = " to " . $stop->format('M Y');
                }

                echo "<div class='experiences'>";
                echo "<div class='titleanddesc'>";
                echo "<p class='title'>" . $exp->getTitle() . " at " . $exp->getPlaceName() . " (" . $exp->getPlaceCity() . ")" . " from " . $start . $stop . "</p>";
                echo "<p class='description'>" . $exp->getDescription() . "</p>";
                echo "</div>";
                echo "<div name='reloader'>";
                //SKILLS
                if (!is_null($exp->getUsedSkills()) && !is_null($exp->getMasteringSkills())) {
                    $usedSkills = $exp->getUsedSkills();
                    $masteringSkills = $exp->getMasteringSkills();
                    echo "Skills used : \n";
                    echo "<br>";
                    if (!empty($usedSkills)) {
                        foreach ($usedSkills as $skill) {
                            echo "<div data-value='" . $skill->getId() . "' class='used' name='purple' expid='" . $exp->getId() . "'>" . $skill->getName() . "</div>";
                        }
                    }
                    if (!empty($masteringSkills)) {
                        foreach ($masteringSkills as $skill) {
                            echo "<div class='mastering'> " . $skill->getName() . "</div> ";
                        }
                    }


                    echo "<br>";
                    //DELETE BUTTON 
                    echo "<input type='hidden' name='user' value='" . $user->getUserId() . "'>";

                    echo "<button style='display:none;' class='experiencedeletebutton' id='experiencedeletebutton' expPlace='" . $exp->getPlaceName() . "' expCity='" . $exp->getPlaceCity() . "' expTitle='" . $exp->getTitle() . "' expId='" . $exp->getId() . "'>DELETE</button>";
                    echo "<button class='experiencedeletebuttonnomodal' disabled><a style='color:white; text-decoration:none;' href='experience/delete_confirm/" . $exp->getId() . "/" . $user->getUserId() .  "'>DELETE</a></button>";


                    //EDIT BUTTON
                    echo "<form action='experience/edit' method='get'>";
                    echo "<button disabled id='experienceeditbutton'><a style='color:white; text-decoration:none;' href='experience/edit/" . $exp->getId() . "/" . $exp->getUser() . "'>EDIT</a></button>";

                    echo "</form>";
                    echo "</div>";
                }
                $i++;
                echo "</div>";
            }
        } else {
            echo "No experiences to show here !";
        }
        echo "</div>";



        echo "<div id='content'></div>";
        echo "<div id='confirmDialog' title='Attention' hidden>";
        echo "</div>";



        echo "<br>";
        //ADD BUTTON
        echo "<div class='add'>";
        echo "<button id='experienceaddbutton' disabled><a style='color:white; text-decoration:none;' href='experience/add/" . $user->getUserId() . "'>ADD</a></button>";
        echo "</div>";

        echo "</div>";

        ?>
    </div>
    <div id="content"></div>
    <div id="confirmDialogCalendar" title="Attention" hidden>
        <p>Confirmez-vous le lancement de cette opération irréversible ?</p>
    </div>


    <script>
        var start;
        var stop;
        var i = 0;
        var html = "";
        var experiences = "";
        var event = "";
        var skillclickerid;
        var user = <?= $user->getUserId() ?>;
        var startYear;
        var startDateCallendar;
        var currentDate = new Date().getFullYear();
        let startfilter;
        let endfilter;
        var calendar;
        var resetDraggingStart;
        var resetDraggingStop;
        var resetResizingStart;
        var resetResizingStop;
        var user_bday;
        let showSlider;

        $(function() {


            //to get the value form dev.ini to know if it should show the Slider or not.
            getSliderValue();
            $('#viewSwitcher').css('display', '');
            $('.experiencedeletebutton').css('display', '');
            $('.experiencedeletebuttonnomodal').css('display', 'none');




            //Enables the switch between list and calendar view by changing the button name and attributes of corresponding 'views'
            //everything happens on click on the div
            $('#viewSwitcher').click(function() {
                if ($('#listview').attr('view') == 'calendarview') {
                    //hides calendar
                    $('#calendar').css('display', 'none');
                    //makes display visible
                    $('#listview').css('display', '');
                    //changes the attribute to notify the view change 
                    $('#listview').attr('view', 'listview');
                    //changes what's written on the button
                    $('#viewSwitcher').html('CALENDAR VIEW');
                    //Launches display of calendar with empty filter to display changes in list view after fullcalendar change
                    getFilteredExperiences("", "");
                    skillClicker();
                } else {
                    //hides list view
                    $('#listview').css('display', 'none');
                    //shows calendar
                    $('#calendar').css('display', '');
                    //changes the attribute to notify the view change 
                    $('#listview').attr('view', 'calendarview');
                    //changes what's written on the button
                    $('#viewSwitcher').html('LIST VIEW');

                    skillClicker();
                }
                //Gets the birthdate of the current user
                getBirthDate();
                //Starts calendar generation
                launchCalendar();


                //Beatifull color change on hover (optional)
            }).hover(function() {
                $('#viewSwitcher').css('background-color', 'blue');
                $('#viewSwitcher').css('color', 'white');
            }, function() {
                $('#viewSwitcher').css('background-color', 'white');
                $('#viewSwitcher').css('color', 'blue');
            });


            //Enables skill click to add them to skillList
            skillClicker();
            modalDel();
            //Manages year errors of inputs and gets the JSon callback to launch display of filtered dates
            $('.filters').children('input').on('input', (function() {
                startfilter = $('input[name="startFilter"]').val();
                endfilter = $('input[name="endFilter"]').val();
                if ((startfilter > endfilter)) {
                    $('yearerror').html("Please enter a correct range.").css('color', 'red');
                    //Exclusive OR
                } else if ((startfilter.length != 0 && endfilter.length == 0) || (startfilter.length == 0 && endfilter.length != 0)) {
                    $('yearerror').html("Both fields must be filled.").css('color', 'red');
                } else if ((endfilter >= currentDate + 1) || (startfilter >= currentDate + 1)) {
                    $('yearerror').html("End and start date cannot be in the future.").css('color', 'red');
                } else if ((startfilter.length != 4 || endfilter.length != 4) && (startfilter.length != 0 && endfilter.length != 0)) {
                    $('yearerror').html("Start and end year must be a 4 digit number.").css('color', 'red');
                } else {
                    $('yearerror').html("");
                    //calls Experience/getFilteredExperienceService in controller with the start and stop values being passed to the function
                    getFilteredExperiences(startfilter, endfilter);
                }
            }));

            displaySlider();
        });

        function modalDel() {

            $(".experiencedeletebutton").click(function() {
                console.log("aaaaa");
                var user = <?= $user->getUserId() ?>;
                var expId = $(this).attr('expId');
                var title = $(this).attr('expTitle');
                var city = $(this).attr('expCity');
                var place = $(this).attr('expPlace');
                var html = "<p>Are you sure u want to delete experience : " + title + " at " + place + " ( " + city + " )? </p>";
                $('#confirmDialog').html(html);

                $('#confirmDialog').dialog({

                    resizable: false,
                    height: 300,
                    width: 500,
                    modal: true,
                    autoOpen: true,
                    buttons: {
                        Yes: function() {
                            $.ajax({
                                url: 'experience/delete',
                                type: 'POST',
                                data: {
                                    experienceId: expId,
                                    userId: user
                                },
                                success: function(data) {

                                    $(".experienceAndBtns").load(' .experienceAndBtns', function() {
                                        console.log("modal del relaunched");
                                        $('.experiencedeletebutton').css('display', '');
                                        $('.experiencedeletebuttonnomodal').css('display', 'none');
                                        modalDel();
                                    });
                                }
                            });

                            $(this).dialog("close");
                        },
                        No: function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function() {}
                });
            });
        }


        function displaySlider() {
            console.log()
            $("#slider-range").slider({
                range: true,
                min: 1900,
                max: 2100,
                values: [1950, 2050],
                slide: function(event, ui) {
                    $("#period").val(ui.values[0] + " to " + ui.values[1]);
                    getFilteredExperiences(ui.values[0], ui.values[1])
                }
            });
            $("#period").val($("#slider-range").slider("values", 0) +
                " to " + $("#slider-range").slider("values", 1));
        }

        function getSliderValue() {
            $.post("experience/getSliderParameter", function(data) {
                showSlider = data;
                //NOJS filter/slider hiding
                if (showSlider === "1") {
                    $('.slider').css('display', '');
                } else {
                    $('.filters').css('display', '');
                }
            })
        }


        function getFilteredExperiences(start, end) {
            $.post("Experience/getFilteredExperienceService", {
                start: start,
                stop: end,
                user: <?= $user->getUserId() ?>
                //on callback of post, when result is loaded, we do the following :
            }, function(data) {
                //checks if data is empty and creates an empty experience list so we can display the no experience message
                if (Object.keys(data).length === 0) {
                    experiences = " ";
                    displayExperiences();
                    //if experiences is not empty we parse the Json doc and attribute the data to global variable 'experiences' we use in display   
                } else {
                    data = $.parseJSON(data);

                    experiences = data;
                    console.log(experiences);
                    displayExperiences();
                }
            });
        }

        //Uses the experiences gotten through JSON callback to make new html content with JSON array values
        function displayExperiences() {
            $('.experienceAndBtns').html(html);
            if (experiences === " ") {
                html += "No experiences to show for this range."
                $('.experienceAndBtns').append(html);
                html = "";
            } else {
                for (let experience of experiences) {

                    html += "<div class='experiences'>";
                    html += "<div class='titleanddesc'>";
                    if (experience.stop === null) {
                        html += "<p class='title'>" + experience.title + " at " + experience.placename + " (" + experience.city + ")" + " from " + experience.start + "</p>";
                    } else {
                        html += "<p class='title'>" + experience.title + " at " + experience.placename + " (" + experience.city + ")" + " from " + experience.start + " to " + experience.stop + "</p>";
                    }
                    html += "<p class='description'>" + experience.description + "</p>";
                    html += "</div>";
                    html += "<div name='reloader'>";
                    html += "<p>Skills used : </p>";
                    for (let use of experience.used) {
                        html += "<div  data-value='" + use.id + "' class='used' name='purple' expid='" + experience.id + "'>" + use.name + "</div>";
                    }
                    for (let master of experience.mastering) {
                        html += "<div class='mastering' data-value='" + master.id + "'> " + master.name + "</div> ";
                    }
                    html += "</div>";

                    html += "<button class='experiencedeletebutton' id='experiencedeletebutton' expPlace='" + experience.placename + "' expCity='" + experience.city + "' expTitle='" + experience.title + "' expId='" + experience.id + "'>DELETE</button>";
                    html += "<input type='hidden' name='user' value='" + experience.user + "'>";
                    html += "<form action='experience/edit' method='post'>";
                    html += "<button id='experienceeditbutton' disabled><a style='color:white; text-decoration:none;' href='experience/edit/" + experience.id + '/' + experience.user + "'>EDIT</a></button>";
                    html += "<input type='hidden' name='user' value='" + experience.user + "'>";
                    html += "</form>";
                    html += "</div>";
                }
                $('.experienceAndBtns').append(html);
                html = "";
            }
            html = "";
            //we call skillclicker and modalDel again to be able to click on newly loaded html
            skillClicker();
            modalDel();

        };

        // Enables skills to be 'clickable' and turns them to blue when clicked
        function skillClicker() {
            $('div.used').click(function() {
                //we store the id to be able to turn all the skills with the same name to blue when clicked
                skillclickerid = $(this).attr("data-value");
                //we start by turning the clicked skill to blue
                $(this).css('background-color', 'blue');
                //simplified ajax post method to launch the push in database
                $.post("Experience/skillClick", {
                    skill: skillclickerid,
                    experience: $(this).attr('expid'),
                    user: user,
                    //we search for each other button with the same id so we can turn them to blue too
                }, $('div.used').each(function() {
                    //if the div has the same id of skill we turn them all to blue
                    if ($(this).attr("data-value") == skillclickerid) {
                        $(this).css('background-color', 'blue');
                    }
                }));
            });
        };

        function initCalendar(startDateCallendar) {
            var calendarEl = $('#calendar')[0];

            calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                timeZone: 'UTC',
                headerToolbar: {
                    left: 'today prev,next',
                    center: 'title',
                    right: 'timelineMax,timelineTwentyYear,timelineTenYear,timelineFiveYear,timelineYear'
                },
                initialView: 'timelineMax',
                aspectRatio: 1.5,
                initialDate: startDateCallendar,
                events: {
                    url: "Experience/getExperiencesForCalendar",
                    method: 'POST',
                    extraParams: {
                        userforcalendar: user
                    },

                },
                eventDragStart: function(info) {
                    manageEventStart(info);
                },
                eventDrop: function(info) {
                    manageEventStop(info);
                },
                eventResizeStart: function(info) {
                    manageEventStart(info);
                },
                eventResize: function(info) {
                    manageEventStop(info);
                },
                views: {
                    timelineMax: {
                        buttonText: 'MAX',
                        type: 'timelineYear',
                        duration: {
                            years: new Date().getFullYear() - startYear + 1
                        },
                        slotDuration: {
                            years: 1
                        },
                        slotLabelFormat: [{
                                year: 'numeric'
                            }, // top level of text
                        ],
                    },
                    timelineTwentyYear: {
                        buttonText: '20 Years',
                        type: 'timelineYear',
                        duration: {
                            years: 20
                        },
                        slotDuration: {
                            years: 1
                        },
                        slotLabelFormat: [{
                                year: 'numeric'
                            }, // top level of text
                        ],
                    },
                    timelineTenYear: {
                        buttonText: '10 Years',
                        type: 'timelineYear',
                        duration: {
                            years: 10
                        },
                        slotDuration: {
                            months: 6
                        },
                        slotLabelFormat: [{
                                year: 'numeric'
                            }, // top level of text
                            {
                                month: 'short'
                            } // lower level of text
                        ],
                    },
                    timelineFiveYear: {
                        type: 'timeline',
                        buttonText: '5 Years',
                        duration: {
                            years: 5
                        },
                        slotDuration: {
                            months: 3
                        },
                    },
                    timelineYear: {
                        buttonText: '1 Year',
                        type: 'timelineYear',
                        duration: {
                            years: 1
                        },
                        slotDuration: {
                            months: 1
                        },
                        slotLabelFormat: [{
                                year: 'numeric'
                            }, // top level of text
                            {
                                month: 'short'
                            } // lower level of text
                        ],
                    }
                },
                editable: true,
            }, );
            calendar.render();
        }

        //gets the info of the events before they are moved in order to use them later to put them back into place if needed
        function manageEventStart(info) {
            resetDraggingStart = info.event.start;
            resetDraggingStop = info.event.end;
        }

        //gets the info of events after they are dropped or resized in order to update properly
        //also launches jqueryUI popup with desired parameters        
        function manageEventStop(info) {
            console.log(info.event.end);
            var ret = null;
            var event = info;
            var eventStopStartDate = event.event.start;
            var eventStopEndDate = event.event.end;
            var eventStopStartDateLabel;
            var eventStopEndDateLabel;
            //used to compare after drop or resize if the end date (or start date) is after date of today
            var currentDate = new Date();
            //basic message in all popups
            var html = "<p style='font-weight : bold;'>Are you sure you want to change experience : " + event.event.title + ".</p>";
            //first validation for current date
            if (eventStopEndDate.getFullYear() > currentDate.getFullYear() || eventStopStartDate.getFullYear() > currentDate.getFullYear()) {

                if (eventStopEndDate.getFullYear() > currentDate.getFullYear()) {
                    eventStopEndDate.setFullYear(currentDate.getFullYear());
                    //console.log(eventStopEndDate);
                }
                if (eventStopStartDate.getFullYear() > currentDate.getFullYear()) {
                    eventStopStartDate.setFullYear(currentDate.getFullYear());
                    //console.log(eventStopStartDate);
                }

                eventStopStartDateLabel = eventStopStartDate.toString().substring(4, 16);
                eventStopEndDateLabel = eventStopEndDate.toString().substring(4, 16);
                html += "<p style='font-weight : bold; color : red;'>You are trying to put an experience in the future,start and/or end date will be set to today's date</p>";
                html += "<br/>"
                html += "<p>With this range : " + eventStopStartDateLabel + " to " + eventStopEndDateLabel + ".</p>";

                //Validation to be unable to put the event before the user's birthdate
            } else if (user_bday > eventStopStartDate.getFullYear()) {
                eventStopStartDate = resetDraggingStart;
                eventStopEndDate = resetDraggingStop;

                eventStopStartDateLabel = eventStopStartDate.toString().substring(4, 16);
                eventStopEndDateLabel = eventStopEndDate.toString().substring(4, 16);
                html += "<p style='font-weight : bold; color : red;'>You are trying to put an experience before your birthdate ,start and/or end date will be set to it's original start and end (no matter what you choose).</p>";
                html += "<br/>"
                html += "<p>With this range : " + eventStopStartDateLabel + " to " + eventStopEndDateLabel + ".</p>";

                //Every validation passed we don't need further formatting of parameters
            } else {

                eventStopStartDateLabel = eventStopStartDate.toString().substring(4, 16);
                eventStopEndDateLabel = eventStopEndDate.toString().substring(4, 16);
                html += "<br/>"
                html += "<p>With this range : " + eventStopStartDateLabel + " to " + eventStopEndDateLabel + ".</p>";
            }
            //changes the content of the jqueryUI message in popup
            $('#confirmDialogCalendar').html(html);
            //launches the dialog popup
            $('#confirmDialogCalendar').dialog({
                resizable: false,
                height: 300,
                width: 500,
                modal: true,
                autoOpen: true,
                buttons: {
                    Yes: function() {
                        ret = "oui";
                        $(this).dialog("close");
                    },
                    No: function() {
                        ret = "non";
                        $(this).dialog("close");
                    }
                },
                close: function() {
                    if (ret !== null) {
                        if (ret === "oui") {
                            //if user answered yes we put the event in place and update the experience with given params
                            //(probably unnecessary since fullcalendar already puts the object into place)
                            event.event.setStart(eventStopStartDate);
                            event.event.setEnd(eventStopEndDate);
                            updateExperience(event);
                        } else {
                            //user answered no
                            //puts the event back where it started with original values
                            info.event.setStart(resetDraggingStart);
                            info.event.setEnd(resetDraggingStop);
                        }
                    } else {
                        //user quit the dialog
                        //puts the event back where it started with original values
                        info.event.setStart(resetDraggingStart);
                        info.event.setEnd(resetDraggingStop);
                        alert("You didn't answer, nothing happened...");
                    }
                }
            });


        }

        //launches ajax method to update experience in DB
        function updateExperience(updateEvent) {
            $.post("Experience/edit_experience_service", {
                userId: user,
                id: updateEvent.event.id,
                start: updateEvent.event.start,
                stop: updateEvent.event.end

            });
        }

        //Launches the calendar 
        function launchCalendar() {
            $.post("Experience/getFirstDate_service", {
                    //the user wich we want the minimal experience date from
                    userforstartdate: user
                },
                function(data) {
                    //callback of the service, startDateCallendar will tell fullcalendar where to start initial view
                    startDateCallendar = data[0].date;
                    //getting the startYear appart to make the 'MAX' view in fullcalendar
                    startYear = startDateCallendar;
                    startYear = startYear.substring(0, 4);
                    //launching calendar with the startdate we just computed
                    initCalendar(startDateCallendar);
                }, "json");
        }

        //returns the birthdate of the current, logged user
        function getBirthDate() {
            $.post("user/get_bdate_service", {
                    user: user
                },
                function(data) {
                    bd = data.substring(0, 4);
                    user_bday = bd;
                    console.log(user_bday);
                }, "json");
        }
    </script>

</body>

</html>