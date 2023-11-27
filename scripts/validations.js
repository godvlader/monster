let settings = [];
let max = 300;

$.ajax({
    url: 'ini/getMax',
    type: 'get',
    dataType: 'json',
    success: function(data) {
        settings = data;
        max = Number.parseInt(settings['textLengthMax']);
        $("#maximum").text('/ ' + max);
    }
});

//character counter
function countChar(val) {
    var len = val.value.length;
    if (len >= max) {
        val.value = val.value.substring(0, max);
        $('#current').text(max);
        $('#current').css('color', 'red');
    } else if (len < 10) {
        $('#current').text(len);
        $('#current').css('color', 'red');
    } else {
        $('#current').text(len);
        $('#current').css('color', 'green');
    }
}

$(function() {
    $('#description').keyup(function() {
        countChar(this);
    });
});


//fin character counter

function iteration2(user) {

    $(function() {
        console.log("it2script");
        let bd;
        var userId = user;
        $("input:text:first").focus();
        $('#submit-btn').attr('disabled', 'true');

        $('input').on('keyup', function() {
            let empty = false;

            $('input').each(function() {
                empty = $(this).val().length == 0;
            });

            if (empty) {
                $('#submit-btn').attr('disabled', 'true');
            } else {
                $('#submit-btn').attr('disabled', 'false');
            }
        });



        $.post("user/get_bdate_service", {
                user: userId
            },
            function(data) {
                bd = data.substring(0, 4);
                // console.log(data);
            }, "json");

        //check birthday vs start date

        $('#StartDateit2').on('change', function() {
            console.log("changing startdate");
            checkStartAndBirthdate();
            checkDates();
        }).on('input', function() {
            console.log("input startdate");
            checkStartAndBirthdate();
            checkDates();
        });

        $('#endDateit2').on('change', function() {
            console.log("changing endate");
            checkStartAndBirthdate();
            checkDates();
        }).on('input', function() {
            console.log("input endate");
            checkStartAndBirthdate();
            checkDates();
        });

        $('#submit-btn').on('click', function(e) {
            var optionSelectedSubmit = $("option:selected", ('#placeSelection'));
            console.log(optionSelectedSubmit.val());
            placeCheck(optionSelectedSubmit.val());
            checkStartAndBirthdate();
            checkDates();
        });

        $('#placeSelection').on('change', function(e) {
            var optionSelectedChange = $("option:selected", ('#placeSelection'));
            placeCheck(optionSelectedChange.val());
        })


        function placeCheck(selection) {
            if (selection == -1) {
                $('#submit-btn').prop('disabled', true);
                $("#errPlace").css("color", "red");
                $("#placeSelection").css('box-shadow', '0 0 5px red');
                $("#errPlace").html("Place must be chosen and different from 'Select yout place'.");
            } else {
                $('#submit-btn').prop('disabled', false);
                $("#placeSelection").css('box-shadow', '0 0 5px green');
                $("#errPlace").css("color", "green");
                $("#errPlace").html("");
                $("#errPlace").html("Looks Good!");
            }
        }

        function checkStartAndBirthdate() {
            console.log('checkstart');

            var strt = new Date($("#StartDateit2").val());
            var now = new Date();
            strtFull = strt.getFullYear();
            $('#errStartDate').html("");
            // console.log(bd);
            if (strt.getTime() > now.getTime()) {
                $("#errStartDate").css("color", "red");
                $("#StartDateit2").css('box-shadow', '0 0 5px red');
                $("#errStartDate").html("Start date cannot be in the future.");
            } else if ($.isNumeric(strtFull) && bd > strtFull) {
                $("#errStartDate").css("color", "red");
                $("#StartDateit2").css('box-shadow', '0 0 5px red');
                $("#errStartDate").html("You can't enter a date before your birthdate!");
            } else if ($.isNumeric(strtFull) && bd < strtFull) {
                $("#errStartDate").css('color', 'green');
                $("#StartDateit2").css('box-shadow', '0 0 5px green');
                $("#errStartDate").html("Looks good! <i class='fas fa-check'></i>");
            } else {
                $("#errStartDate").css('color', 'green');
                $("#StartDateit2").css('box-shadow', '0 0 5px green');
                $("#errStartDate").html("Looks good! <i class='fas fa-check'></i>");
            }
        }

        //check start date vs end date
        function checkDates() {
            console.log("checkdates");
            var strt = new Date($("#StartDateit2").val());
            var end = new Date($("#endDateit2").val());
            var now = new Date();

            //console.log(strt.getTime() + "start date " + end.getTime());

            if (strt.getTime() === end.getTime()) {
                $("#errEndDate").css("color", "red");
                $("#endDateit2").css('box-shadow', '0 0 5px red');
                $("#errEndDate").html("Start date and end date can't be the same!");
            } else if (strt.getTime() > end.getTime()) {
                $("#errEndDate").css("color", "red");
                $("#endDateit2").css('box-shadow', '0 0 5px red');
                $("#errEndDate").html("End date can't be before start date!");
            } else if (end.getTime() > now.getTime()) {
                $("#errEndDate").css("color", "red");
                $("#endDateit2").css('box-shadow', '0 0 5px red');
                $("#errEndDate").html("End date cannot be in the future.");
            } else if (strt.getTime() < end.getTime()) {
                $("#errEndDate").css('color', 'green');
                $("#endDateit2").css('box-shadow', '0 0 5px green');
                $("#errEndDate").html("Looks good! <i class='fas fa-check'></i>");
            } else {
                $("#errEndDate").css('color', 'green');
                $("#endDateit2").css('box-shadow', '0 0 5px green');
                $("#errEndDate").html("Looks good! <i class='fas fa-check'></i>");
            }
        }
        let settings = [];
        let max = 300;

        $.ajax({
            url: 'ini/getMax',
            type: 'get',
            dataType: 'json',
            success: function(data) {
                settings = data;
                max = Number.parseInt(settings['textLengthMax']);
                $("#maximum").text('/ ' + max);
            }
        });

        //title validation
        $("#titleit2").on('input', function() {
            let text = $(this).val();
            let length = text.length;
            if (length > 128) {
                $("#errTitle").html("Too long! Title must be at least 3 characters long and at most 128 characters.");
                $("#errTitle").css('color', 'red');
                $(this).css('box-shadow', '0 0 5px red');
            } else if (length < 3) {
                $("#errTitle").html("Too short! Title must be at least 3 characters long and at most 128 characters.");
                $("#errTitle").css('color', 'red');
                $(this).css('box-shadow', '0 0 5px red');
            } else {
                $("#errTitle").css('color', 'green');
                $(this).css('box-shadow', '0 0 5px green');
                $("#errTitle").html("Looks good! <i class='fas fa-check'></i>");
            }
        });

        //if textarea fill equals max, disable writing in textarea
        $("#descriptionit2").on('input', function() {
            let text = $(this).val();
            let length = text.length;
            $("#current").text(length);
            $("#maximum").text('/ ' + max);
            if (length >= max) {
                $("#descriptionit2").css('box-shadow', '0 0 5px red');
                $('textarea').val((text).substring(0, max));
                $("#counter").css('color', 'red');
                $("#errDescription").html("Too long!");
                $("#errDescription").css('color', 'red');
            } else if (length < max && length < 10) {
                $("#descriptionit2").css('box-shadow', '0 0 5px red');
                $("#counter").css('color', 'red');
                $("#maximum").text('/ ' + max);
                $("#errDescription").html("Too short!");
                $("#errDescription").css('color', 'red');
            } else {
                $("#descriptionit2").css('box-shadow', '0 0 5px green');
                $("#counter").css('color', 'green');
                $("#errDescription").css('color', 'green');
                $("#errDescription").html("Looks good! <i class='fas fa-check'></i>");
            }
        });

    });
}

