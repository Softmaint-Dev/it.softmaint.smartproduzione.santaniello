<script type="text/javascript">
    var wnd = window.open('<?php echo str_replace(' ', '', 'http://localhost:8081/upload/ ' . $stampante . '/ ' . $pdf); ?>');
    wnd.print();
    timer = setTimeout(closewindow, 7000);

    function closewindow() {
        clearTimeout(timer);
        wnd.close();
        window.close();
    }
</script>

