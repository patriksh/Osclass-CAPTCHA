<?php
/* Developed by defected.dev | 2021
 *
 * https://github.com/dftd/Osclass-CAPTCHA
*/

class AdvCAPTCHA_Problems {
    public static function generate($type, $qnaExclude = '') {
        switch($type) {
            case 'math':
                return self::generateMath();
            break;
            case 'text':
                return self::generateText();
            break;
            case 'qna':
                return self::generateQNA($qnaExclude);
            break;
        }

        return null;
    }

    public static function verify($captcha) {
        $type = $captcha['type'];
        $problem = $captcha['problem'];
        $answer = Params::getParam('advcaptcha');

        switch($type) {
            case 'recaptcha':
                return self::verifyRecaptcha(Params::getParam('recaptcha_response'));
            break;
            case 'hcaptcha':
                return self::verifyHcaptcha(Params::getParam('h-captcha-response'));
            break;
            case 'math':
                return self::verifyMath($problem, $answer);
            break;
            case 'text':
                return self::verifyText($problem, $answer);
            break;
            case 'qna':
                return self::verifyQNA($problem, $answer);
            break;
        }

        return false;
    }

    public static function generateMath() {
        $max = 10;
        $num1 = rand(1, $max);
        $num2 = rand(1, $max);

        return [
            'type' => 'math',
            'num1' => $num1,
            'num2' => $num2,
            'answer' => $num1 + $num2
        ];
    }

    public static function generateText() {
        $length = 5;

        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($chars) - 1;

        $string = '';
        for($i = 0; $i <= $length; $i++) {
            $string .= substr($chars, rand(0, $max), 1);
        }
    
        return [
            'type' => 'text',
            'answer' => $string,
            'image' => self::textToImage($string)
        ];
    }

    public static function generateQNA($exclude) {
        $questions = (array) json_decode(AdvCAPTCHA_Helper::getPreference('questions'), true);
        if(!count($questions)) return false;

        // Exclude current question if we're refreshing.
        if($exclude != '' && count($questions) > 1) {
            foreach($questions as $question => $answer) {
                if($question == $exclude) {
                    unset($questions[$question]);
                }
            }
        }

        $questions = AdvCAPTCHA_Helper::shuffleAssoc($questions);

        return [
            'type' => 'qna',
            'question' => key($questions),
            'answer' => current($questions)
        ];
    }

    public static function verifyRecaptcha($response) {
        $recaptcha = osc_file_get_contents('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => AdvCAPTCHA_Helper::getPreference('recaptcha_secret_key'),
            'response' => $response,
        ]);

        $recaptcha = json_decode($recaptcha);
    
        return $recaptcha->score >= (float) AdvCAPTCHA_Helper::getPreference('recaptcha_threshold');
    }

    public static function verifyHcaptcha($response) {
        $hcaptcha = osc_file_get_contents('https://hcaptcha.com/siteverify', [
            'secret' => AdvCAPTCHA_Helper::getPreference('hcaptcha_secret_key'),
            'response' => $response,
        ]);

        $hcaptcha = json_decode($hcaptcha);
    
        return $hcaptcha->success;
    }

    public static function verifyMath($problem, $answer) {
        return (int) $answer === (int) $problem['answer'];
    }

    public static function verifyText($problem, $answer) {
        return trim(strtolower($answer)) == trim(strtolower($problem['answer']));
    }

    public static function verifyQNA($problem, $answer) {
        return trim(strtolower($answer)) == trim(strtolower($problem['answer']));
    }

    public static function textToImage($string, $width = 250, $height = 80, $fontsize = 24) {
        $font = ADVCAPTCHA_PATH . 'assets/web/font.ttf';
        $background = ADVCAPTCHA_PATH . 'assets/web/pattern.jpg';
    
        $captcha = imagecreatetruecolor($width, $height);
        list($bx, $by) = getimagesize($background);
        $bx = ($bx - $width < 0) ? 0 : rand(0, $bx - $width);
        $by = ($by - $height < 0) ? 0 : rand(0, $by - $height);
        $background = imagecreatefromjpeg($background);
        imagecopy($captcha, $background, 0, 0, $bx, $by, $width, $height);
    
        $text_size = imagettfbbox($fontsize, 0, $font, $string);
        $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
        $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);
    
        $centerX = ceil(($width - $text_width) / 2);
        $centerX = $centerX < 0 ? 0 : $centerX;
        $centerX = ceil(($height - $text_height) / 2);
        $centerY = $centerX < 0 ? 0 : $centerX;
    
        if(rand(0, 1)) {
            $centerX -= rand(0,55);
        } else {
            $centerX += rand(0,55);
        }
        $colornow = imagecolorallocate($captcha, rand(0, 100), rand(0, 100), rand(0, 100));
        imagettftext($captcha, $fontsize, rand(-10, 10), $centerX, $centerY, $colornow, $font, $string);
    
        ob_start();
        imagejpeg($captcha);
        imagedestroy($captcha);
        $contents = ob_get_contents();
        ob_end_clean();
    
        return 'data:image/jpeg;base64,' . base64_encode($contents);
    }
}