$(() => {

    // управоение размеров iframe ####################################################################################################
        if ($('#iframe-job-module', parent.document.body).attr('height') === 'auto') {
            $(window).resize(() => JM_iframe_height());
            JM_iframe_height();
        }
        else $('div.job-data').css({'flex-grow': 1, 'overflow-y': 'auto'});

    // предупреждение перехода
        $('a').on('click', e => {
            if (typeof $(e.target).attr('href') !== 'undefined') {
                if ($('form#resume').length !== 0) {
                // проверка данных
                    let conf = false;
                    $('form#resume div.file-elem input[type="file"]').each((i, e) => { if ($(e).val() !== '') conf = true; });
                    $('form#resume div.multifile-elem input[type="file"]:eq(0)').each((i, e) => { if ($(e).val() !== '') conf = true; });
                    $('form#resume').find('input[type="text"], input[type="date"]').each((i, e) => { if ($(e).val() !== '') conf = true; });
                    $('form#resume select:visible').each((i, e) => { if ($(e).val() !== '' && $(e).attr('name') !== 'IsBolashak[]') conf = true; });
                // вывод сообщения
                    if (conf) if (!confirm($('form#resume').attr('confirm'))) return false;
                }
            // вывод анимации
                if (!$(e.target).hasClass('file')) $('div#loader').show();
            }
        });

    // настройка полей
        $('div.hand-change input[type="checkbox"]').each((i, e) => JM_change_hand(e, false));
        $('div.file input[type="file"]').each((i, e) => {
            let div = $(e).closest('div.file');
            if ($(e).val() !== '') {
                $(div).find('div.file-name').text($(e).val());
                $(div).find('a.file-add').hide();
                $(div).find('a.file-delete').show();
            }
            else $(div).find('div.file-name').text($(div).find('div.file-name').attr('message'));
        });
        $('div.multifile-elem').each((i, e) => JM_copy_file_elem(e));
        $('form select[name="Country[]"]').each((i, e) => JM_load_city(e));

    // ввод только чисел #############################################################################################################
        $('form').on('input', 'input[name="TotalExperience"], input[name="EducationExperience"]', e => JM_input_only_digital(e));

    // управление ошибками ###########################################################################################################
        $('form').on('input', 'input.alert', (e) => JM_alert_empty($(e.target), 'remove'));
        $('form').on('change', 'select.alert', (e) => JM_alert_empty($(e.target), 'remove'));
        $('form').on('input', 'textarea#g-recaptcha-response', e => { alert(12); $(e.target).closest('div.section').removeClass('alert') });

    // управление списком городов
        $('form').on('change', 'select[name="Country[]"]', e => JM_load_city(e.target));

    // управление типа ввода #########################################################################################################
        $('form').on('change', 'div.hand-change input[type="checkbox"]', e => JM_change_hand(e.target));
        $('form').on('click', 'div.hand-change div:last-child', e => $(e.target).parent('div').find('input').click());

    // управление блоками образования ################################################################################################
        $('a.add-education').on('click', e => JM_education_add(e));
        $('div#job-data-org').on('click', 'a.delete-education', e => JM_education_remove(e));

    // управление выбором файлов #####################################################################################################
        $('form').on('click', 'a.file-add', e => $(e.target).closest('div.file').find('input[type="file"]').click());
        $('form').on('input', 'input[type="file"]', e => JM_file_add(e));
        $('form').on('click', 'a.file-delete', e => JM_file_remove(e));

    // проверка заполнения полей и отправка формы
        $('a.send-form').on('click', () => JM_form_send());
});

// контроль высоты iframe ############################################################################################################
    function JM_iframe_height() {
        let iframe = $('#iframe-job-module', parent.document.body);
        iframe.height(document.body.scrollHeight);
    }

