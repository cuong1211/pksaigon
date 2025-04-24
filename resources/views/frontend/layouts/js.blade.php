<script>
    //setup header
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //hiển thị thông báo
    function notification(type, title, content) {
        title = '';
        if (Array.isArray(content)) {
            let string = '';
            $.each(content, function(index, item) {
                string += item + '<br/>';
            });
            content = string;
        }
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toastr-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        switch (type) {
            case 'success':
                toastr.success(content, title);
                break;
            case 'error':
                toastr.error(content, title);
                break;
            case 'warning':
                toastr.warning(content, title);
                break;
            default:
                toastr.warning('Không xác định được thông báo', 'Cảnh báo!');
                break;
        }
    }
</script>
