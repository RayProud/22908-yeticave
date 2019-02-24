<form class="form form--add-lot container <?php if (count($found_errors) > 0): ?>form--invalid<?php endif; ?>" enctype="multipart/form-data" action="/add.php" method="post"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php if (count($found_errors['lot-name']) > 0): ?>form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" required>
            <span class="form__error">
                <?= implode("; ", $found_errors['lot-name']) ?>
            </span>
        </div>
        <div class="form__item <?php if (count($found_errors['category']) > 0): ?>form__item--invalid<?php endif; ?>">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option disabled>Выберите категорию</option>

                <?php foreach ($categories as $category): ?>
                    <option><?=$category; ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">
                <?= implode("; ", $found_errors['category']) ?>
            </span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php if (count($found_errors['message']) > 0): ?>form__item--invalid<?php endif; ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required></textarea>
        <span class="form__error">
            <?= implode("; ", $found_errors['message']) ?>
        </span>
    </div>
    <div class="form__item form__item--file <?php if (count($found_errors['lot-photo']) > 0): ?>form__item--invalid<?php endif; ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="lot-photo" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error">
            <?= implode("; ", $found_errors['lot-photo']) ?>
        </span>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?php if (count($found_errors['lot-rate']) > 0): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0" required>
            <span class="form__error">
                <?= implode("; ", $found_errors['lot-rate']) ?>
            </span>
        </div>
        <div class="form__item form__item--small <?php if (count($found_errors['lot-step']) > 0): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" required>
            <span class="form__error">
                <?= implode("; ", $found_errors['lot-step']) ?>
            </span>
        </div>
        <div class="form__item <?php if (count($found_errors['lot-date']) > 0): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="lot-date" required>
            <span class="form__error">
                <?= implode("; ", $found_errors['lot-date']) ?>
            </span>
        </div>
    </div>
    <?php if (count($found_errors) > 0): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
