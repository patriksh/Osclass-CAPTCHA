$(function() {
    $('.advcaptcha-refresh').click(function() {
        $.ajax({
            url: advcaptcha_refresh_url,
            type: 'POST',
            cache: false,
            success: function(captcha) {
                response = JSON.parse(captcha);
                switch(response.type) {
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