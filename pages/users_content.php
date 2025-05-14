<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Users</h1>
    <a href="create_user.php" class="btn btn-primary">Add User</a>
  </div>
  <?php if (!empty($message)): ?>
    <div class="alert alert-danger"><?= $message ?></div>
  <?php endif; ?>
  <?php if (!empty($users)): ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th> 
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Edit</th>
            <!-- <th>Remove</th> -->
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user['inc_user_id'] ?></td>
              <td><?= htmlspecialchars($user['user_name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['role_name']) ?></td>
              <td>
                <a href="edit_user.php?id=<?= $user['inc_user_id'] ?>" class="btn btn-warning btn-sm">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
              </td>
              <!-- <td>
                <form method="post" action="users.php" class="d-inline-block">
                  <input type="hidden" name="user_id" value="<?= $user['inc_user_id'] ?>">
                  <button type="submit" name="remove_user" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i> Remove
                  </button>
                </form>
              </td> -->
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="alert alert-info">No users found.</p>
  <?php endif; ?>
</div>
