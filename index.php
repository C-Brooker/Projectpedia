<?php include 'inc/header.php'; ?>

<h2 class="mb-5">Projects</h2>

<div class="container" id="filter-container">
  <div class="container-fluid mb-3">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-8">
        <input class="form-control" type="search" name="search" id="search" placeholder="Search a project">
      </div>
      <div class="col-md-4">
        <input class="form-control" type="date" name="date" id="date">
      </div>
    </div>
    <div class="form-check form-check-inline d-flex justify-content-center mt-1 gap-5">
      <input class="form-check-input" type="checkbox" name="isOwned" id="isOwned" onclick="isOwned()">
      <label class="form-check-label ml-2" for="isOwned">Owned</label>
    </div>
  </div>
</div>

<div class="container mh-250" id="projects-container">
  <div class="row justify-content-center" id="project-row">
    <!--Javascript displaySearch function code inserted here -->
  </div>
</div>


<div class="overlay" id="overlay-container" onclick="overlayToggle(event)" hidden>
  <div class="container mt-3">
    <div class="row justify-content-center align-items-center h-100">
      <div class="card col-md-7 custom-height">
        <div class="card-body d-flex flex-column align-items-center py-5 px-3 text-center">
          <h3 id="title">Title</h3>
          <h6 id="phase" class="text-muted mt-1">Phase</h6>
          <p id="description" class="mb-5 mt-4 f-size">Description</p>
          <div class="row mb-5 w-100">
            <div class="col">
              <p class="card-text text-center m-0"><?php echo "Start Date:"; ?></p>
              <p id="start-date" class="card-text text-center">??/??/????</p>
            </div>
            <div class="col">
              <p class="card-text text-center m-0"><?php echo "End Date:" ?></p>
              <p id="end-date" class="card-text text-center">??/??/????</p>
            </div>
          </div>
          <h6 id="user" class="text-muted mb-1">Created by username</h6>
          <h6 id="user-email" class="text-muted small fst-italic fw-lighter">Email</h6>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include 'inc/footer.php'; ?>