// загрузка списка городов ###########################################################################################################
    function JM_load_city(e) {
        // определение позиций
            let country = $(e);
            let city = $(country).parent('div').parent('div').next('div').find('select[name="City[]"]');
        // подготовка к запросу
            $(city).removeClass('alert');
            $(city).find('option:first').attr('selected', true);
            $(city).find('option.city').remove();
            if ($(country).val() === '') {
                $(city).find('option:first').text($(city).attr('mes-country-empty'));
                $(city).attr('disabled', true);
                return false;
            }
            $(city).find('option:first').text($(city).attr('mes-load-city'));
            $(country).attr('disabled', true);
            $(city).attr('disabled', true);
        // запрос данных
            $.getJSON( "ajax.php", { 'country': $(e).val() }, (data) => {
                if (typeof data === 'object') {
                    if (Object.keys(data).length != 0) {
                        for (let i in data) $(city).append('<option class="city" value=' + i + '>' + data[i] + '</option>');
                    }
                    $(city).find('option:first').text($(city).attr('mes-default'));
                    $(country).attr('disabled', false);
                    $(city).attr('disabled', false);
                };
            }).fail(() => { // данные некорректные
                $(city).addClass('alert');
                $(city).find('option:first').text($(city).attr('mes-load-error'));
                $(country).attr('disabled', false);
            });
    }

// образование - добавить ############################################################################################################
    function JM_education_add(e) {
        // подтверждение
            if (!confirm($(e.target).attr('confirm'))) return false;
        // копирование
            let title = $('div.education-title').last().clone();
            let data = $('div.education-data').last().clone();
        // очистка копии
            $(data).find('*.alert').each((i, e) => $(e).removeClass('alert'));
            $(data).find('div.hand-change input[type="checkbox"]').each((i, e) => {
                $(e).prop('checked', false);
                JM_change_hand(e);
            });
            $(data).find('input').val('');
            $(data).find('select option:first-child').attr('selected', true);
            $(data).find('input[type="checkbox"]').prop('checked', false);
            $(data).find('textarea').val('');
        // вставка копии
            $('div.education-data').last().after(title);
            $(title).after(data);
        // контроль видимости кнопки удаления
            $('a.delete-education').removeClass('hidden');
        // правка заголовков
            JM_education_number();
    }

// образование - удалить #############################################################################################################
    function JM_education_remove(e) {
        // контроль удаления
            if ($('div.education-data').length === 1) return false;
        // удаление
            if (!confirm($(e.target).attr('confirm'))) return false;
            let data = $(e.target).parent('div');
            let title = $(data).prev('div');
            $(title).remove();
            $(data).remove();
        // контроль видимости кнопки удаления
            if ($('div.education-data').length === 1) $('a.delete-education').addClass('hidden');
        // правка заголовков
            JM_education_number();
    }

// переключение полей для ручного ввода ##############################################################################################
    function JM_change_hand(e, focus = true) {
        let div = $(e).closest('div.hand-change').parent('div');
        let sel = $(div).find('select');
        JM_alert_empty($(div).find('input'), 'remove');
        JM_alert_empty(sel, 'remove');
        if ($(e).is(':checked')) {
            $(sel).val('').hide();
            $(div).find('input[type="hidden"]').attr('type', 'text');
            if (focus) $(div).find('input[type="text"]').focus();
        }
        else {
            $(div).find('input[type="text"]').attr('type', 'hidden');
            $(sel).val('').show();
        }
    }

// добавление файла ##################################################################################################################
    function JM_file_add(e) {
        let div = $(e.target).closest('div.file').removeClass('alert');
        $(div).parent('div').prev('div').removeClass('alert');
        $(div).find('div.file-name').text($(e.target).val());
        $(div).find('a.file-add').hide();
        $(div).find('a.file-delete').show();
        if ($(div).closest('div.section').hasClass('multifile-elem')) JM_copy_file_elem($(div).closest('div.section'));
    }

