 @include('qualita.components.header', [
     'title' => 'Controllo intermedio Sorttex',
     'code' => 'M_IO 10_0',
     'review' => 0,
 ]);

 <br>
 <div class="container text-center">
     <div class="row">
         <div class="col-3">
             <b>LOTTO MP </b> <input class="form-control" type="text" name="lotto">
         </div>
         <div class="col"></div>
         <div class="col-3">
             <b>VARIETA'</b><input class="form-control" type="text" name="varieta">
         </div>
     </div>
 </div>
 <table class="table">
     <thead>
         <tr>
             <th scope="col" style="font-size: 12px">DATA</th>
             <th scope="col" style="font-size: 12px">ORA</th>
             <th scope="col" style="font-size: 12px">CORPI ESTRANEI</th>
             <th scope="col" style="font-size: 12px">RAGGRINZITO/SEMI NERI - SCURI</th>
             <th scope="col" style="font-size: 12px">AVARIATO / CIMICIATO</th>
         </tr>
     </thead>
     <tbody>
         <?php for ($i = 0;
                           $i < 5;
                           $i++){ ?>
         <tr>
             <th scope="row" style="font-size: 12px"><input type="text" class="form-control"></th>
             <th style="font-size: 12px"><input type="datetime-local" class="form-control"
                     name="data_<?php echo $i; ?>" id="data_<?php echo $i; ?>"></th>

             <td style="font-size: 12px"><input type="text" class="form-control"></td>
             <td style="font-size: 12px"><input type="text" class="form-control"></td>
             <td style="font-size: 12px"><input type="text" class="form-control"></td>
         </tr>
         <?php } ?>

 </table>
 <input type="button" id="remove" class="form-control btn-primary" style="background: blue;color:white;"
     value="Stampa" onclick="stampa()">
 </tbody>
 </div>
 </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
 </script>
 </body>

 <script type="text/javascript">
     function stampa() {
         y = document.getElementsByClassName('form-control').length;
         for (i = 0; i < y; i++)
             document.getElementsByClassName('form-control')[i].style = 'border:none';
         document.getElementById('remove').style.display = 'none';
         window.print();
         document.getElementById('remove').style.display = 'block';
         y = document.getElementsByClassName('form-control').length;
         for (i = 0; i < y; i++)
             document.getElementsByClassName('form-control')[i].style = '';
     }
 </script>

 </html>
