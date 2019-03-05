<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?=$category; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <?php if (count($lots) > 0): ?>
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=htmlspecialchars($lot["image_url"]); ?>" width="350" height="260" alt="<?=htmlspecialchars($lot["title"]); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lot["category_title"]; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?lot=<?=$lot["id"]; ?>"><?=htmlspecialchars($lot["title"]); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=format_price($lot["start_price"]); ?></span>
                            </div>
                            <div class="lot__timer timer"><?='через ' . get_human_time_from_now($lot['end_at']); ?></div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
