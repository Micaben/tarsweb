<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Mycsoft - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Inicio de sesion</h5>
            <form action="{{ route('login.custom') }}" id="loginform"method="post">
              @csrf
              <div class="form-floating mb-3">
                <label class="my-0" for="usuario">Usuario:</label>
                <input type="text" class="form-control" required id="usuarioinput" name="usuario" placeholder="juanperez">
              </div>
              <div class="form-floating mb-3">
                <label class="my-0" for="password">Password:</label>
                <input type="password" class="form-control" required id="passwordinput" name="password" placeholder="Password">
              </div>
              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold w-100" type="submit"> Entrar</button>
              </div>
              @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif
            </form>
            <hr>
            <div class="text-center">
              <a class="small" href="{{ route('forgot-pass') }}">Forgot Password?</a>
            </div>
            <div class="text-center">
              <a class="small"  href="{{ route('register') }}">Create an Account!</a>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="{{ asset('js/utilidades.js') }}"></script>
    <script>

window.onload = function() {
  document.getElementById("usuarioinput").focus();
};

document.addEventListener('DOMContentLoaded', function() {
        enableEnterNavigationForForms(); // Activar la funcionalidad al cargar la página
    });

    $(document).ready(function () {
        $('#registrationForm').submit(function (event) {
            event.preventDefault();
          
            $.ajax({
                url: '/register',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showSuccessMessage(response.message);
                    } else {
                        let errorMessages = formatErrorMessages(response.errors);
                        showErrorMessage(errorMessages);
                    }
                },
                error: function () {
                    showErrorMessage('Error de conexión !!');
                }
            });
        });
    });
    </script>
</body>

</html>