function iteration3(user, expIdIt3, startIt3, stopIt3, titleIt3, descriptionIt3) {

    $(function() {
        $('#description').keyup(function() {
            countChar(this);
        });
    });

    if (expIdIt3 == null || startIt3 == null || titleIt3 == null) {
        console.log(expIdIt3);
        console.log(startIt3);
        console.log(titleIt3);

        html = "";
        html += "<div class='just-validate-errors'>";
        html += "<p>Start date: </p>";
        html += "<input id='StartDate' type='date' name='start'>";
        html += "</div>";
        html += "<div class='just-validate-errors'>";
        html += "<p>End date (optional) : </p>";
        html += "<input id='endDate' type='date' name='stop' value='null'>";
        html += "</div>";
        html += "<div class='just-validate-errors'>";
        html += "<p>Title : </p>";
        html += "<input id='title' type='text' name='title'>";
        html += "</div>";
        html += "<div class='just-validate-errors'>";
        html += "<p>Description (optional) :</p>";
        html += "<textarea id='description' name='description' placeholder='Description must be between 10 and 128 characters. However, it is not required.' value=''></textarea>";
        html += "</div>";
    } else {
        if (descriptionIt3 == null) {
            descriptionIt3 = '';
        }
        console.log("else");


        html = "";
        html += "<input type='hidden' value='" + expIdIt3 + "' name='experienceId'>";
        html += "<input type='hidden' value='" + user + "' name='userId'>"
        html += "<div class='just-validate-errors'>";
        html += "<p>Start date: </p>";
        html += "<input id='StartDate' type='date' name='start' value='" + startIt3 + "'>";
        html += "</div>";
        html += "<div class='just-validate-errors'>";
        html += "<p>End date (optional) : </p>";
        html += "<input id='endDate' type='date' name='stop' value='" + stopIt3 + "'>";
        html += "</div>";
        html += "<div class='just-validate-errors'>";
        html += "<p>Title : </p>";
        html += "<input id='title' type='text' name='title' value='" + titleIt3 + "'>";
        html += "</div>";
        html += "<div class='just-validate-errors'>";
        html += "<p>Description (optional) :</p>";
        html += "<textarea id='description' name='description' placeholder='Description must be between 10 and 128 characters. However, it is not required.' value='" + descriptionIt3 + "'></textarea>";
        html += "</div>";

    }

    console.log("it3");


    $('#formswitcher').html("");
    $('#formswitcher').html(html);
    let bd, startDate, endDate, title, description, place;
    var data;

    $(function() {
        var userId = user;
        const validation = new JustValidate('#expForm', {
            errorFieldCssClass: 'errorsJV',
            errorFieldStyle: {
                border: '1px solid red',
            },
            errorLabelCssClass: 'just-validate-errors',
            errorLabelStyle: {
                color: 'red',
                textDecoration: 'underlined',
            },
            focusInvalidField: true,
            lockForm: true,

            errorContainer: 'just-validate-errors',
            successFieldStyle: {
                border: '1px solid green',
            },
            successFieldCssClass: 'successJV',
            successLabelCssClass: 'success'
        });

        validation
            .addField('#StartDate', [{
                    plugin: JustValidatePluginDate(() => ({
                        required: true,
                    })),
                    errorMessage: 'Start date is required.',
                },
                {
                    validator: function(value) {
                        return function() {
                            return fetch("user/check_birth/" + value + "/" + userId)
                                .then(response => response.json())
                        }
                    },
                    errorMessage: 'Start date must be after birth date',
                },
                {
                    validator: function(value) {
                        return function() {
                            return fetch("user/check_today/" + value)
                                .then(response => response.json())
                        }
                    },
                    errorMessage: 'Start date cannot be in the future.',
                }
            ], {
                successMessage: 'Looks good! start date',
            })

        .addField('#endDate', [{
            plugin: JustValidatePluginDate(() => ({
                required: false,
                isAfter: document.querySelector("#StartDate").value,
                isBefore: new Date()
            })),
            errorMessage: 'End date should be after start date and can\'t be in the future.',

        }], {
            successMessage: 'End date looks good!'
        })

        .addField('#title', [{
                rule: 'required',
                errorMessage: 'Please enter a title.'
            },
            {
                rule: 'minLength',
                value: 3,
                errorMessage: 'Title must be at least 3 characters.'
            },
            {
                rule: 'maxLength',
                value: max,
                errorMessage: 'Title must be less than ' + max + ' characters.'
            },
            {
                rule: 'customRegexp',
                value: /^[a-zA-Z\s]*$/,
                errorMessage: 'Title must start by a letter and must contain only letters.'
            },

        ], {
            successMessage: 'Title looks good!'
        })

        .addField("#description", [{
                rule: 'minLength',
                value: 10,
                errorMessage: 'Description must be at least 10 characters.'
            },
            {
                rule: 'maxLength',
                value: max,
                errorMessage: 'Description must be less than ' + max + ' characters.'
            }
        ], {
            successMessage: 'Description looks good!'
        })


        .onSuccess(function(event) {
                event.target.submit(); //par défaut le form n'est pas soumis

            })
            .onFail(function(event) {

            });

    });
}