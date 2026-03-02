<?php
/**
 * Partners & Organizers section
 */
$shieldIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
$buildingIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 012-2h8a2 2 0 012 2v18z"/><path d="M6 12H4a2 2 0 00-2 2v6a2 2 0 002 2h2"/><path d="M18 9h2a2 2 0 012 2v9a2 2 0 01-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>';
$landmarkIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>';
$gradIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/></svg>';
$lockIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>';
$globeIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>';
$bookIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>';

$groups = [
    [
        'title' => 'Организаторы',
        'items' => [
            ['name' => 'ФУМО ВО ИБ', 'icon' => $shieldIcon],
            ['name' => 'ФУМО СПО ИБ', 'icon' => $shieldIcon],
            ['name' => 'СПК-ИТ', 'icon' => $globeIcon],
        ],
    ],
    [
        'title' => 'Соорганизаторы',
        'items' => [
            ['name' => 'ФСТЭК России', 'icon' => $buildingIcon],
            ['name' => 'Минобрнауки России', 'icon' => $landmarkIcon],
            ['name' => 'РТУ МИРЭА', 'icon' => $gradIcon],
        ],
    ],
    [
        'title' => 'Партнёры',
        'items' => [
            ['name' => 'АНО НТЦ ЦК', 'icon' => $bookIcon],
            ['name' => 'АЗИ', 'icon' => $shieldIcon],
            ['name' => 'ГК «ИнфоТеКС»', 'icon' => $lockIcon],
        ],
    ],
    [
        'title' => 'При участии',
        'items' => [
            ['name' => 'ФСБ России', 'icon' => $shieldIcon],
            ['name' => 'Аппарат СБ России', 'icon' => $buildingIcon],
        ],
    ],
    [
        'title' => 'Оператор',
        'items' => [
            ['name' => 'ООО «Академия "Профи Скиллс"»', 'icon' => $globeIcon],
        ],
    ],
];
?>
<section id="partners" class="section" aria-labelledby="partners-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Партнёры и организаторы</p>
            <h2 id="partners-heading" class="text-balance" style="margin-top:0.75rem;">Организаторы и партнёры</h2>
        </div>

        <?php foreach ($groups as $group): ?>
            <div class="partners__group">
                <h3 class="partners__group-title"><?= e($group['title']) ?></h3>
                <div class="partners__grid">
                    <?php foreach ($group['items'] as $item): ?>
                        <div class="partners__item">
                            <?= $item['icon'] ?>
                            <span class="partners__item-name"><?= e($item['name']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center" style="margin-top:3rem;">
            <a href="#registration" class="btn btn--primary">Стать партнёром</a>
        </div>
    </div>
</section>
