<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.11/fabric.js"></script>
<script src="<?php echo URL::asset('backend/plugins/jquery/jquery.min.js') ?>"></script>


<div style="width:60%;float:left;">

    <?php

    if (sizeof(explode('x', $etichetta->grandezza)) > 1) {
        list($base, $altezza) = explode('x', $etichetta->grandezza);
        ?>
    <canvas id="c" width="<?php  echo $base*4 ?>" height="<?php echo $altezza*4 ?>"
            style="border:1px solid #ccc"></canvas> <?php
                                                    } else {

                                                        ?>
    <canvas id="c" width="840" height="1180" style="border:1px solid #ccc"></canvas>
        <?php
    }

    ?>


</div>

<div style="width:40%;float:left;">

    <h2><?php echo $etichetta->codice; ?></h2>
    <button onclick="Addtext('testo')">Aggiungi Campo</button>
    <input type="text" id="cd_cf" list="cliente" class="form-control" placeholder="Inserire il codice cliente..."
           autocomplete="off">
    <datalist id="cliente">
        <?php foreach ($clienti as $c) { ?>
        <option value="<?php echo $c->Cd_CF ?>"><?php echo $c->Cd_CF . ' - ' . $c->Descrizione; ?></option>
        <?php } ?>
    </datalist>
    <button onclick="AddImage()">Aggiungi Immagine</button>
    <button onclick="AddRectangle()">Aggiungi Rettangolo</button>


    <a onclick="Save()">
        <button>Salva</button>
    </a>


    <div id="textControls" hidden>
        <div id="text-wrapper" style="margin-top: 10px" ng-show="getText()">
            <div id="text-controls">
                <select id="text-align">
                    <option value="left">L</option>
                    <option value="center">C</option>
                    <option value="right">R</option>
                    <option value="justify">J</option>
                </select>

                <input type="number" min="6" max="120" step="1" id="text-font-size" placeholder="Font Size">

                <button id="font-weight" style="font-weight: bold;">B</button>
                <button id="font-weight" onclick="clona()">Duplica</button>

                <button onclick="sendSelectedObjectToFront()">Porta Sopra</button>
                <button onclick="sendSelectedObjectBack()">Porta Sotto</button>

                <button onClick="deleteObject()">Elimina</button>

            </div>

        </div>

    </div>

    <br><br>


    <!-- <input type="text" id="id_prblattivita" name="id_prblattivita" placeholder="Id Bolla"> -->
    <input type="text" id="param1" name="param1" placeholder="Param 1"
           onkeydown="localStorage.setItem('param1',$(this).val())"
           onchange="localStorage.setItem('param1',$(this).val())">

    <button onclick="test_etichetta()">Test</button>

    <br><br>
    <br><br>


    <button onclick="Addtext('{cd_ar}')">Codice Articolo</button>
    <button onclick="Addtext('{codicearticolo}')">Codice Articolo oppure Alternativo</button>
    <button onclick="Addtext('{descrizione_ar}')">Descrizione Articolo</button>
    <button onclick="Addtext('{descrizione_collo}')">Descrizione Collo</button>
    <button onclick="Addtext('{nr_collo}')">Nr Collo</button>
    <button onclick="Addtext('{scadenza_lotto}')">scadenza_lotto</button>
    <button onclick="Addtext('{lotto}')">Lotto</button>
    <button onclick="Addtext('{qtaprodotta}')">Qta Prodotta</button>
    <button onclick="Addtext('{cd_armisura}')">Cd ARMisura</button>
    <button onclick="Addtext('{descrizione_cf}')">Descrizione Cliente</button>
    <button onclick="Addtext('{idordinelavoro}')">ID ODL</button>
    <button onclick="Addtext('{nr_pedana}')">Nr Pedana</button>
    <button onclick="Addtext('{attivita_next}')">Prossima Attività</button>
    <button onclick="Addtext('{cd_prattivita}')">Codice Attività</button>
    <button onclick="Addtext('{cd_operatore}')">Codice Operatore</button>
    <button onclick="Addtext('{timeins}')">Timestamp Inserimento</button>
    <button onclick="Addtext('{cd_prrisorsa}')">Codice Risorsa</button>
    <button onclick="Addtext('{cd_do}')">CD_DO</button>
    <button onclick="Addtext('{numerodoc}')">Numero Documento</button>
    <button onclick="Addtext('{lotto}')">Lotto</button>
    <button onclick="Addtext('{numerodocrif}')">Numero Doc. Rif</button>
    <button onclick="Addtext('{numerocolli}')">Numero Colli</button>
    <button onclick="Addtext('{pesonetto}')">Peso Netto</button>
    <button onclick="Addtext('{pesonettissimo}')">Peso Nettissimo</button>
    <button onclick="Addtext('{colli}')">Colli</button>
    <button onclick="Addtext('{id_prblattivita}')">Id Bolla</button>
    <button onclick="Addtext('{indirizzo}')">Indirizzo</button>
    <button onclick="Addtext('{Anno}')">Anno</button>
    <button onclick="Addtext('{Origine}')">Origine</button>
    <button onclick="Addtext('{localita}')">Localita</button>
    <button onclick="Addtext('{cd_provincia}')">Provincia</button>
    <button onclick="Addtext('{xLotto}')">xLotto</button>
