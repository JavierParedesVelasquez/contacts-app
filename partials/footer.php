<!-- footer -->
</main>
<!-- Bootstrap CDN - JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<?php $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); ?>
<?php if ($uri == "/contacts-app/" ||  $uri == "/contacts-app/index.php") : ?>

    <script defer src="./static/js/welcome.js"></script>
<?php endif ?>
</body>

</html>