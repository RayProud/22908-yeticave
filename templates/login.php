<form class="form container <?php if ($have_errors): ?>form--invalid<?php endif; ?>" action="/login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?php if (isset($found_errors['email'])): ?>form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars($email)?>" required>
        <span class="form__error">
            <?= $found_errors['email'] ?? '' ?>
        </span>
    </div>
    <div class="form__item form__item--last  <?php if (isset($found_errors['password'])): ?>form__item--invalid<?php endif; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" required>
        <span class="form__error">
            <?= $found_errors['password'] ?? '' ?>
        </span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
