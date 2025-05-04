<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.register') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-5"><?= lang('Auth.register') ?></h5>

            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= esc($error) ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email', $email ?? '') ?>" required onblur="suggestUsernames()">
                    <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                </div>

                <!-- Username -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                    <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
                </div>

                <!-- Username suggestions container -->
                <div id="username-suggestions" class="mb-3" style="display: none;">
                    <label class="form-label">Username Suggestions:</label>
                    <div class="suggestions-list d-flex flex-wrap gap-2">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required>
                    <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                </div>

                <!-- Password (Again) -->
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                    <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                </div>

                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                </div>

                <p class="text-center"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.login') ?></a></p>
            </form>
        </div>
    </div>
</div>

<script>
    function suggestUsernames() {
        const email = document.getElementById('floatingEmailInput').value;
        if (!email) return;

        fetch('<?= site_url('register/suggest-usernames') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `email=${encodeURIComponent(email)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.suggestions.length > 0) {
                    const suggestionsContainer = document.getElementById('username-suggestions');
                    const suggestionsList = suggestionsContainer.querySelector('.suggestions-list');

                    // Clear previous suggestions
                    suggestionsList.innerHTML = '';

                    // Add new suggestions
                    data.suggestions.forEach(suggestion => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-outline-primary btn-sm mb-1 me-1';
                        btn.textContent = suggestion;
                        btn.onclick = function() {
                            selectUsername(suggestion);
                        };
                        suggestionsList.appendChild(btn);
                    });

                    // Show suggestions
                    suggestionsContainer.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function selectUsername(username) {
        document.getElementById('floatingUsernameInput').value = username;
        // Hide suggestions after selection
        document.getElementById('username-suggestions').style.display = 'none';
    }

    // Auto-suggest when page loads if email is present
    document.addEventListener('DOMContentLoaded', function() {
        const email = document.getElementById('floatingEmailInput').value;
        if (email) {
            suggestUsernames();
        }
    });
</script>

<?= $this->endSection() ?>