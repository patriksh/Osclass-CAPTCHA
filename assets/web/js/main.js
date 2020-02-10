$(function() {
    $('.advcaptcha-refresh').click(function() {
        var type = $(this).attr('data-type');
        var session = $(this).attr('data-session');
        var post_data = {
            'type': type,
            'key': session
        }

        if(type == 'qna') {
            post_data.q = $('.advcaptcha-q').text();
        }

        $.ajax({
            url: advcaptcha_refresh_url,
            type: 'POST',
            data: post_data,
            success: function(captcha) {
                response = JSON.parse(captcha);
                switch(type) {
                    case 'math':
                        $('.advcaptcha-num1').html(response.problem.num1);
                        $('.advcaptcha-num2').html(response.problem.num2);
                    break;
                    case 'qna':
                        $('.advcaptcha-q').html(response.problem.question);
                    break;
                    case 'text':
                        $('.advcaptcha-img').attr('src', response.problem.img);
                    break;
                }
            }
        });
    });
});
