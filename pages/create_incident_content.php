<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0"><?= $title ?></h1>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>


    <form method="post" action="create_incident.php" enctype="multipart/form-data">
        <div class="form-group mb-3"> 
            <label for="inc_type_id">Incident Type:</label>
            <select class="form-control bg-dark text-white" id="inc_type_id" name="inc_type_id" required>
                <option value="">Select Incident Type</option>
                <?php
                $type_query = "SELECT inc_type_id, type_name FROM incident_type";
                $type_result = $conn->query($type_query);
                if ($type_result->num_rows > 0) {
                    while ($type_row = $type_result->fetch_assoc()) {
                        echo "<option value='" . $type_row['inc_type_id'] . "'>" . $type_row['type_name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3"> 
            <label for="inc_sev_id">Incident Severity:</label>
            <select class="form-control  bg-dark text-white" id="inc_sev_id" name="inc_sev_id" required>
                <option value="">Select Incident Severity</option>
                <?php
                $severity_query = "SELECT inc_sev_id, severity_name FROM incident_severity";
                $severity_result = $conn->query($severity_query);
                if ($severity_result->num_rows > 0) {
                    while ($severity_row = $severity_result->fetch_assoc()) {
                        echo "<option value='" . $severity_row['inc_sev_id'] . "'>" . $severity_row['severity_name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3"> 
            <label for="description">Description:</label>
            <textarea class="form-control  bg-dark text-white" id="description" name="description" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3"> 
            <label for="affected_assets">Affected Assets:</label>
            <select class="form-control  bg-dark text-white" id="affected_assets" name="affected_assets[]" multiple required>
                <option value="">Select Affected Assets</option>
                <?php
                $asset_query = "SELECT asset_id, asset_name FROM asset";
                $asset_result = $conn->query($asset_query);
                if ($asset_result->num_rows > 0) {
                    while ($asset_row = $asset_result->fetch_assoc()) {
                        echo "<option value='" . $asset_row['asset_id'] . "'>" . $asset_row['asset_name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3"> 
            <label for="evidence_files">Evidence Files:</label>
            <input type="file" class="form-control-file" id="evidence_files" name="evidence_files[]" multiple>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Incident</button> <!-- Lägg till margin-top för knappen -->
    </form>
</div>
