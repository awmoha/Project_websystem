<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['edit_user_data'])) {
    $user = $_SESSION['edit_user_data']; 
} else {
   
    echo "Error: User data not found!";
    exit();
}
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0"><?= $title ?></h1>
        <a href="users.php" class="btn btn-secondary"> Back</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= (strpos($message, '✅') === 0) ? 'success' : 'danger' ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" action="edit_user.php">
        <input type="hidden" name="user_id" value="<?= $user['inc_user_id'] ?>">
        <div class="mb-3">
            <label for="user_name" class="form-label">Username:</label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="role_name" class="form-label">Role:</label>
            <select class="form-select" id="role_name" name="role_name" required>
                <option value="" disabled <?= (!isset($user['role_name'])) ? 'selected' : '' ?>>Select Role</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role ?>" <?= (isset($user['role_name']) && $user['role_name'] === $role) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role) ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (optional):</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
        </div>

        <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

