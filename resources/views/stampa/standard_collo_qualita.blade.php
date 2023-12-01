
<div style="padding:10px">
    <table>

        <tr>
            <td colspan="2" style="text-align: center"><h2 ><?php echo $collo->Descrizione_CF ?></h2></td>
        </tr>

        <tr>
            <td style="text-align: center">Lotto<br><b style="font-size:20px;"><?php echo $collo->IdOrdineLavoro ?></b></td>
            <td style="text-align: center">Trattamento <br><b><?php echo ($collo->xTrattamento)?'SI':'NO' ?></b></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;"><?php echo  'B. '.number_format($collo->xBase,2,'.','').' - S.L. '.number_format($collo->xSoffiettoL/2,2,'.','').'+'.number_format($collo->xSoffiettoL/2,2,'.','').' - H. '.number_format($collo->xAltezza,2,'.','').' - SP. '.number_format($collo->xSpessore,2,'.','').'my' ?></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;"><b><?php echo $collo->Descrizione_AR ?></b></td>
        </tr>

        <tr>
            <td style="text-align: center">Collo NR. <br><b><?php echo $collo->Descrizione ?></b></td>
            <td style="text-align: center">
                Peso Metro <b><?php echo number_format($collo->xPesoMetro,2,'.','') ?></b><br>
                Peso Busta <b><?php echo number_format($collo->xPesoBusta,2,'.','') ?></b>
            </td>
        </tr>

        <tr>
            <td style="text-align: left"><?php echo $collo->Cd_Operatore ?><br><b><?php echo $collo->Cd_PrAttivita ?></b></td>
            <td style="text-align: right"><?php echo date('d/m/Y H:i',strtotime($collo->TimeIns)) ?></td>
        </tr>

    </table>

</div>

<style>

    body{
        font-size:14px;
        font-family: Arial;
    }
</style>

