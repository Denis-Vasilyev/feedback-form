<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=6ec153f4-323b-40a0-bf8d-e79ed8ac45a4&lang=ru_RU" type="text/javascript"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LeRj_sgAAAAADytK8TDVSgj6obOm-Y3VJ8s7xf8"></script>
    <style>
        body {
            padding: 10px 0 0 20px;
        }
        label span.asterisk {
            color: red;
        }
        button:not([disabled]) > .enabled-send-btn {
            display: inline-block;
        }
        button:not([disabled]) > .disabled-send-btn {
            display: none;
        }
        button[disabled] > .disabled-send-btn {
            display: inline-block;
        }
        button[disabled] > .enabled-send-btn {
            display: none;
        }
        #jsSendTestForm {
            min-width: 160px;
        }
    </style>
    <title>Severgroup test example</title>
</head>
<body>
    <div class="modal fade" id="ymapModal" tabindex="-1" aria-labelledby="ymapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ymapModalLabel">Выберите адрес на карте</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div id="map" style="width: 465px; height: 400px"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Заголовок модального окна</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <form id="jsTestForm" class="row needs-validation" autocomplete="off" novalidate>
        <div class="container">
            <div class="row">
                <div class="col-md-4 gy-1">
                    <label for="FIO" class="form-label">ФИО</label>
                    <input type="text" class="form-control" id="FIO" placeholder="Укажите ФИО" maxlength="25">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 gy-1">
                    <label for="PHONE" class="form-label">Телефон<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="PHONE" placeholder="Укажите телефон" maxlength="25" required>
                    <div class="valid-feedback">
                        Все хорошо!
                    </div>
                    <div class="invalid-feedback">
                        Пожалуйста, укажите корректно телефон.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 gy-1">
                    <label for="ADRESS" class="form-label">Адрес<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="ADRESS" placeholder="Укажите адрес" required>
                    <div class="valid-feedback">
                        Все хорошо!
                    </div>
                    <div class="invalid-feedback">
                        Пожалуйста, укажите корректно адрес.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 gy-1">
                    <label for="ADRESS2" class="form-label">Адрес2<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="ADRESS2" placeholder="Укажите адрес2" required>
                    <div class="valid-feedback">
                        Все хорошо!
                    </div>
                    <div class="invalid-feedback">
                        Пожалуйста, укажите корректно адрес2.
                    </div>
                </div>
            </div>
            <input type="hidden" name="recaptcha_response" id="RECAPTCHA_RESPONSE">
            <div class="row">
                <div class="col-12 gy-3">
                    <button class="btn btn-primary" type="submit" id="jsSendTestForm" >
                        <span class="enabled-send-btn">Отправить форму</span>
                        <span class="disabled-send-btn">Загрузка...</span>
                        <span class="spinner-border spinner-border-sm disabled-send-btn" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            //google recaptcha
            grecaptcha.ready(function () {
                grecaptcha.execute('6LeRj_sgAAAAADytK8TDVSgj6obOm-Y3VJ8s7xf8', { action: 'contact' }).then(function (token) {
                    var recaptchaResponse = document.getElementById('RECAPTCHA_RESPONSE');
                    recaptchaResponse.value = token;
                });
            });
            //

            //инициализируем Dadata
            $("#ADRESS").suggestions({
                token: "27a422f5bd9704c6cc98b136776f9d6d55a51bf3",
                type: "ADDRESS"
            });
            //

            //инициализируем яндекс-карты
            ymaps.ready(init);

            function init() {
                let myInput = document.getElementById("ADRESS2"),
                    myPlacemark,
                    myMap = new ymaps.Map('map', {
                        center: [55.75082989210521, 37.62173955468747],
                        zoom: 9
                    }, {
                        searchControlProvider: 'yandex#search'
                    });


                // Слушаем клик на карте.
                myMap.events.add('click', function (e) {
                    let coords = e.get('coords');

                    // Если метка уже создана – просто передвигаем ее.
                    if (myPlacemark) {
                        myPlacemark.geometry.setCoordinates(coords);
                    }
                    // Если нет – создаем.
                    else {
                        myPlacemark = createPlacemark(coords);
                        myMap.geoObjects.add(myPlacemark);
                        // Слушаем событие окончания перетаскивания на метке.
                        myPlacemark.events.add('dragend', function () {
                            getAddress(myPlacemark.geometry.getCoordinates());
                        });
                    }
                    getAddress(coords);
                });

                // Создание метки.
                function createPlacemark(coords) {
                    return new ymaps.Placemark(coords, {
                        iconCaption: 'поиск...'
                    }, {
                        preset: 'islands#violetDotIconWithCaption',
                        draggable: true
                    });
                }

                // Определяем адрес по координатам (обратное геокодирование).
                function getAddress(coords) {
                    myPlacemark.properties.set('iconCaption', 'поиск...');
                    ymaps.geocode(coords).then(function (res) {
                        let firstGeoObject = res.geoObjects.get(0),
                            address = firstGeoObject.getAddressLine();

                        myPlacemark.properties
                            .set({
                                // Формируем строку с данными об объекте.
                                iconCaption: [
                                    // Название населенного пункта или вышестоящее административно-территориальное образование.
                                    firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                                    // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                                    firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                                ].filter(Boolean).join(', '),
                                // В качестве контента балуна задаем строку с адресом объекта.
                                balloonContent: address
                            });
                        myInput.value = address;
                        localStorage.setItem('value', address); // При вызове функции (которая срабатываем при нажатии на карте) записываем данные в localstorage

                        ymapModal.toggle();
                    });
                }
            }
            //

            const ymapModal = new bootstrap.Modal('#ymapModal', {
                keyboard: false
            });

            const exampleModal = new bootstrap.Modal('#exampleModal', {
                keyboard: false
            });

            $("#jsTestForm").on(
                "submit",
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    resetFormValidity(this);

                    if (this.checkValidity()) {

                        let formData = {
                            FIO: this.elements.FIO.value,
                            PHONE: this.elements.PHONE.value,
                            ADRESS: this.elements.ADRESS.value,
                            ADRESS2: this.elements.ADRESS2.value,
                            RECAPTCHA_RESPONSE: this.elements.RECAPTCHA_RESPONSE.value,
                            /*SESSID_5: this.elements.sessid_5.value*/
                        };

                        $('#jsSendTestForm').attr('disabled', !$('#jsSendTestForm').attr('disabled'));

                        $.ajax(
                            {
                                url: "/form_res_processor.php",
                                dataType: "json", // Для использования JSON формата получаемых данных
                                context: this,
                                method: "GET",
                                data: formData,
                                success: function (data) {
                                    let dataAndForm = {data: data, context: this};
                                    setTimeout(function () {
                                        data = dataAndForm.data;
                                        $this = dataAndForm.context;
                                        $('#exampleModal .modal-body').html('Форма успешно отправлена!');
                                        exampleModal.toggle();
                                        if (typeof data === "object" && "success" in data) {
                                            if (data.success) {
                                                resetForm($this);
                                            } else {
                                                $this.classList.remove("was-validated");
                                                data.errorData.forEach(function (item) {
                                                    let itemSelector = "#" + item;
                                                    setElementValidityResult(false, $(itemSelector).get(0));
                                                });
                                            }
                                        }
                                        $('#jsSendTestForm').attr('disabled', !$('#jsSendTestForm').attr('disabled'));
                                    }, 2000, dataAndForm);
                                },
                                error: function (data) {
                                    console.log(data);
                                    $('#exampleModal .modal-body').html('Произошла ошибка при отправке формы :(');
                                    exampleModal.toggle();
                                    $('#jsSendTestForm').attr('disabled', !$('#jsSendTestForm').attr('disabled'));
                                }
                            }
                        );
                    }

                    this.classList.add('was-validated'); //рендерит результат валидации
                }
            );

            function setElementValidityResult(validityResult, element) {
                if (validityResult) {
                    element.classList.remove("is-invalid");
                    element.classList.add("is-valid");
                    //element.setCustomValidity(""); //true
                } else {
                    element.classList.remove("is-valid");
                    element.classList.add("is-invalid");
                    //element.setCustomValidity("any-text"); //false
                }
            }

            function resetForm(form) {
                resetFormValidity(form);
                form.reset();
            }

            function resetFormValidity(form) {
                form.classList.remove("was-validated");
                $(form).find(".is-valid").removeClass("is-valid");
                $(form).find(".is-invalid").removeClass("is-invalid");
            }

            $("#FIO").on(
                "change",
                function () {
                    let validityRes = this.value.match(/^[a-zA-Zа-яА-Я\- ]{1,25}$/);
                    setElementValidityResult(validityRes, this);
                }
            );

            $("#PHONE").on(
                "change",
                function () {
                    let validityRes = this.value.match(/^\+?\d{1,3}?[- ]?\(?(?:\d{2,3})\)?[- ]?\d{1,4}[- ]?\d{1,4}[- ]?\d{1,4}$/);
                    setElementValidityResult(validityRes, this);
                }
            );

            $("#ADRESS2").on(
                "focus",
                function (el) {
                    ymapModal.toggle();
                }
            );
        });
    </script>
</body>
</html>