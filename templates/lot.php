<section class="lot-item container">
      <h2><?=htmlspecialchars($lot["title"]); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=htmlspecialchars($lot["image_url"]); ?>" width="730" height="548" alt="<?=htmlspecialchars($lot["title"]); ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot["category_title"]; ?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($lot["description"]); ?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <?php if (get_time_till_date($lot["end_at"]) !== null): ?>
            <div class="lot-item__timer timer">
                <?=get_time_till_date($lot["end_at"]); ?>
            </div>
            <?php endif; ?>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=format_price($lot["start_price"]); ?></span>
              </div>
              <div class="lot-item__min-cost">
                    Мин. ставка <span><?=format_price($lot["bet_step"]); ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
