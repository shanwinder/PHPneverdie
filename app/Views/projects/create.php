<div class="d-flex flex-wrap justify-content-between gap-2 mb-4">
  <h1 class="h3 mb-0">Submit Project</h1>
  <a class="btn btn-outline-secondary" href="<?= e(url('/projects')) ?>">Back</a>
</div>
<form class="card lesson-card" method="post" action="<?= e(url('/projects')) ?>">
  <?= csrf_field() ?>
  <div class="card-body row g-3">
    <div class="col-md-8">
      <label class="form-label">Title</label>
      <input class="form-control" name="title" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Type</label>
      <select class="form-select" name="submission_type"><option value="github_url">GitHub URL</option><option value="text_note">Text Note</option></select>
    </div>
    <div class="col-12">
      <label class="form-label">GitHub URL</label>
      <input class="form-control" name="github_url" placeholder="https://github.com/user/repo">
    </div>
    <div class="col-12">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="4"></textarea>
    </div>
    <div class="col-12">
      <label class="form-label">Note</label>
      <textarea class="form-control" name="note" rows="4"></textarea>
    </div>
  </div>
  <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Submit</button></div>
</form>

