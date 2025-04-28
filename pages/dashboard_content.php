<div class="container mt-5">
  <h2 class="mb-4">Dashboard Overview</h2>
  <div class="row mb-4 text-center" id="sortable-dashboard">
    <div class="col-md-4 mb-3" id="card1">
      <div class="card shadow-sm bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">Total Users</h5>
          <h3><?= $dashboardData['totals']['users'] ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3" id="card2">
      <div class="card shadow-sm bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Total Incidents</h5>
          <h3><?= $dashboardData['totals']['incidents'] ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3" id="card3">
      <div class="card shadow-sm bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">Total Assets</h5>
          <h3><?= $dashboardData['totals']['assets'] ?></h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Severity Table -->
  <div class="row mb-4" id="sortable-tables">
    <div class="col-md-6 mb-4" id="severityTable">
      <div class="card shadow-sm bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title text-center">Incidents by Severity</h5>
          <div class="table-responsive">
            <table class="table table-dark table-striped table-hover mb-0">
              <thead>
                <tr>
                  <th>Severity Level</th>
                  <th>Count</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dashboardData['severity_counts'] as $severityCount): ?>
                  <tr>
                    <td><?= htmlspecialchars($severityCount['severity_name']); ?></td>
                    <td><?= htmlspecialchars($severityCount['count_by_severity']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="card shadow-sm bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title">Incidents by Type</h5>
          <div class="table-responsive">
            <table class="table table-dark table-striped table-hover mb-0">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Count</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dashboardData['type_counts'] as $typeCount): ?>
                  <tr>
                    <td><?= htmlspecialchars($typeCount['type_name']); ?></td>
                    <td><?= htmlspecialchars($typeCount['count_by_type']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row mb-4" id="sortable-charts">
    <div class="col-md-6 mb-3" id="statusTypeBarChartWrapper">
      <div class="card shadow-sm bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title">Incidents by Status</h5>
          <canvas id="statusTypeBarChart" class="w-100"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-3" id="severityPieChartWrapper">
      <div class="card shadow-sm bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title">Incidents per Month</h5>
          <canvas id="incidentBarChart" class="w-100"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3" id="statusTypePieChartWrapper">
      <div class="card shadow-sm bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title">Incidents by Type</h5>
          <canvas id="incidentTypeChart" class="w-100"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3" id="severityPieChartWrapper">
      <div class="card shadow-sm bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title">Incidents by Severity</h5>
          <div id="severityPieChart" class="w-100"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
  // Incident per month (Bar)
  const incBarLabels = <?= json_encode(array_reverse($dashboardData['incidents_per_month']['labels'])) ?>;
  const incBarData = <?= json_encode(array_reverse($dashboardData['incidents_per_month']['data'])) ?>;

  new Chart(document.getElementById('incidentBarChart'), {
    type: 'doughnut',
    data: {
      labels: incBarLabels,
      datasets: [{
        label: 'Incidents per Month',
        data: incBarData,
        backgroundColor: 'rgba(75,192,192,0.6)',
        borderColor: 'rgba(75,192,192,1)',
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Incident by type (Pie)
  new Chart(document.getElementById('incidentTypeChart'), {
    type: 'pie',
    data: {
      labels: <?= json_encode($dashboardData['incident_types']['labels']) ?>,
      datasets: [{
        label: 'Incidents by Type',
        data: <?= json_encode($dashboardData['incident_types']['data']) ?>,
        backgroundColor: [
          '#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#2ecc71'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
</script>

<!-- Incidents by Severity (Pixel Chart) -->
<script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawSeverityPieChart);

  function drawSeverityPieChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Severity');
    data.addColumn('number', 'Count');
    var severityData = <?= json_encode($dashboardData['severity_counts']); ?>;
    severityData.forEach(function(row) {
      data.addRow([row.severity_name, parseInt(row.count_by_severity)]);
    });

    var options = {
      title: 'Distribution of Incidents by Severity',
      is3D: true,
      backgroundColor: '#343a40',
      legendTextStyle: {
        color: '#ffffff'
      },
      titleTextStyle: {
        color: '#ffffff'
      },
      pieSliceTextStyle: {
        color: '#000000'
      }
    };

    var chart = new google.visualization.PieChart(document.getElementById('severityPieChart'));
    chart.draw(data, options);
  }
</script>

<script>
  const statusLabels = <?= json_encode(array_column($dashboardData['status_counts'], 'status_type')) ?>;
  const statusData = <?= json_encode(array_column($dashboardData['status_counts'], 'count_by_status')) ?>;

  // Definiera bakgrundsfärger för varje status
  const backgroundColors = {
    'OPEN': 'rgba(255, 99, 132, 0.6)', // Röd för "OPEN"
    'WORK IN PROGRESS': 'rgba(255, 159, 64, 0.6)', // Orange för "WORK IN PROGRESS"
    'SOLVED': 'rgba(75, 192, 192, 0.6)' // Grön för "SOLVED"
  };

  // Skapa en array för bakgrundsfärger baserat på status
  const statusBackgroundColors = statusLabels.map(status => backgroundColors[status]);

  new Chart(document.getElementById('statusTypeBarChart'), {
    type: 'bar',
    data: {
      labels: statusLabels,
      datasets: [{
        label: 'Incidents by Status',
        data: statusData,
        backgroundColor: statusBackgroundColors, // Använd de dynamiska bakgrundsfärgerna
        borderColor: 'rgba(255,159,64,1)',
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>



<script>
  // Enable sorting for the cards, tables, and charts
  document.addEventListener('DOMContentLoaded', function() {
    // Sort the main dashboard cards
    const sortableDashboard = new Sortable(document.getElementById('sortable-dashboard'), {
      handle: '.card',
      animation: 150,
      onEnd: function(evt) {
        const order = Array.from(document.querySelectorAll('#sortable-dashboard .col-md-4'))
          .map(card => card.id);
        localStorage.setItem('dashboardOrder', JSON.stringify(order));
      }
    });

    // Sort the tables section
    const sortableTables = new Sortable(document.getElementById('sortable-tables'), {
      handle: '.card',
      animation: 150,
      onEnd: function(evt) {
        const order = Array.from(document.querySelectorAll('#sortable-tables .col-12'))
          .map(card => card.id);
        localStorage.setItem('tableOrder', JSON.stringify(order));
      }
    });

    // Sort the charts section
    const sortableCharts = new Sortable(document.getElementById('sortable-charts'), {
      handle: '.card',
      animation: 150,
      onEnd: function(evt) {
        const order = Array.from(document.querySelectorAll('#sortable-charts .col-md-6'))
          .map(card => card.id);
        localStorage.setItem('chartOrder', JSON.stringify(order));
      }
    });

    // Restore saved orders from localStorage
    const restoreOrder = (sectionId, storageKey) => {
      const savedOrder = JSON.parse(localStorage.getItem(storageKey));
      if (savedOrder) {
        const container = document.getElementById(sectionId);
        savedOrder.forEach(id => {
          const card = document.getElementById(id);
          container.appendChild(card);
        });
      }
    };

    restoreOrder('sortable-dashboard', 'dashboardOrder');
    restoreOrder('sortable-tables', 'tableOrder');
    restoreOrder('sortable-charts', 'chartOrder');
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>