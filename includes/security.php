<?php
/**
 * Security functions: CSRF, rate limiting, headers, sanitization
 */

// Start session for CSRF + rate-limiting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Set security headers
 */
function set_security_headers(): void {
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
    // CSP: allow self, Yandex Maps, inline styles/scripts needed for map
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://api-maps.yandex.ru https://yandex.ru; style-src 'self' 'unsafe-inline'; img-src 'self' data: https://*.yandex.ru https://*.yandex.net; frame-src https://yandex.ru https://*.yandex.ru;");
}

/**
 * Generate CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function csrf_validate(string $token): bool {
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Regenerate CSRF token after successful validation
 */
function csrf_regenerate(): void {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Generate image-based CAPTCHA
 * Creates a math challenge with a distorted image
 */
function captcha_generate(): array {
    $operators = ['+', '-', '*'];
    $op = $operators[random_int(0, 2)];

    switch ($op) {
        case '+':
            $a = random_int(10, 50);
            $b = random_int(10, 50);
            $answer = $a + $b;
            break;
        case '-':
            $a = random_int(20, 60);
            $b = random_int(1, $a - 1);
            $answer = $a - $b;
            break;
        case '*':
            $a = random_int(2, 12);
            $b = random_int(2, 9);
            $answer = $a * $b;
            break;
        default:
            $a = random_int(10, 50);
            $b = random_int(10, 50);
            $answer = $a + $b;
    }

    $opSymbol = $op === '*' ? "\u{00D7}" : $op;
    $question = "{$a} {$opSymbol} {$b} = ?";

    $_SESSION['captcha_answer'] = $answer;
    $_SESSION['captcha_time'] = time();

    return ['question' => $question, 'answer' => $answer];
}

/**
 * Validate CAPTCHA answer with time limit (5 min)
 */
function captcha_validate(string $input): bool {
    if (empty($_SESSION['captcha_answer']) || empty($_SESSION['captcha_time'])) {
        return false;
    }
    // Expire after 5 minutes
    if (time() - $_SESSION['captcha_time'] > 300) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time']);
        return false;
    }
    $valid = (int)$input === (int)$_SESSION['captcha_answer'];
    if ($valid) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time']);
    }
    return $valid;
}

/**
 * Generate CAPTCHA image (PNG) as base64 data URI
 */
function captcha_image(string $text): string {
    $width = 200;
    $height = 60;
    $img = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($img, 13, 15, 25); // dark bg
    $textColor = imagecolorallocate($img, 0, 212, 255); // cyan
    $noiseColor = imagecolorallocate($img, 40, 50, 70);

    imagefill($img, 0, 0, $bgColor);

    // Add noise lines
    for ($i = 0; $i < 8; $i++) {
        imageline($img,
            random_int(0, $width), random_int(0, $height),
            random_int(0, $width), random_int(0, $height),
            $noiseColor
        );
    }

    // Add noise dots
    for ($i = 0; $i < 100; $i++) {
        imagesetpixel($img, random_int(0, $width), random_int(0, $height), $noiseColor);
    }

    // Remove "= ?" for image display, just show the expression
    $display = str_replace(' = ?', '', $text);

    // Draw text with built-in font (size 5 = largest built-in)
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($display);
    $textHeight = imagefontheight($fontSize);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;

    imagestring($img, $fontSize, (int)$x, (int)$y, $display, $textColor);

    // Output as base64
    ob_start();
    imagepng($img);
    $data = ob_get_clean();
    imagedestroy($img);

    return 'data:image/png;base64,' . base64_encode($data);
}

/**
 * Rate limiter: max $limit submissions per $window seconds
 */
function rate_limit_check(string $action = 'form', int $limit = 3, int $window = 300): bool {
    $key = "rate_{$action}";
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    $now = time();
    // Remove expired entries
    $_SESSION[$key] = array_filter($_SESSION[$key], fn($t) => ($now - $t) < $window);

    if (count($_SESSION[$key]) >= $limit) {
        return false; // Rate limited
    }

    $_SESSION[$key][] = $now;
    return true;
}

/**
 * Sanitize output for HTML (XSS protection)
 */
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Validate INN (10 digits for org, 12 for individual)
 */
function validate_inn(string $inn, bool $is_org = true): bool {
    $digits = preg_replace('/\D/', '', $inn);
    return $is_org ? strlen($digits) === 10 : strlen($digits) === 12;
}

/**
 * Validate email strictly
 */
function validate_email_strict(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone (Russian format)
 */
function validate_phone(string $phone): bool {
    $digits = preg_replace('/\D/', '', $phone);
    return strlen($digits) >= 10 && strlen($digits) <= 12;
}
