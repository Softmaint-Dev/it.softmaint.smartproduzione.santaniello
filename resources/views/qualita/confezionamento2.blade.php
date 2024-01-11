 
        @include('qualita.components.header', [
            "title" => "Monitoraggio efficienza X. Ray",
            "code" => "M_IO 17_4",
            "review" => 0,
        ]); 
    <form class="form-control" action="">
    
        <table class="table">
            <tbody>
                <tr>
                    <th style="font-size: 12px" scope="row">FIRMA CQ</th>
                    <td>[SKIN_C_1]</td>
                </tr>
            </tbody>
        </table>



       
            <table class="table table-striped">
                <thead>
                    <td>Linea Pentec MBR-1200</td>
                    <td>1° con. ore________
                        Lotto ____________</td>
                    <td>2° con. ore________
                        Lotto ____________</td>
                    <td>3° con. ore________
                        Lotto ____________</td>
                    <td>4° con. ore________
                        Lotto ____________</td>
                    <td>5° con. ore________
                        Lotto ____________</td>
                    <td>6° con. ore________
                        Lotto ____________</td>
                </thead>
                <tbody>
                
                    <tr>
                        <td>FE 2,5 mm</td>
                        <td>
                            <input type="text" name="name" id="id">
                        </td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                    </tr>
                    <tr>
                        <td>NON FE 2,5 mm</td>

                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                       
                    </tr>
              
                    <tr>
                        <td>STAINLESS 1,8mm</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                    </tr>
                   
                    <tr>
                        <td>Firma addetto CQ</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                    </tr>
                </tbody>
            </table>
        

        <br>

       
            <table class = "table  table-striped">
                <thead>
                    <td>Linea Pentec MD-PMO</td>
                    <td>1° con. ore________
                        Lotto ____________</td>
                    <td>2° con. ore________
                        Lotto ____________</td>
                    <td>3° con. ore________
                        Lotto ____________</td>
                    <td>4° con. ore________
                        Lotto ____________</td>
                    <td>5° con. ore________
                        Lotto ____________</td>
                    <td>6° con. ore________
                        Lotto ____________</td>
                </thead>
                <tbody>
                
                    <tr>
                        <td>FE 3,0 mm</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                    </tr>
                    <tr>
                        <td>NON FE 3,5 mm</td>

                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                       
                    </tr>
              
                    <tr>
                        <td>STAINLESS 4.0 mm</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                    </tr>
                  
                    <tr>
                        <td>Firma addetto CQ</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                        <td>CONFORME</td>
                    </tr>
                </tbody>
            </table>
       

        <br>

    </br>

        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <td>Visto giornaliero per verifica corretta compilazione e ritiro per archiviazione</td>
                      <td></td>
                    </tr>
                </thead>
                
            </table>
        </div>

   

            <div class="container fluid">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Redatto da</td>
                            <td>Verificato</td>
                            <td>Approvato da</td>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Redatto da</td>
                            <td>Verificato</td>
                            <td>Approvato da</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </form>

        <script>
             window.onbeforeprint = function convertiInParagrafo() {
                var inputElement = document.getElementById("id");
                var paragrafoElement = document.createElement("p");
                paragrafoElement.textContent = inputElement.value;
                inputElement.parentNode.replaceChild(paragrafoElement, inputElement);
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</body>

<script></script>
</html>