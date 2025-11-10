<script>
// Mobile Tab - IMEI validation
document.getElementById('imei').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
