<table>
    <thead>
    <tr>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">Codice Articolo</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">Cd_ARLotto</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">Quantita</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">U.M.</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">DataMov</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">NumeroOVC</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">NumeroDDT</th>
        <th style="font-weight: bold;border:1px solid white;" colspan="1">NumeroOL</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($righe as $r) { ?>
    <tr>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['0']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['1']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['2']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['3']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['4']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['5']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['6']; ?></td>
        <td colspan="1" style="border:1px solid white;"><?php echo $r['7']; ?></td>
    </tr>
    <?php } ?>

</tbody>
</table>
