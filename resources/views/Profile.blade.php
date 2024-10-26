<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mycsoft - Profile</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('sidebarprincipal')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('upperbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                <div class="container mt-4 mb-4 p-3 d-flex justify-content-center">
                     <div class="card p-4">
                         <div class=" image d-flex flex-column justify-content-center align-items-center">
                             <button class="btn btn-secondary">
                                 <img class="img-profile rounded-circle" src="img/undraw_profile.svg" height="100" width="100" />
                            </button>
                             <span class="name mt-3">Eleanor Pena</span>
                              <span class="idd">@eleanorpena</span> 
                    <div class="d-flex flex-row justify-content-center align-items-center gap-2"> 
                        <span class="idd1">Oxc4c16a645_b21a</span>
                         <span> <i class="fa fa-copy"></i></span> 
                        </div> 
                        <div class="d-flex flex-row justify-content-center align-items-center mt-3">
                             <span class="number">1069 <span class="follow">Followers</span></span>
                             </div> 
                             <div class=" d-flex mt-2">
                                 <button class="btn1 btn-dark">Edit Profile</button> </div>
                                  <div class="text mt-3"> 
                                    <span>Eleanor Pena is a creator of minimalistic x bold graphics and digital artwork.<br><br> Artist/ Creative Director by Day #NFT minting@ with FND night. </span> 
                                </div>
                                 <div class="gap-3 mt-3 icons d-flex flex-row justify-content-center align-items-center">
                                     <span><i class="fa fa-twitter"></i></span>
                                 <span><i class="fa fa-facebook-f"></i></span> 
                                 <span><i class="fa fa-instagram"></i></span>
                                  <span><i class="fa fa-linkedin"></i></span> 
                                </div> 
                                <div class=" px-2 rounded mt-4 date "> 
                                    <span class="join">Joined May,2021</span>
                                 </div> 
                                </div> 
                            </div>
                        </div>
                </div>
                <!-- End of Main Content -->
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Mycsoft 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/tablesview.js') }}"></script>
    <script src="{{ asset('js/selects.js') }}"></script>
    <script src="{{ asset('js/clearFormInputs.js') }}"></script>
    <script src="{{ asset('js/mensajes.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

</script>

</body>

</html>