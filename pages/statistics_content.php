<div class="container bg-dark text-light mt-5">
   <h1>Page Visit Tracking</h1>


   <ul class="nav nav-tabs">
       <li class="nav-item">
           <a class="nav-link <?= !isset($_GET['user_id']) ? 'active' : '' ?>" data-bs-toggle="tab" href="#full-log">Full Log</a>
       </li>
       <li class="nav-item">
           <a class="nav-link <?= isset($_GET['user_id']) ? 'active' : '' ?>" data-bs-toggle="tab" href="#user-log">By User</a>
       </li>
       <li class="nav-item">
           <a class="nav-link" data-bs-toggle="tab" href="#summary">Summary</a>
       </li>
   </ul>


   <div class="tab-content">
      
       <div class="tab-pane fade <?= !isset($_GET['user_id']) ? 'show active' : '' ?>" id="full-log">
           <h3>All Visits</h3>
           <?php if ($result->num_rows > 0): ?>
               <table class="table table-bordered table-striped table-dark">
                   <thead>
                       <tr>
                           <th>Time</th>
                           <th>User</th>
                           <th>Page</th>
                           <th>Browser</th>
                           <th>IP Address</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php while ($visit = $result->fetch_assoc()): ?>
                           <tr>
                               <td><?= htmlspecialchars($visit['created_at']) ?></td>
                               <td><?= htmlspecialchars($visit['user_name']) ?></td>
                               <td><?= htmlspecialchars($visit['page_url']) ?></td>
                               <td><?= htmlspecialchars($visit['browser_name']) ?></td>
                               <td><?= htmlspecialchars($visit['ip_address']) ?></td>
                           </tr>
                       <?php endwhile; ?>
                   </tbody>
               </table>


              
               <?php if ($totalPages > 1): ?>
                   <nav>
                       <ul class="pagination justify-content-center mt-3">
                           <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                               <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                           </li>
                           <?php
                           $start = max(1, $page - 2);
                           $end = min($totalPages, $page + 2);
                           for ($i = $start; $i <= $end; $i++): ?>
                               <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                   <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                               </li>
                           <?php endfor; ?>
                           <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                               <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                           </li>
                       </ul>
                   </nav>
               <?php endif; ?>
           <?php else: ?>
               <div class="alert alert-info">No visits found</div>
           <?php endif; ?>
       </div>


     
       <div class="tab-pane fade <?= isset($_GET['user_id']) ? 'show active' : '' ?>" id="user-log">
           <h3>Visits by User</h3>
           <form method="GET" action="statistics.php" class="mb-3">
               <div class="input-group">
                   <select class="form-select" name="user_id" required>
                       <option disabled selected>Select a user</option>
                       <?php foreach ($users as $u): ?>
                           <option value="<?= $u['inc_user_id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $u['inc_user_id']) ? 'selected' : '' ?>>
                               <?= htmlspecialchars($u['user_name']) ?>
                           </option>
                       <?php endforeach; ?>
                   </select>
                   <button type="submit" class="btn btn-primary">View Visits</button>
               </div>
           </form>


           <?php if (isset($userVisits)): ?>
               <?php if ($userVisits->num_rows > 0): ?>
                   <table class="table table-striped table-dark">
                       <thead>
                           <tr>
                               <th>Time</th>
                               <th>Username</th>
                               <th>IP Address</th>
                               <th>Browser</th>
                               <th>URL</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php while ($uVisit = $userVisits->fetch_assoc()): ?>
                               <tr>
                                   <td><?= htmlspecialchars($uVisit['created_at']) ?></td>
                                   <td><?= htmlspecialchars($uVisit['user_name']) ?></td>
                                   <td><?= htmlspecialchars($uVisit['ip_address']) ?></td>
                                   <td><?= htmlspecialchars($uVisit['browser_name']) ?></td>
                                   <td><?= htmlspecialchars($uVisit['page_url']) ?></td>
                               </tr>
                           <?php endwhile; ?>
                       </tbody>
                   </table>


                 
                   <?php if ($totalUserPages > 1): ?>
                       <nav>
                           <ul class="pagination justify-content-center mt-3">
                               <li class="page-item <?= $userPage <= 1 ? 'disabled' : '' ?>">
                                   <a class="page-link" href="?user_id=<?= $selectedUserId ?>&user_page=<?= $userPage - 1 ?>">Previous</a>
                               </li>


                               <?php
                               $start = max(1, $userPage - 2);
                               $end = min($totalUserPages, $userPage + 2);
                               for ($i = $start; $i <= $end; $i++): ?>
                                   <li class="page-item <?= $i == $userPage ? 'active' : '' ?>">
                                       <a class="page-link" href="?user_id=<?= $selectedUserId ?>&user_page=<?= $i ?>"><?= $i ?></a>
                                   </li>
                               <?php endfor; ?>


                               <li class="page-item <?= $userPage >= $totalUserPages ? 'disabled' : '' ?>">
                                   <a class="page-link" href="?user_id=<?= $selectedUserId ?>&user_page=<?= $userPage + 1 ?>">Next</a>
                               </li>
                           </ul>
                       </nav>
                   <?php endif; ?>
               <?php else: ?>
                   <div class="alert alert-info">No visits found for this user.</div>
               <?php endif; ?>
           <?php endif; ?>
       </div>


      
       <div class="tab-pane fade" id="summary">
           <h3>Summary of Page Visits</h3>
           <?php if ($summaryResult->num_rows > 0): ?>
               <table class="table table-striped table-dark">
                   <thead>
                       <tr>
                           <th>Page</th>
                           <th>Total Visits</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php while ($summary = $summaryResult->fetch_assoc()): ?>
                           <tr>
                               <td><?= htmlspecialchars($summary['page_url']) ?></td>
                               <td><?= htmlspecialchars($summary['total_visits']) ?></td>
                           </tr>
                       <?php endwhile; ?>
                   </tbody>
               </table>
           <?php else: ?>
               <div class="alert alert-info">No summary data available</div>
           <?php endif; ?>
       </div>
   </div>
</div>
