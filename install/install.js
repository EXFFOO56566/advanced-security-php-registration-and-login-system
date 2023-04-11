$(function() {
    var options = { buttons: { submitText: "Install" } };
    var wizard = $("#as-wizard").wizard(options);
    wizard.show();

    $(".close").remove();
    $(".modal").css("margin-top","-315px");
    
    $("#encryption-bcrypt").click(function () {
        $("#choice-wrapper-bcrypt").removeClass()
                                   .addClass("alert")
                                   .addClass("alert-success");
        $("#choice-wrapper-sha").removeClass()
                                .addClass("alert")
                                .addClass("alert-error");
    });
    
    $("#encryption-sha512").click(function () {
        $("#choice-wrapper-bcrypt").removeClass()
                                   .addClass("alert")
                                   .addClass("alert-error");
        $("#choice-wrapper-sha").removeClass()
                                .addClass("alert")
                                .addClass("alert-success");
    });

    //When you click on any button ("Yes" and "No"), it store value in 
    //an hidden field
    $('div.btn-group button').click(function(){
        var text = $(this).text(),
            val  = $(this).val(),
            rel  = $(this).attr('rel');

        //store value in hidden field
        $(this).parents(".btn-group").find(".btn-group-value").val(val);
        
        //check if we are selecting bootstrap version
        if(typeof rel !== "undefined") {
            if(rel == "bv1")
                $(this).addClass("btn-success").next().removeClass("btn-success");
            else
                $(this).addClass("btn-success").prev().removeClass("btn-success");
            return;
        }
        
        
        //Yes - green, No - red
        if(text == "Yes" || text == "Enabled")
            $(this).addClass('btn-success').next().removeClass("btn-danger");
        else 
            $(this).addClass('btn-danger').prev().removeClass("btn-success");
    });

    $("#tw-enabled").click(function() { $(".tw-fields").slideDown('fast'); }).trigger('click');
    $("#tw-disabled").click(function() { $(".tw-fields").slideUp('fast'); }).trigger('click');

    $("#fb-enabled").click(function() { $(".fb-fields").slideDown('fast'); }).trigger('click');
    $("#fb-disabled").click(function() { $(".fb-fields").slideUp('fast'); }).trigger('click');

    $("#gp-enabled").click(function() { $(".gp-fields").slideDown('fast'); }).trigger('click');
    $("#gp-disabled").click(function() { $(".gp-fields").slideUp('fast'); }).trigger('click');

    $("#mailer").change(function () {
        if ( $(this).val() == 'smtp' )
            $("#smtp-wrapper").slideDown('fast');
        else
            $("#smtp-wrapper").slideUp('fast');
    });


    //database card validation
    wizard.cards["database"].on("validate", function(card) {
        $(".error-popover").remove();
        var ret = true;
        card.el.find("input").each(function () {
            var val = $(this).val();
            if(val == "" && $(this).attr('name') != "db_pass") {
                card.wizard.errorPopover($(this), "Field cannot be empty");
                ret = false;
            }
        });

        if(ret == false)
            return ret;

        var data = {
            db_type: $("#db_type").val(),
            db_host: $("#db_host").val(),
            db_name: $("#db_name").val(),
            db_user: $("#db_user").val(),
            db_pass: $("#db_pass").val()
        };
        $(".wizard-next").text("Checking db...")
                         .addClass("disabled")
                         .attr('disabled', "disabled");
        $.ajax({
            type: "POST",
            url: "install_helper.php",
            data: data,
            success: function (result) {
                if(result != "") {

                    var html  = '<div class="alert alert-error fade in">';
                        html += '<p class="alert-message">';
                        html += 'Error connecting to database! Check your details.';
                        html += '</p></div>';

                    $(".wizard-buttons-container").prepend(html);

                    setTimeout(function () {
                        $(".alert").fadeOut("slow",function () { 
                            $(this).remove();
                        });
                    }, 3000);
                }
                else {

                   var activeCard = wizard.getActiveCard();
                       activeCard.deselect()
                                 .markVisited();
                       wizard.cards['session'].select();
                }
                 $(".wizard-next").text("Next")
                                     .removeClass("disabled")
                                     .removeAttr("disabled");

            }
        });

        return false;
    });
    
    //site_info card validation
    wizard.cards['site_info'].on("validate", function(card) {
        card.el.find("input").popover('destroy');
        var el = card.el.find("input");
        var valid = true;
        el.each(function () {
            if($.trim( $(this).val() ) == "") {
                card.wizard.errorPopover($(this), "Field cannot be empty");
                valid = false;
            }
        });

        return valid;
    });

     wizard.cards["login"].on("validate", function(card) {
        card.el.find("input").popover('destroy');

        var at   = $("#max_login_attempts"),
            ret  = true,
            redirect = $("#redirect_after_login"),
            re   = /^[0-9]+$/;

         if($.trim(redirect.val()) == "") {
             card.wizard.errorPopover(redirect, "Field cannot be empty");
             ret = false;
         }

        if($.trim(at.val()) == "") {
            card.wizard.errorPopover(at, "Field cannot be empty");
            ret = false;
        }
        else if(!re.test(at.val())) {
            card.wizard.errorPopover(at, "This should be integer value.");
            ret = false;
        }

        return ret;

    });

    wizard.cards["misc"].on("validate", function(card) {
        card.el.find("input").popover('destroy');

        var at   = $("#prk_life"),
            ret  = true,
            re   = /^[0-9]+$/;

        if($.trim(at.val()) == "") {
            card.wizard.errorPopover(at, "Field cannot be empty");
            ret = false;
        }
        else if(!re.test(at.val())) {
            card.wizard.errorPopover(at, "This should be integer value.");
            ret = false;
        }

        return ret;

    });

    wizard.cards["social"].on("validate", function(card) {
        card.el.find("input").popover('destroy');

        var tw_login = $("#tw-login"),
            fb_login = $("#fb-login"),
            gp_login = $("#gp-login"),
            tw_key = $("#tw_key"),
            tw_secret = $("#tw_secret"),
            fb_id = $("#fb_id"),
            fb_secret = $("#fb_secret"),
            gp_id = $("#gp_id"),
            gp_secret = $("#gp_secret"),
            ret = true;

        if (tw_login.val() == 'true' )
        {
            if ( tw_key.val() == '' ) {
                card.wizard.errorPopover(tw_key, "Field cannot be empty");
                ret = false;
            }
            if ( tw_secret.val() == '' ) {
                card.wizard.errorPopover(tw_secret, "Field cannot be empty");
                ret = false;
            }
        }

        if (fb_login.val() == 'true' )
        {
            if ( fb_id.val() == '' ) {
                card.wizard.errorPopover(fb_id, "Field cannot be empty");
                ret = false;
            }
            if ( fb_secret.val() == '' ) {
                card.wizard.errorPopover(fb_secret, "Field cannot be empty");
                ret = false;
            }
        }

        if (gp_login.val() == 'true' )
        {
            if ( gp_id.val() == '' ) {
                card.wizard.errorPopover(gp_id, "Field cannot be empty");
                ret = false;
            }
            if ( gp_secret.val() == '' ) {
                card.wizard.errorPopover(gp_secret, "Field cannot be empty");
                ret = false;
            }
        }

        return ret;

    });

    //On wizard submit event
    wizard.on("submit", function(wizard) {
        $.ajax({
            url: "install_engine.php",
            type: "POST",
            data: wizard.serialize(),
            success: function(response) {
                console.log(response);
                if(response == "success") {
                    var html  = "<h3>Successful!</h3>";
                        html += "<p>Congratulations!</p>";
                        html += "<p>Advanced Security - PHP Registration/Login System";
                        html += "has been successfully installed on your server.</p>";
                        html += "<p>You can <a href='../login.php'>Login or register</a> now.</p>";
                    $("#finish-card").html(html);
                    wizard.hideButtons(); // hides the next and back buttons
                    wizard.updateProgressBar(100); // sets the progress meter to 0
                }
                else {
                     var html  = "<h3>Error!</h3>";
                         html += "<p>Ouch, something went wrong during the installation process.</p>";
                         html += "<p>Please check your browser's console to see server response.</p><br/>";
                         html += "<p><strong>Note:</strong>";
                         html += "If response in your browser's console is some strange HTML, that is probably XDEBUG error message.";
                         html += "Copy and paste that in some html or php file to see it properly formatted.</p>";
                    $("#finish-card").html(html);
                }
            }
        });
    });

});