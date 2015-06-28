$(function() {

    var paraTag = $('.contact-form #submit').parent('div');

    $(paraTag).children('input').remove();
    $(paraTag).append('<input  class="contact_button button btn btn-primary btn-block" type="button" name="submit" id="submit" value="Send Message" />');

    $('.contact-form #submit').click(function() {
        var name = $('input#name').val();
        var email = $('input#email').val();
        var message = $('textarea#message').val();
        var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
        var subject = $('input#subject').val();
        if (name == '')
        {
            $('[name="name"]').addClass('vaidate_error');
        } else {
            $('[name="name"]').removeClass('vaidate_error');
        }

        if (email == '')
        {
            $('[name="email"]').addClass('vaidate_error');
        } else {
            if (!pattern.test(email)) {
                $('[name="email"]').addClass('vaidate_error');
            } else {
                $('[name="email"]').removeClass('vaidate_error');
            }
        }


        if (message == "")
        {
            $('[name="message"]').addClass('vaidate_error');
        } else {
            $('[name="message"]').removeClass('vaidate_error');
        }
        if (subject == "")
        {
            $('[name="subject"]').addClass('vaidate_error');
        } else {
            $('[name="subject"]').removeClass('vaidate_error');
        }

        $.ajax({
            type: 'post',
            url: 'sendEmail.php',
            data: 'name=' + name + '&email=' + email + '&subject=' + subject + '&message=' + message,
            success: function(results) {
                $('div#response').html(results).css('display', 'block');

            }
        }); // end ajax
    });



    var paraTag2 = $('.contact-form-2 #submit').parent('div');

    $(paraTag2).children('input').remove();
    $(paraTag2).append('<input  class="contact_button btn btn-primary" type="button" name="submit" id="submit" value="Select" />');



    $('.contact-form-2 #submit').click(function() {
        var name = $('input#name').val();
        var email = $('input#email').val();
        var message = $('textarea#message').val();
        var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
        var subject = $('input#subject').val();
        var phone = $('input#phone').val();

        if (phone == '')
        {
            $('[name="phone"]').addClass('vaidate_error');
        } else {
            $('[name="phone"]').removeClass('vaidate_error');
        }

        if (name == '')
        {
            $('[name="name"]').addClass('vaidate_error');
        } else {
            $('[name="name"]').removeClass('vaidate_error');
        }

        if (email == '')
        {
            $('[name="email"]').addClass('vaidate_error');
        } else {
            if (!pattern.test(email)) {
                $('[name="email"]').addClass('vaidate_error');
            } else {
                $('[name="email"]').removeClass('vaidate_error');
            }
        }


        if (message == "")
        {
            $('[name="message"]').addClass('vaidate_error');
        } else {
            $('[name="message"]').removeClass('vaidate_error');
        }
        if (subject == "")
        {
            $('[name="subject"]').addClass('vaidate_error');
        } else {
            $('[name="subject"]').removeClass('vaidate_error');
        }

        $.ajax({
            type: 'post',
            url: 'sendEmail.php',
            data: 'name=' + name + '&phone=' + phone + '&email=' + email + '&subject=' + subject + '&message=' + message,
            success: function(results) {
                $('div#response').html(results).css('display', 'block');

            }
        }); // end ajax
    });
});
		