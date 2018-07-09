<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ $_ENV['APP_NAME'] }}</title>

<!--         <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous"> -->

        <link href="{{mix('css/app.css')}}" rel="stylesheet" type="text/css">

    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="#">Courses</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Membership</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Sign In</a>
              </li>
            </ul>
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img src="https://lh3.googleusercontent.com/-l55Mc1g0Xbc/AAAAAAAAAAI/AAAAAAAAAAA/xkvNqY95l5o/photo.jpg" width="20" height="20" class="align-middle mr-1" />
                  Anders
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Sign Out</a>
                </div>
              </li>
            </ul>
          </div>
        </nav>

        <div class="container-fluid">
          <div class="row">
            <div class="col-sm">
              <ul class="nav nav-pills my-3">
                <li class="nav-item">
                  <a class="nav-link active" href="#">Upcoming</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">In Progress</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Courses I'm taking</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Courses I'm teaching</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"><span class="oi oi-plus"></span> Create new course</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="card-deck">
                <div class="card bg-light">
                  <div class="card-body">
                    <h5 class="card-title">Cuban salsa intermediate 1</h5>
                    <h6 class="card-subtitle">Intermediate 1</h6>
                    <p class="card-text">Nic &amp; Kenneth</p>
                    <a href="#" class="btn btn-primary">Sign up</a>
                  </div>
                    <div class="card-footer">
                      <small class="text-muted">7 tuesdays at 19:30 beggining April 7</small>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="card-deck">
                <div class="card bg-light">
                  <div class="card-body">
                    <h5 class="card-title">Cuban salsa intermediate 1</h5>
                    <h6 class="card-subtitle">Intermediate 1</h6>
                    <p class="card-text">Nic &amp; Kenneth</p>
                    <span class="text-success">Signed up!</span>
                    <a href="#" class="text-danger">Cancel</a>
                  </div>
                    <div class="card-footer">
                      <small class="text-muted">7 tuesdays at 19:30 beggining April 7</small>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="card-deck">
                <div class="card bg-light">
                  <div class="card-body">
                    <h5 class="card-title">Cuban salsa intermediate 1</h5>
                    <h6 class="card-subtitle">Intermediate 1</h6>
                    <p class="card-text">Nic &amp; Kenneth</p>
                    <a href="#" class="btn btn-primary">Sign up</a>
                  </div>
                    <div class="card-footer">
                      <small class="text-muted">7 tuesdays at 19:30 beggining April 7</small>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="card-deck">
                <div class="card bg-light">
                  <div class="card-body">
                    <h5 class="card-title">Cuban salsa intermediate 1</h5>
                    <h6 class="card-subtitle">Intermediate 1</h6>
                    <p class="card-text">Nic &amp; Kenneth</p>
                    <a href="#" class="btn btn-primary">Sign up</a>
                  </div>
                    <div class="card-footer">
                      <small class="text-muted">7 tuesdays at 19:30 beggining April 7</small>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

<!--         <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script> -->
      <script src="{{mix('js/app.js')}}" ></script>
    </body>
</html>
