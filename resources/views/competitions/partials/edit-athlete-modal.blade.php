<div class="modal fade" id="editAthleteModal" tabindex="-1" aria-labelledby="editAthleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAthleteModalLabel">Edit Athlete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAthleteForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="athlete_id" id="edit_athlete_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Family Name</label>
                        <input type="text" class="form-control" name="family_name" id="edit_family_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Given Name</label>
                        <input type="text" class="form-control" name="given_name" id="edit_given_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Region</label>
                        <input type="text" class="form-control" name="region" id="edit_region" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
