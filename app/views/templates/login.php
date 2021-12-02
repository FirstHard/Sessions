      <!-- Login form template -->
      <div class="col-12 col-lg-4 offset-lg-4 my-5">
        <h1 class="text-center">Please log in! *</h1>
        <?= $errors; ?>
        <div class="card">
          <div class="card-body">
            <form action="?do=login" method="POST" autocomplete="off">
              <div class="mb-3">
                <label for="Login" class="form-label">Login or Email address</label>
                <input type="login" name="login" class="form-control" id="Login" required>
                <small>Try logins: admin, manager, user</small>
              </div>
              <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="Password" required>
              </div>
              <button type="submit" name="submit" class="btn btn-primary">login</button>
            </form>
          </div>
        </div>
        <h5>* All fields are required. Only latin characters.</h5>
      </div>
      <!-- End of Login form template -->