//скрипты для страницы edit_post

//очищаем папку temp
clear_temp();

//richText для редактирования текста поста
$('.textarea').richText({
    imageUpload: false,
    videoEmbed: false,
    fileUpload: false,
    fileUpload: false,
});

//tagEditor
BulmaTagsInput.attach('input[data-type="tags"], input[type="tags"], select[data-type="tags"], select[type="tags"]', {
    tagClass: 'is-rounded is-grey'
});

//счетчик символов
$(document).ready(function () {
    $('#post_title').charCounter();
});

//переменная для хранения строки, которая будет удаленя после удаления файла
var tr;

//список загруженных файлов в папку temp
var uploaded_files = [];

//Plyr, видеоплеер
const player = new Plyr('#player');

//получаем кол-во файлов в посте
//если файлов 0, то таблица с файлами не будет показываться
var num_of_files = $("#tbody").children().length;

//по нажатию на кнопку удаления показать модальное окно подтвреждения удаления
$(document).on("click", ".delete_media", function () {
    //показываем модальное окно
    $(".modalDelete").addClass("is-active fade-in");
    //если параметр data-id не определен, то значит в модальное окно будет передаваться имя файла
    //и удаление произойдет по имени файла
    if ($(this).data("id") === undefined) {
        $("#submit_modal").data("id", $(this).data("filename"));
    } else //если data-id определен, значит передаваться будет id, и удаление произойдет по id
    //т.е при помощи записи о файле в БД
    {
        $("#submit_modal").data("id", $(this).data("id"));
    }

    //записываем строку таблицы в которой находится файл, чтобы удалить её после удаления
    tr = $(this).parent().parents()[0];
});

//закрыть модальное окно подтвреждения удаления
$("#close_delete_modal").click(function () {
    $(".modalDelete").removeClass("is-active fade-in");
});

//удалить файл
$("#submit_modal").click(function () {
    //прячем строку таблицы и отправялем ajax
    //послать запрос на удаление файла
    send_delete_media_request($(this).data("id"));
});

//отправка запроса на удаление файла через ajax
function send_delete_media_request(media_id) {
    //установка заголовка с csrf-токеном
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //отправка запроса
    $.ajax({
        type: 'POST',
        url: '/delete_media',
        data: {
            id: media_id
        },
        success: function (response) {
            //если запрос выполнился успешно, то уменьшем счетчик файлов на один
            num_of_files--;
            $(tr).attr("style", "display: none;"); //и удаляем строку таблицы с файлом
            //убираем модальное окно
            $(".modalDelete").removeClass("is-active fade-in");
            if (num_of_files === 0) {
                $("#appended_files").remove();
                $("#file_browser").addClass("invisible").removeClass("fade-in");
                $("#no_files").removeClass("invisible").addClass("fade-in");
            }
            var index_of_el = uploaded_files.findIndex(el => el.includes(media_id));
            if (index_of_el != -1) {
                uploaded_files.splice(index_of_el, 1);
                console.log("uploaded files", uploaded_files)
            }
            console.log("%cThe file has been succesfully deleted.", "color: red;");
        }
    });
}

//показать превью файла
$(document).on('click', ".preview", function () {
    $("#preview-modal").addClass("is-active fade-in");
    //если картинка, то показываем тег img
    if ($(this).data("type") === "image") {
        $("#content-in-modal").attr("style", "display: block");
        $("#content-in-modal").attr("src", $(this).data("url"));

        $("#content-in-modal").on('load', function () {
            $("#content-in-modal").attr("style", "display: block;");
            var screen_height = window.screen.height;
            var img_height = $("#content-in-modal").height();
            var img_width = $("#content-in-modal").width();
            if (img_height > screen_height) {
                var new_img_height = img_height / 1.368;
                var new_img_width = img_width / 1.368;
                $("#content-in-modal").width(new_img_width).height(new_img_height)
            }
        });
    }
    //если видео, то показываем плеер
    if ($(this).data("type") === "video") {
        $("#player_div").attr("style", "display: block;");
        $("#content-video").attr("src", $(this).data("url"));
        player.source = {
            type: 'video',
            title: 'Preview',
            sources: [{
                src: $(this).data("url"),
                type: 'video/mp4',
                size: 720,
            }, ],
        };
    }
});