// удаление файла ####################################################################################################################
    function JM_file_remove(e) {
        let div = $(e.target).closest('div.file');
        let mes = $(div).find('div.file-name').attr('message');
        $(div).find('div.file-name').text(mes);
        $(div).find('input[type="file"]').val('');
        $(div).find('a.file-delete').hide();
        $(div).find('a.file-add').show();
        // удаление элемента формы
            let sec = $(div).closest('div.section');
            if ($(sec).hasClass('multifile-elem')) {
                $(div).parent('div').parent('div').remove();
                if ($(sec).find('div.file').length < parseInt($(sec).attr('item')) + 1) {
                    $(sec).children('div:last-child').removeClass('hidden');
                }
            }
    }

// копирование элемента управление файлом
    function JM_copy_file_elem(e) {
        if ($(e).find('div.file:last-child').find('input[type="file"]').val() === '') return false;
        if ($(e).find('div.file').length < parseInt($(e).attr('item')) + 1) {
            let copy = $(e).children('div:last-child').clone();
            let mes = $(copy).find('div.file-name').attr('message');
            $(copy).find('div.file-name').text(mes);
            $(copy).find('input[type="file"]').val('');
            $(copy).find('a.file-delete').hide();
            $(copy).find('a.file-add').show();
            $(e).children('div:last-child').after(copy);
            if ($(e).find('div.file').length > parseInt($(e).attr('item'))) $(copy).addClass('hidden');
        }
    }

// пересчет номеров образования ######################################################################################################
    function JM_education_number() {
        $('div.education-title').each((i, e) => {
            // заголовка
                let arr = $(e).text().split('№');
                arr[arr.length - 1] = i + 1;
                $(e).text(arr.join('№'));
            // кнопки удаления
                arr = $('a.delete-education:eq(' + i + ')').text().split('№');
                arr[arr.length - 1] = i + 1;
                $('a.delete-education:eq(' + i + ')').text(arr.join('№'));
        });
    }

// упаравление подсветкой пустых полей ###############################################################################################
    function JM_alert_empty(e ,type) {
        if (type === 'add') {
            $(e).addClass('alert');
            $(e).parent('div').prev('div').addClass('alert');
        }
        if (type === 'remove') {
            $(e).removeClass('alert');
            $(e).parent('div').prev('div').removeClass('alert');
        }
    }

// ввод только чисел #################################################################################################################
    function JM_input_only_digital(e) {
        let reg = /^\d+(\.?)\d*$/g;
        while (!($(e.target).val().match(reg)) && $(e.target).val() !== '') {
            $(e.target).val($(e.target).val().slice(0, -1));
        }
    }

// проверка и отправка формы #########################################################################################################
    function JM_form_send() {
        // проверка файлов
            $('form#resume div.file-elem input[type="file"]').each((i, e) => {
                if ($(e).val() === '') JM_alert_empty($(e).parent('div'), 'add');
            });
            $('form#resume div.multifile-elem input[type="file"]:eq(0)').each((i, e) => {
                if ($(e).val() === '') JM_alert_empty($(e).parent('div'), 'add');
            });
        // проверка полей вода
            $('form#resume').find('input[type="text"], input[type="date"]').each((i, e) => {
                $(e).val($(e).val().trim());
                if ($(e).val() === '') JM_alert_empty(e, 'add');
            });
        // проверка списков
            $('form#resume select:visible').each((i, e) => {
                if ($(e).val() === '') JM_alert_empty(e, 'add');
            });
        // проверка капчи
            $('textarea#g-recaptcha-response').closest('div.captcha').removeClass('alert');
            if ($('textarea#g-recaptcha-response').val() === '') {
                $('textarea#g-recaptcha-response').closest('div.captcha').addClass('alert');
            }

        // отправка формы
            if ($('form#resume').find('*.alert').length === 0) $('form#resume').submit();
            else {
                let top = $('form#resume').find('*.alert:eq(0)').offset().top;
                let tag = $('div#job-data-org div.elem').offset().top;
                $('div#job-data-org').scrollTop(top - tag - 10);
            }
    }