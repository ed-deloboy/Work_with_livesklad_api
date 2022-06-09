<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пример</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
        .find-text {
            color: black;
            padding: 2px;
            font-style: normal;
            /* background-color: #34C88C; */
        }

        [type="radio"]:checked,
        [type="radio"]:not(:checked) {
            position: absolute;
            left: -9999px;
        }

        [type="radio"]:checked+label,
        [type="radio"]:not(:checked)+label {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            line-height: 20px;
            display: inline-block;
            color: #666;
        }

        [type="radio"]:checked+label:before,
        [type="radio"]:not(:checked)+label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            border-radius: 100%;
            background: #fff;
        }

        [type="radio"]:checked+label:after,
        [type="radio"]:not(:checked)+label:after {
            content: '';
            width: 12px;
            height: 12px;
            background: #F87DA9;
            position: absolute;
            top: 4px;
            left: 4px;
            border-radius: 100%;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        [type="radio"]:not(:checked)+label:after {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        [type="radio"]:checked+label:after {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    </style>

</head>

<body>


    <?php

    require_once 'config/get_token.php';

    $result_shops = file_get_contents("https://api.livesklad.com/shops/", false, stream_context_create(array(
        'http' => array(
            'method'  => 'GET',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Authorization: " . $TOKEN,
        )
    )));

    $shops_arr = json_decode($result_shops, true);


    ?>

    <section>
        <div class="container">
            <div class="col-12 mx-auto">
                <div id="form_shop_div" class="form_1 p-2 bg-light mb-3">
                    <h2>Ваши данные</h2>
                    <form id="form_shop" class="mt-3">
                        <input class="form-control" type="text" name="agent_name" placeholder="Название организации">

                        <!-- <input class="form-control" type="text" name="agent_data" placeholder="ФИО агента"> -->
                        <button type="submit" class="btn btn-primary mt-3">Искать</button>
                    </form>
                </div>

                <div id="user_agent_name_form_div" class="form_2 p-2 bg-light mb-3 d-none">
                    <h2>Выберите город где купили и Ваш аккаунт</h2>
                    <form id="user_agent_name_form" class="mt-3">

                        <label for="user_select">Город покупки</label>
                        <select name="city_buy" id="city_buy" class="form-select mb-3" aria-label="Город покупки">
                            <option value="">Выбрать</option>
                            <option value="1">Ростов-на-Дону</option>
                            <option value="2">Шахты</option>
                            <option value="3">Красный Сулин</option>
                        </select>
                        <label for="user_select">Ваш аккаунт</label>
                        <select name="user_name" id="user_select" class="form-select" aria-label="Ваш аккаунт">

                        </select>
                        <button type="submit" class="btn btn-primary mt-3">Показать продажи</button>

                    </form>
                </div>
                <div id="spinner_container" class="d-flex justify-content-center pt-4 mt-3 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="content_container p-2 bg-light mb-3">
                    <!-- <h2>Ваши покупки</h2> -->
                    <div class="sales_container">
                        <table id="table" class="table d-none">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Гарантия</th>
                                    <th scope="col">Название</th>
                                    <th scope="col">Цена</th>
                                </tr>
                            </thead>
                            <tbody id="t_body">
                                
                            </tbody>
                        </table>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let form_shop = document.getElementById('form_shop');
        let form_shop_div = document.getElementById('form_shop_div');
        let sales_number_input = document.getElementById('sales_number');
        let data_container = document.getElementById('sales_container');
        let spinner_container = document.getElementById('spinner_container');
        let table = document.getElementById('table');
        let t_body = document.getElementById('t_body');
        

        let user_agent_name_form_div = document.getElementById('user_agent_name_form_div');
        let user_agent_name_form = document.getElementById('user_agent_name_form');

        form_shop.addEventListener('submit', (e) => {
            e.preventDefault();
            spinner_container.classList.remove('d-none');
            // let shop_id = document.getElementById('shop_id').value
            let cont_container = document.querySelector('.content_container')
            let data_shop_id = $(form_shop).serializeArray();
            let user_select = document.getElementById('user_select');
            // console.log(data_shop_id);

            // получение контрагента
            $.ajax({
                type: "post",
                url: "config/get_dataAgent_name.php",
                data: data_shop_id,
                success: function(res) {
                    spinner_container.classList.add('d-none');
                    form_shop_div.classList.add('d-none')
                    user_agent_name_form_div.classList.remove('d-none')
                    let response = JSON.parse(res)
                    // console.log(response);
                    search_agent(response['data']);
                }
            });

        })
        // отправляем на сервак агента
        user_agent_name_form.addEventListener('submit', (e) => {
            e.preventDefault();
            spinner_container.classList.remove('d-none');

            let data_name_users = $(user_agent_name_form).serializeArray();
            // console.log(data_name_users);

            $.ajax({
                type: "post",
                url: "config/get_shopData_sales.php",
                data: data_name_users,
                success: function(res) {
                    // console.log(res);
                    spinner_container.classList.add('d-none');
                    table.classList.remove('d-none');
                    let response = JSON.parse(res);
                    console.log(response);
                    // output_sales(response);
                }
            });
        })

        // functions //

        function output_sales(arr) {
            for (let i = 0; i < data.length; i++) {
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${arr[i]['data']['productHistories'][0]['guaranteeInMonth']}</td>
                    <td>${arr[i]['data']['productHistories'][0]['name']}</td>
                    <td>${arr[i]['data']['productHistories'][0]['soldPrice']}</td>
                    `)
                t_body.append(tr);
            }

        }

        // вывод имен в селект
        function search_agent(arr_names) {
            // form_shop_div.classList.add('d-none');
            // user_agent_name_form_div.classList.remove('d-none');
            let out = new Array;
            for (let i = 0; i < arr_names.length; i++) {
                out.push(`<option value="${arr_names[i]['name']}">${arr_names[i]['name']}</option>`);
            }
            // console.log(out);
            user_select.innerHTML = out;
        }
    </script>

</body>

</html>