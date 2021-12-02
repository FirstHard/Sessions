      <div class="alert alert-<?= $heading_class; ?> alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><?= $error['message_heading']; ?></h4>
        <hr>
        <p><?= $error['message_description']; ?></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>