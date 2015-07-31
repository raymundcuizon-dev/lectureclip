<footer>
    <div class="large-12 columns">
        <hr/>
        <div class="row">
            <div class="large-6 columns">
                <p>© Copyright lectureclip</p>
            </div>
            <div class="large-6 columns">
                <span id='ct' ></span>
            </div>
        </div>
    </div>
</footer>
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script src="js/query.dataTables.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable();
    });
    function display_c() {
        var refresh = 1000; // Refresh rate in milli seconds
        mytime = setTimeout('display_ct()', refresh);
    }

    function display_ct() {
        var x = new Date();
        document.getElementById('ct').innerHTML = x;
        tt = display_c();
    }
</script>
<script>
    $(document).foundation();
</script>
</body>
</html>
