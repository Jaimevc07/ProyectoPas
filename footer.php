</main>
<footer class="px-2 py-2 fixed-bottom bg-dark">
    <span class="text-muted">Casino Chass Rojo | Lector de Tarjetas RFID 
        &nbsp;|&nbsp;
        Has iniciado sesion como
        <?php
        echo $_SESSION["nombre"] . " " . $_SESSION["apellido"];
        ?>
    </span>
</footer>
</body>
</html>