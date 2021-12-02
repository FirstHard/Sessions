    <header>
      <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="<?= HOME; ?>">Manager dashboard</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <span class="ms-auto navbar-text text-success">Welcome, <?= $user['name']; ?>!</span>
            <ul class="navbar-nav mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="?do=<?= $action_link; ?>"><?= $action_link_text; ?></a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
	  </header>
	  <main>
      <div class="container my-5">
        <div class="row">
        <?php
          ob_start();
          include (ROOT . '/app/views/templates/' . $template . '.php');
          $page_template = ob_get_contents();
          ob_end_flush();
        ?>
        </div>
      </div>
    </main>
    <footer class="bg-dark py-5 mt-5">
      <div class="container">
        <div class="row">
          <div class="col-12 col-lg-2 align-self-center text-center">
            <a href="<?= HOME ?>">
              <img src="<?= HOME ?>/app/views/assets/images/logo.png" alt="Shop logo" height="90">
            </a>
          </div>
          <div class="col-12 col-lg-8">
            <ul class="nav justify-content-center text-light pb-3 mb-3">
              <li class="nav-item"><a href="#" class="nav-link px-2">Home</a></li>
              <li class="nav-item"><a href="#" class="nav-link px-2">Catalog</a></li>
              <li class="nav-item"><a href="#" class="nav-link px-2">Product</a></li>
              <li class="nav-item"><a href="#" class="nav-link px-2">Cart</a></li>
              <li class="nav-item"><a href="?do=login" class="nav-link px-2">Login</a></li>
            </ul>
            <p class="text-center text-muted">&copy; 2021 The Shop Inc. All rights reserved.</p>
            <p class="text-center text-muted">Developed by <a href="https://buinoff.tk" target="_blank">Vladimir Buinoff</a> on the <a href="https://getbootstrap.com" target="_blank" rel="noopener noreferrer">Bootstrap Framework</a> in 2021.</p>
          </div>
          <div class="col-12 col-lg-2 align-self-center text-center">
            <p class="text-muted">Gratitude to:</p>
            <a href="https://www.nixsolutions.com" target="_blank">
              <img src="<?= HOME ?>/app/views/assets/images/nix-logo-new.svg" alt="NIX logo" height="80">
            </a>
          </div>
        </div>
      </div>
    </footer>