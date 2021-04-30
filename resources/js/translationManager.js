require('x-editable-bs4/dist/bootstrap4-editable/js/bootstrap-editable');

//buttons
window.$.fn.editableform.buttons =
    '<button type="submit" class="btn btn-primary btn-sm editable-submit">'+
    '<i class="now-ui-icons ui-1_check"></i>'+
    '</button>'+
    '<button type="button" class="btn btn-default btn-sm editable-cancel">'+
    '<i class="now-ui-icons ui-1_simple-remove"></i>'+
    '</button>';

require('jquery-ujs');

jQuery(document).ready(function($)
{
    var Cookies = require('js-cookie');

    $.ajaxSetup({
        beforeSend: function (xhr, settings)
        {
            console.log('beforesend');
            settings.data += "&_token=" + document.head.querySelector('meta[name="csrf-token"]').content;
        }
    });

    $('.editable').editable()
        .on('hidden', function (e, reason)
        {
            var locale = $(this).data('locale');
            if (reason === 'save')
            {
                $(this).removeClass('status-0').addClass('status-1');
            }
            if (reason === 'save' || reason === 'nochange')
            {
                var $next = $(this).closest('tr').next().find('.editable.locale-' + locale);
                setTimeout(function ()
                {
                    $next.editable('show');
                }, 300);
            }
        });

    $('.group-select').on('change', function ()
    {
        window.location.href = $(this).find(':selected').data('action');
    });

    $("a.delete-key").click(function (event)
    {
        event.preventDefault();
        var row = $(this).closest('tr');
        var url = $(this).attr('href');
        var id = row.attr('id');
        $.post(url, {id: id}, function ()
        {
            row.remove();
        });
    });

    $('.form-import').on('ajax:success', function (e, data)
    {
        $('div.success-import strong.counter').text(data.counter);
        $('div.success-import').slideDown();
        window.location.reload();
    });

    $('.form-find').on('ajax:success', function (e, data)
    {
        $('div.success-find strong.counter').text(data.counter);
        $('div.success-find').slideDown();
        window.location.reload();
    });

    $('.form-publish').on('ajax:success', function (e, data)
    {
        $('div.success-publish').slideDown();
    });

    $('.form-publish-all').on('ajax:success', function (e, data)
    {
        $('div.success-publish-all').slideDown();
    });
    $('.enable-auto-translate-group').click(function (event)
    {
        event.preventDefault();
        $('.autotranslate-block-group').removeClass('hidden');
        $('.enable-auto-translate-group').addClass('hidden');
    })
    $('#base-locale').change(function (event)
    {
        console.log($(this).val());
        Cookies.set('base_locale', $(this).val());
    })
    if (typeof Cookies.get('base_locale') !== 'undefined')
    {
        $('#base-locale').val(Cookies.get('base_locale'));
    }
});
