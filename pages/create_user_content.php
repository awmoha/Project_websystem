<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0"><?= $title ?></h1>
        <a href="users.php" class="btn btn-secondary">Back</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= (strpos($message, '✅') === 0) ? 'success' : 'danger' ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" action="create_user.php">
        <div class="form-group mb-3">
            <label for="user_name" class="form-label">Username:</label>
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username" required>
        </div>
        <div class="form-group  mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group  mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group mb-3">
            <label for="role_name" class="form-label">Role:</label>
            <select class="form-select" id="role_name" name="role_name" required>
                <option value="" disabled selected>Select Role</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= htmlspecialchars($role) ?>"><?= ucfirst(strtolower($role)) ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>