</div>


<script type="text/javascript">

    const STEP = 1;

    // Add image from local
    var canvas = new fabric.Canvas('c');
    // display/hide text controls

    var Direction = {
        LEFT: 0,
        UP: 1,
        RIGHT: 2,
        DOWN: 3
    };

    fabric.util.addListener(document.body, 'keydown', function (options) {
        if (options.repeat) {
            return;
        }
        var key = options.which || options.keyCode; // key detection
        if (key === 37) { // handle Left key
            moveSelected(Direction.LEFT);
        } else if (key === 38) { // handle Up key
            moveSelected(Direction.UP);
        } else if (key === 39) { // handle Right key
            moveSelected(Direction.RIGHT);
        } else if (key === 40) { // handle Down key
            moveSelected(Direction.DOWN);
        }
    });

    function clona() {
        canvas.getActiveObject().clone(function (cloned) {
            _clipboard = cloned;
        });

        _clipboard.clone(function (clonedObj) {
            canvas.discardActiveObject();
            clonedObj.set({
                left: clonedObj.left + 10,
                top: clonedObj.top + 10,
                evented: true,
            });
            if (clonedObj.type === 'activeSelection') {
                // active selection needs a reference to the canvas.
                clonedObj.canvas = canvas;
                clonedObj.forEachObject(function (obj) {
                    canvas.add(obj);
                });
                // this should solve the unselectability
                clonedObj.setCoords();
            } else {
                canvas.add(clonedObj);
            }
            _clipboard.top += 10;
            _clipboard.left += 10;
            canvas.setActiveObject(clonedObj);
            canvas.requestRenderAll();
        });
    }

    function moveSelected(direction) {

        var activeObject = canvas.getActiveObject();
        var activeGroup = canvas.getActiveGroup();

        if (activeObject) {
            switch (direction) {
                case Direction.LEFT:
                    activeObject.setLeft(activeObject.getLeft() - STEP);
                    break;
                case Direction.UP:
                    activeObject.setTop(activeObject.getTop() - STEP);
                    break;
                case Direction.RIGHT:
                    activeObject.setLeft(activeObject.getLeft() + STEP);
                    break;
                case Direction.DOWN:
                    activeObject.setTop(activeObject.getTop() + STEP);
                    break;
            }
            activeObject.setCoords();
            canvas.renderAll();
            console.log('selected objects was moved');
        } else if (activeGroup) {
            switch (direction) {
                case Direction.LEFT:
                    activeGroup.setLeft(activeGroup.getLeft() - STEP);
                    break;
                case Direction.UP:
                    activeGroup.setTop(activeGroup.getTop() - STEP);
                    break;
                case Direction.RIGHT:
                    activeGroup.setLeft(activeGroup.getLeft() + STEP);
                    break;
                case Direction.DOWN:
                    activeGroup.setTop(activeGroup.getTop() + STEP);
                    break;
            }
            activeGroup.setCoords();
            canvas.renderAll();
            console.log('selected group was moved');
        } else {
            console.log('no object selected');
        }

    }

    canvas.on('object:selected', function (e) {
        if (e.target.type === 'i-text') {
            document.getElementById('textControls').hidden = false;
        }
        if (e.target.type === 'image') {
            document.getElementById('textControls').hidden = false;
        }
    });

    canvas.on('before:selection:cleared', function (e) {
        if (e.target.type === 'i-text') {
            document.getElementById('textControls').hidden = true;
        }
    });

    window.deleteObject = function () {
        var activeGroup = canvas.getActiveGroup();
        if (activeGroup) {
            var activeObjects = activeGroup.getObjects();
            for (let i in activeObjects) {
                canvas.remove(activeObjects[i]);
            }
            canvas.discardActiveGroup();
            canvas.renderAll();
        } else canvas.getActiveObject().remove();
    }

    // Refresh page
    function refresh() {
        setTimeout(function () {
            location.reload()
        }, 100);
    }

    // Add text
    function Addtext(testo) {
        canvas.add(new fabric.IText(testo, {
            left: 0,
            top: 0,
            fontFamily: 'Arial',
            fill: '#000',
            stroke: '#fff',
            strokeWidth: .1,
            fontSize: 16
        }));
    }

    function AddImage() {
        text = document.getElementById('cd_cf').value;
        if (text != '') {
            text = text + '.jpg';
            var immagine = new fabric.Image.fromURL('http://server:8081/img/' + text, function (oImg) {
                canvas.add(oImg);
            });
        }
    }


    function AddRectangle() {
        var rect = new fabric.Rect({
            left: 100,
            top: 50,
            fill: '#fff',
            width: 200,
            height: 100,
            objectCaching: false,
            stroke: '#000',
            strokeWidth: 1,
        });

        canvas.add(rect);
        canvas.setActiveObject(rect);
    }

    document.getElementById('text-font-size').onchange = function () {
        canvas.getActiveObject().setFontSize(this.value);
        canvas.renderAll();
    };
    document.getElementById('text-align').onchange = function () {
        canvas.getActiveObject().setTextAlign(this.value);
        canvas.renderAll();
    };

    document.getElementById('font-weight').onclick = function () {
        font_weight = canvas.getActiveObject().getFontWeight();

        if (font_weight == 'bold') {
            canvas.getActiveObject().setFontWeight('');
        } else {
            canvas.getActiveObject().setFontWeight('bold');
        }
        canvas.renderAll();
    };

    // Send selected object to front or back
    var selectedObject;
    canvas.on('object:selected', function (event) {
        selectedObject = event.target;
    });
    var sendSelectedObjectBack = function () {
        canvas.sendToBack(selectedObject);
    }
    var sendSelectedObjectToFront = function () {
        canvas.bringToFront(selectedObject);
    }

    // Do some initializing stuff
    fabric.Object.prototype.set({
        transparentCorners: true,
        cornerColor: '#22A7F0',
        borderColor: '#22A7F0',
        cornerSize: 12,
        padding: 5
    });

    <?php /*
    <?php $immagine = 'immagine'.$pagina;$json = 'json'.$pagina; ?>
    canvas.setBackgroundImage('<?php echo URL::asset($modulo->$immagine) ?>', canvas.renderAll.bind(canvas), {
        originX: 'left',
        originY: 'top',
        scaleX: 0.48,
        scaleY: 0.48,
        left: 0,
        top: 0
    }); */ ?>

    function loadCanvas(json) {
        canvas.loadFromJSON(json, function () {
            canvas.renderAll();
        }, function (o, object) {
            console.log(o, object)
        })
    }

    <?php if ($etichetta->json != ''){ ?>
    loadCanvas('<?php echo addslashes($etichetta->json) ?>');
    <?php } ?>


    function Save() {

        var json = JSON.stringify(canvas.toJSON());


        // save via xhr
        $.post('/salva_etichetta/<?php echo $etichetta->Id_xSPReport ?>', {json: json}, function (resp) {
        }, 'json');

        alert('Campi Salvati');
    }


    function test_etichetta() {

        // id_bolla = $('#id_prblattivita').val();
        param1 = $('#param1').val();
        tipologia = <?php echo $etichetta->tipologia ?>;
        codice = '<?php echo $etichetta->codice ?>';

        window.open('<?php echo URL::asset('test_etichetta') ?>/' + param1 + '/' + tipologia + '/' + codice);
    }


    var param1 = localStorage.getItem("param1");
    $("#param1").val(param1);

</script>


