<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
      <!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->


      <title><?php echo isset($titolo) ? $titolo : 'Arca Industry' ?></title>
    <link rel="icon" href="<?php echo URL::asset('img/ico.png') ?>">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->

   
 </head>

<body>
    <div class="container-fluid">
        <table class="table">
            <div class="container text-center">
                <div class="row">
                    <div class="col">
                        <img width="150px" src="{{ asset('img/santaniello.png') }}" class="img-fluid" alt="logo"></td>
                    </div>
                    <div class="col-6">
                        <b>Sistema di Gestione Integrato</b>
                        <p>{{$title}} </p>
                    </div>
                    <div class="col">
                        <b>{{$code}}</b>
                        <p>Rev {{$review}} del {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
    </div>