//закрыть модальное окно с превью
$("#modal-close").click(function () {
    $("#preview-modal").removeClass("is-active");
    player.stop(); //стопорим плеер, чтобы видео не играло в фоне :)
    $("#content-in-modal").attr("style", "display: none");
    $("#player_div").attr("style", "display: none;");
});

//выключаем autoDiscover у дропзоны
Dropzone.autoDiscover = false;

//dropzone
var dropzone = $("#dropzone_form").dropzone({
    //autoProcessQueue: false, //автозагрузка файлов: 
    chunking: true, //разбиение на чанки
    chunkSize: 20000000, //макс размер чанка: 20 мб
    retryChunks: false,
    addRemoveLinks: true, //кнопка удаления файлов
    paramName: 'file',
    forceChunking: true,
    maxFiles: 20,
    maxFilesize: 4000, //максимальный размер файла: 4 гб
    parallelUploads: 20,
    acceptedFiles: '.jpg,.jpeg,.png,.mp4',
    init: function () {
        var dz = this;
        //при отправке файла показать сообщение в консоли и добавить отправляемым файлам имя файла
        this.on('sending', function (file, xhr, data) {
            console.log(`%cSending file ${file.name}`, 'color:grey;');
            data.append("filename", file.name);
        });

        this.on('success', function (file) {
            dz.removeFile(file); //убираем файл из дропзоны

            //получаем ответ с сервера
            var response = JSON.parse(file.xhr.response);
            uploaded_files.push(response.filename);

            //если кол-во файлов - ноль, то убираем плашку о том что файлов нет и показываем таблицу
            if (num_of_files === 0) {
                $("#no_files").addClass("invisible");
                $("#file_browser").removeClass("invisible").addClass("fade-in");
            }

            //делаем +1 к кол-ву файлов
            num_of_files++;

            var tbody = $("#tbody");
            if (num_of_files === 1) //если кол-во файлов 1, то добавляем заголовок "Appended files"
            {
                files_appended = true;
                tbody.append("<tr class='fade-in' id='appended_files'><td colspan='3'><b>Appended files</b></td>");
            }

            //выводим файл в таблице
            tbody.append(`<tr class='fade-in'><td><a class='preview' data-type="${response.mime}" data-url="${response.file_url}">${response.filename}</a></td><td></td>
      <td>${response.mime}</td> <td><a class="button is-small is-danger delete_media" data-tooltip="Delete this media" data-filename="${response.filename}">
      <span class="icon">
        <i class="fas fa-trash"></i>
      </span>
    </a></td></tr>`);
        });

        this.on('canceled', function (file) {
            console.log(`%c${file.name} - upload has been canceled.`, 'color: red;');
        });

    },
    //когда файл или все чанки файла загрузятся, пишем в консоль
    chunksUploaded: function (xhr, done, file) {
        done();
        console.log(`The file ${xhr.name} has been uploaded.`);

        $("#footer").attr("style", `top: ${$(document).height()}px;`)
    }
});

//отправить пост
$("#submit_post").click(function () {
    $(this).attr("disabled", "disabled");
    //установка заголовка с csrf-токеном
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //отправка запроса
    $.ajax({
        type: 'POST',
        url: `/post/${$("#post_id").val()}/edit`,
        data: {
            post_id: $("#post_id").val(),
            file_list: JSON.stringify(uploaded_files),
            post_title: $("#post_title").val(),
            post_content: $(".textarea").val(),
            post_visibility: $("#publish_checkbox").is(":checked"),
            post_date: $("#publish_date").val(),
            post_category: $("#post_category").val(),
            tags: $("#tags").val()
        },
        //при успешном завершении запроса редиректим к постам
        success: function (response) {
            window.location.replace("/control/posts");
        }
    });
});
