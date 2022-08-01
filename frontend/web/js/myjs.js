
$(document).ready(function(){
    $("input").attr('autocomplete', 'off');
    // $('.checkicheck').iCheck({
    //     checkboxClass: 'icheckbox_square-red',
    //     radioClass: 'iradio_square-red',
    // });
    $('.dropicker').dateDropper({
        dropWidth: 200,
        format: 'd-m-Y'
    });

    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});

// untuk field khusus angka

$(document).on("keydown", ".angka", function(e){
    checkInput(e);
});

function checkInput(e){
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 189]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
}

function format_usd(amount, decimalCount = 2, decimal = ".", thousands = ",") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function deleteclick(url) 
{
    swal({ 
        title: 'Are you sure?', 
        type: 'warning', 
        showCancelButton: true, 
        closeOnConfirm: true, 
        allowOutsideClick: true,
        dangerMode: true
    }, function(){
        if ($('#pjax-task-grid').length > 0) {
            $.get(url, {}, function(){
                $.pjax.reload({container: '#pjax-task-grid'});
            });
        }
        else {
            window.location.href = url;
        }
    }, function(){
        return false;
    });
}

function submitbutton(method) {
    if (method == 'application.apply') {
        $('#application_form').submit();
	} else if (method == 'application.saveandnew') {
		$("#hiddensaveandnew").val("1");
		$('#application_form').submit();
    } else if (method == 'application.cancel') {
        if (referer != '') {
            window.location = referer;
        } else {
            window.location = base;
        }
    }
}
$.noConflict(true);

