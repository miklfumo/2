<?php
/**
 * Fixed navigation bar
 */
$navLinks = [
    ['href' => '#about', 'label' => 'О конференции'],
    ['href' => '#goals', 'label' => 'Цели'],
    ['href' => '#speakers', 'label' => 'Спикеры'],
    ['href' => '#schedule', 'label' => 'Программа'],
    ['href' => '#partners', 'label' => 'Партнёры'],
    ['href' => '#gallery', 'label' => 'Галерея'],
    ['href' => '#venue', 'label' => 'Место'],
];
?>
<header class="nav" role="banner">
    <nav class="nav__inner" aria-label="Основная навигация">
        <a href="#" class="nav__logo" aria-label="ФУМО ИБ — Главная">
            <img src="public/images/logos/Logo.png" alt="Логотип ФУМО ИБ" class="nav__logo-image" width="44" height="44">
            <span class="nav__logo-text">ФУМО ИБ</span>
        </a>

        <ul class="nav__links">
            <?php foreach ($navLinks as $link): ?>
                <li><a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a></li>
            <?php endforeach; ?>
        </ul>

        <a href="#registration" class="btn btn--primary nav__cta">Регистрация</a>

        <button type="button" class="nav__toggle" aria-label="Открыть меню" aria-expanded="false">
            <!-- Menu icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <!-- Close icon (hidden by default) -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </nav>

    <div class="nav__mobile">
        <?php foreach ($navLinks as $link): ?>
            <a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a>
        <?php endforeach; ?>
        <a href="#registration" class="nav__mobile-cta">Регистрация</a>
    </div>
</header>
