<?php
/**
 * Registration form section
 * Processes via process-registration.php with CSRF, CAPTCHA, rate limiting
 */
$csrfToken = csrf_token();
$captchaData = captcha_generate();

// Check for success from previous submission
$regSuccess = false;
$regName = '';
if (!empty($_SESSION['reg_success'])) {
    $regSuccess = true;
    $regName = $_SESSION['reg_name'] ?? '';
    unset($_SESSION['reg_success'], $_SESSION['reg_name']);
}

// Check for errors from previous submission
$regErrors = $_SESSION['reg_errors'] ?? [];
$regOld = $_SESSION['reg_old'] ?? [];
unset($_SESSION['reg_errors'], $_SESSION['reg_old']);

// Generate CAPTCHA image if GD is available
$captchaImgSrc = '';
if (function_exists('imagecreatetruecolor')) {
    $captchaImgSrc = captcha_image($captchaData['question']);
}

$config = get_conference_config();
$smartCaptchaSiteKey = $config['smartcaptcha_sitekey'] ?? '';
?>
<section id="registration" class="section section--alt" aria-labelledby="registration-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Регистрация</p>
            <h2 id="registration-heading" class="text-balance" style="margin-top:0.75rem;">Зарегистрироваться</h2>
            <p class="section-desc">Выберите тип участия и заполните форму регистрации.</p>
        </div>

        <?php if ($regSuccess): ?>
            <div class="reg__success">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <h2 style="margin-top:1.5rem;">Регистрация завершена</h2>
                <p class="text-muted" style="margin-top:0.75rem;">
                    Благодарим за регистрацию<?= $regName ? ', ' . e($regName) : '' ?>.
                    Подтверждение отправлено на указанный адрес электронной почты.
                    Ждём вас на Пленуме ФУМО ВО ИБ.
                </p>
            </div>
        <?php else: ?>
            <!-- Participant type selector -->
            <div class="reg__selector">
                <button type="button" class="reg__selector-btn is-active" data-type="education">
                    Образовательные организации и ФОИВ
                </button>
                <button type="button" class="reg__selector-btn" data-type="other">
                    Иные организации
                </button>
            </div>

            <?php if (!empty($regErrors)): ?>
                <div class="reg__errors" style="margin-top:1rem;max-width:32rem;margin-left:auto;margin-right:auto;">
                    <ul>
                        <?php foreach ($regErrors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="process-registration.php" method="POST" class="reg__form" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="participant_type" id="participant-type-input" value="<?= e($regOld['participant_type'] ?? 'education') ?>">
                <input type="hidden" name="payment_type" id="payment-type-input" value="<?= e($regOld['payment_type'] ?? 'organization') ?>">

                <!-- Client-side error display -->
                <div class="reg__errors" style="display:none;"></div>

                <div class="form-group">
                    <label for="reg-fullname" class="form-label">ФИО <span class="required">*</span></label>
                    <input type="text" id="reg-fullname" name="fullname" required class="form-input"
                        placeholder="Иванов Иван Иванович"
                        value="<?= e($regOld['fullname'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="reg-email" class="form-label">Рабочая электронная почта <span class="required">*</span></label>
                    <input type="email" id="reg-email" name="email" required class="form-input"
                        placeholder="ivanov@university.ru"
                        value="<?= e($regOld['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="reg-organization" class="form-label">Организация <span class="required">*</span></label>
                    <input type="text" id="reg-organization" name="organization" required class="form-input"
                        placeholder="Название организации"
                        value="<?= e($regOld['organization'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="reg-position" class="form-label">Должность <span class="required">*</span></label>
                    <input type="text" id="reg-position" name="position" required class="form-input"
                        placeholder="Заведующий кафедрой"
                        value="<?= e($regOld['position'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="reg-phone" class="form-label">Телефон <span class="required">*</span></label>
                    <input type="tel" id="reg-phone" name="phone" required class="form-input"
                        placeholder="+7 (999) 123-45-67"
                        value="<?= e($regOld['phone'] ?? '') ?>">
                </div>

                <!-- Additional fields for "other" organizations -->
                <div id="other-fields" style="display:none;">
                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <p class="form-label">Тип оплаты</p>
                        <div class="payment-toggle">
                            <button type="button" class="payment-toggle__btn is-active" data-payment="organization">
                                Оплата от организации
                            </button>
                            <button type="button" class="payment-toggle__btn" data-payment="individual">
                                Оплата за себя (физлицо)
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label for="reg-inn" class="form-label">
                            ИНН
                            <span style="font-size:0.75rem;font-weight:400;color:var(--color-fg-muted)">(<span id="inn-hint">10 цифр</span>)</span>
                            <span class="required">*</span>
                        </label>
                        <input type="text" id="reg-inn" name="inn" class="form-input font-mono"
                            maxlength="10" placeholder="1234567890"
                            value="<?= e($regOld['inn'] ?? '') ?>">
                    </div>

                    <!-- Partner checkbox -->
                    <div class="form-check" style="margin-bottom:1.25rem;">
                        <input type="checkbox" id="reg-partner" name="want_partner" value="1">
                        <label for="reg-partner">
                            Хотим получить статус и возможности партнёра конференции
                            <span class="partner-tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;cursor:help;color:var(--color-fg-muted)"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                <span class="partner-tooltip__popup">Мастер-класс, профориентационный модуль и т.д.</span>
                            </span>
                            <br>
                            <small style="color:var(--color-fg-muted)">Варианты партнёрства обсуждаются с оператором в отдельном порядке.</small>
                        </label>
                    </div>
                </div>

                <!-- CAPTCHA -->
                <div class="captcha-box">
                    <label for="reg-captcha" class="form-label">Проверка <span class="required">*</span></label>

                    <?php if (!empty($smartCaptchaSiteKey)): ?>
                        <div class="captcha-box__smart" aria-label="Проверка SmartCaptcha">
                            <div
                                id="smartcaptcha-container"
                                class="smart-captcha"
                                data-sitekey="<?= e($smartCaptchaSiteKey) ?>"
                                data-hl="ru"
                                data-callback="onSmartCaptchaSuccess"
                            ></div>
                        </div>
                        <input type="hidden" name="smartcaptcha_token" id="smartcaptcha-token" value="">
                    <?php endif; ?>

                    <?php if ($captchaImgSrc): ?>
                        <img src="<?= $captchaImgSrc ?>" alt="CAPTCHA" class="captcha-box__image" width="200" height="60">
                    <?php else: ?>
                        <p style="margin-top:0.25rem;font-size:0.875rem;color:var(--color-fg-muted);">
                            Решите: <span class="font-mono font-semibold text-primary"><?= e($captchaData['question']) ?></span>
                        </p>
                    <?php endif; ?>
                    <input type="number" id="reg-captcha" name="captcha" required
                        class="form-input" style="width:8rem;margin-top:0.5rem;"
                        placeholder="Ответ">
                    <?php if (!empty($smartCaptchaSiteKey)): ?>
                        <p class="captcha-box__hint">Если виджет SmartCaptcha недоступен, решите пример выше.</p>
                    <?php endif; ?>
                </div>

                <!-- Consent checkboxes -->
                <div class="form-check">
                    <input type="checkbox" id="reg-personal-data" name="agreed_personal_data" value="1">
                    <label for="reg-personal-data">
                        Даю согласие на обработку персональных данных в соответствии с
                        <a href="#">Федеральным законом &#8470; 152-ФЗ</a>.
                        <span class="required">*</span>
                    </label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="reg-offer" name="agreed_offer" value="1">
                    <label for="reg-offer">
                        Принимаю условия <a href="#">договора-оферты</a>.
                        <span class="required">*</span>
                    </label>
                </div>

                <button type="submit" class="btn btn--primary" style="width:100%;margin-top:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Зарегистрироваться
                </button>
            </form>
        <?php endif; ?>
    </div>
</section>
