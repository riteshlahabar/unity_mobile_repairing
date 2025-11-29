<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <link rel="shortcut icon" href="/assets/images/favicon.ico">
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/icons.min.css" rel="stylesheet">
  <link href="/assets/css/app.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-xxl">
    <div class="row vh-100 justify-content-center align-items-center">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body p-0 bg-white rounded-top text-center">
    <img src="/assets/images/logo-sm.png" height="50" alt="logo" class="auth-logo mt-3">
    <h4 class="text-black fs-18 mt-3 mb-1">UNITY MOBILES AND REPAIRING LAB</h4>
    <p class="text-muted mb-0 pb-3">Sign in continue to Portal</p>
</div>
          <div class="card-body pt-4">
            <!-- Laravel login POST route, include CSRF -->
            <form method="POST" action="{{ route('admin.login') }}">
              @csrf
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="email" class="form-control" placeholder="Enter username">
              </div>
              <div class="mb-3">
                <label for="userpassword" class="form-label">Password</label>
                <input type="password" id="userpassword" name="password" class="form-control" placeholder="Enter password">
              </div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                  <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <a href="#" class="text-muted font-13">Forgot password?</a>
              </div>
              <button type="submit" class="btn btn-primary d-block w-100">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Only use the JS file that exists in your structure -->
  <script src="/assets/js/app.js"></script>
</body>
</html>
