<script type="text/javascript" src="https://www.smartpost.ee/widget/FiAPTLocation.js" charset="utf-8"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<script type="text/javascript">
    const waitUntilElementExists = (selector, callback) => {
        const el = document.querySelector(selector);
        if (el) { return callback(el); }
        setTimeout(() => waitUntilElementExists(selector, callback), 500);
    }
</script>
