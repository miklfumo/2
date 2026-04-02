<?php
/**
 * Partners & Organizers section
 */
$groups = [
    [
        'title' => 'Организаторы',
        'items' => [
            ['name' => 'ФУМО ВО ИБ', 'logo' => 'public/images/logos/Logo.png'],
            ['name' => 'ФУМО СПО ИБ', 'logo' => 'public/images/logos/Logo.png'],
            ['name' => 'СПК-ИТ', 'logo' => 'public/images/logos/spkit.png'],
        ],
    ],
    [
        'title' => 'Соорганизаторы',
        'items' => [
            ['name' => 'ФСТЭК России', 'logo' => 'public/images/logos/fstek.png'],
            ['name' => 'Минобрнауки', 'logo' => 'public/images/minobr.png'],
            ['name' => 'РТУ МИРЭА', 'logo' => 'public/images/logos/MIREA_Gerb_Colour.png'],
        ],
    ],
    [
        'title' => 'Партнёры',
        'items' => [
            ['name' => 'АНО НТЦ ЦК', 'logo' => 'public/images/logos/NTCCK.png'],
            ['name' => 'АЗИ', 'logo' => 'public/images/logos/AZI.png'],
            ['name' => 'ГК «ИнфоТеКС»', 'logo' => 'public/images/logos/infotecs.png'],
        ],
    ],
    [
        'title' => 'При участии',
        'items' => [
            ['name' => 'ФСБ России', 'logo' => 'public/images/logos/FSB.png'],
            ['name' => 'Аппарат СБ России', 'logo' => 'public/images/logos/SBRF.png'],
        ],
    ],
    [
        'title' => 'Оператор',
        'items' => [
            ['name' => 'ООО «Академия "Профи Скиллс"»', 'logo' => 'public/images/logos/Profiskills.png'],
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
                            <img src="<?= e($item['logo']) ?>" alt="<?= e($item['name']) ?>" class="partners__logo" loading="lazy" decoding="async">
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
