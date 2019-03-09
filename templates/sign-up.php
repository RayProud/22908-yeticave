<form class="form container <?php if ($have_errors): ?>form--invalid<?php endif; ?>" enctype="multipart/form-data" action="/sign-up.php" method="post"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if (isset($found_errors['email'])): ?>form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars($email)?>" required>
        <span class="form__error">
            <?= $found_errors['email'] ?? '' ?>
        </span>
    </div>
    <div class="form__item <?php if (isset($found_errors['password'])): ?>form__item--invalid<?php endif; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" required>
        <span class="form__error">
            <?= $found_errors['password'] ?? '' ?>
        </span>
    </div>
    <div class="form__item <?php if (isset($found_errors['name'])): ?>form__item--invalid<?php endif; ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=htmlspecialchars($user_name)?>" required>
        <span class="form__error">
            <?= $found_errors['name'] ?? '' ?>
        </span>
    </div>
    <div class="form__item <?php if (isset($found_errors['contacts'])): ?>form__item--invalid<?php endif; ?>">
        <label for="contacts">Контактные данные*</label>
        <textarea id="contacts" name="contacts" placeholder="Напишите как с вами связаться" required><?=htmlspecialchars($contacts)?></textarea>
        <span class="form__error">
            <?= $found_errors['contacts'] ?? '' ?>
        </span>
    </div>
    <div class="form__item form__item--file form__item--last <?php if (isset($found_errors['avatar'])): ?>form__item--invalid<?php endif; ?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="" name="avatar">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error">
            <?= $found_errors['avatar'] ?? '' ?>
        </span>
    </div>
    <?php if ($have_errors): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>
