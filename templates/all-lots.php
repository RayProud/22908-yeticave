<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="/all-lots.php?category=<?=$category['id']; ?>"><?=$category['title']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<div class="container">
  <section class="lots">
    <h2>Все лоты в категории <span>«<?=$category_title; ?>»</span></h2>
    <ul class="lots__list">

        <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($lot["image_url"]); ?>" width="350" height="260" alt="<?=htmlspecialchars($lot["title"]); ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$category_title; ?></span>
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

  </section>
</div>
