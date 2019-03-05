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
            <?php if(isset($_SESSION['user']) && get_time_till_date($lot["end_at"]) !== null): ?>
                <div class="lot-item__state">
                    <?php if (get_time_till_date($lot["end_at"]) !== null): ?>
                        <div class="lot-item__timer timer">
                            <?=get_time_till_date($lot["end_at"]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="lot-item__cost-state">
                      <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=format_price($price); ?></span>
                      </div>
                      <div class="lot-item__min-cost">
                            Мин. ставка <span><?=format_price($lot["bet_step"]); ?></span>
                      </div>
                    </div>
                    <form class="lot-item__form" action="/lot.php?lot=<?=$lot["id"]; ?>" method="post">
                        <p class="lot-item__form-item form__item <?php if (isset($found_errors['cost'])): ?>form__item--invalid<?php endif; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?=$price + $lot["bet_step"]; ?>">
                            <span class="form__error">
                                <?= $found_errors['cost'] ?? '' ?>
                            </span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <?php if (count($bets) > 0): ?>
                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?=htmlspecialchars($bet['name']); ?></td>
                                <td class="history__price"><?=format_price($bet['amount']); ?></td>
                                <td class="history__time"><?=get_human_time_from_now($bet['created_at']) . 'назад'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
      </div>
    </section>
