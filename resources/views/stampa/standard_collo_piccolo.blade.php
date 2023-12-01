
<div style="padding:10px">
    <table>

        <tr>
            <td colspan="2" style="text-align: center"><b style="font-size:24px;"><?php echo $collo->Descrizione_CF ?></b></td>
        </tr>

        <tr>
            <td style="text-align: center">Lotto<br><b style="font-size:24px;"><?php echo $collo->IdOrdineLavoro ?></b></td>
            <td style="text-align: center">Quantita Prodotta <br><b><?php echo $collo->Cd_ARMisura.' '.number_format($collo->QtaProdotta,0,'.','') ?></b></td>
        </tr>
        <tr>
            <td style="text-align: center;width:100px;"><barcode code="<?php echo $collo->Nr_Collo ?>" type="C128A" height="0.66" size="1" />
                <br><b><?php echo $collo->Nr_Collo ?></b></td>

            <td style="text-align: center">Numero Collo <br><b><?php echo $collo->Descrizione ?></b></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><?php echo $collo->attivita_next ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><b><?php echo $collo->Descrizione_AR ?></b></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><?php echo  'B. '.number_format($collo->xBase,2,'.','').' - S.L. '.number_format($collo->xSoffiettoL/2,2,'.','').'+'.number_format($collo->xSoffiettoL/2,2,'.','').' - H. '.number_format($collo->xAltezza,2,'.','').' - SP. '.number_format($collo->xSpessore,2,'.','').'my' ?></td>
        </tr>

        <tr>
            <td style="text-align: left"><?php echo $collo->Cd_Operatore ?><br><b><?php echo $collo->Cd_PrAttivita ?></b></td>
            <td style="text-align: right"><?php echo date('d/m/Y H:i',strtotime($collo->TimeIns)) ?></td>
        </tr>

    </table>

</div>

<style>

    body{
        font-size:13px;
        font-family: Arial;
    }
</style>

