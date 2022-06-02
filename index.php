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


    // echo '<pre>';
    // echo '<br>';
    // echo '<Контент json = >';
    // print_r($shops_arr);
    ?>

    <section>
        <div class="container">
            <div class="col-6 mx-auto">
                <h2>Выберите мастерскую</h2>
                <form id="form_shop" class="mt-3">
                    <select name="shop_id" id="shop_id" class="form-select" aria-label="Выберите мастерскую">
                        <option selected>Выберите мастерскую</option>


                        <?php
                        for ($shop = 0; $shop < count($shops_arr); $shop++) {

                        ?>

                            <option value="<?= $shops_arr['data'][$shop]['id'] ?>"><?= $shops_arr['data'][$shop]['name'] ?></option>

                        <?php
                        }

                        ?>
                    </select>
                    <div class="input-group mt-3">
                        <span class="input-group-text" id="basic-addon1">№</span>
                        <input id="sales_number" name="sales_number" type="number" class="form-control" placeholder="Номер заказа" aria-label="Номер заказа" aria-describedby="basic-addon1">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Искать</button>
                </form>
                <div class="content_container mt-3">

                </div>
            </div>
        </div>
    </section>

    <script>
        let form_shop = document.getElementById('form_shop');
        let sales_number_input = document.getElementById('sales_number');

        form_shop.addEventListener('submit', (e) => {
            e.preventDefault();

            let shop_id = document.getElementById('shop_id').value
            let cont_container = document.querySelector('.content_container')
            let data_shop_id = $(form_shop).serializeArray();
            // console.log(data_shop_id);


            $.ajax({
                type: "post",
                url: "config/get_shopData_sales.php",
                data: data_shop_id,
                success: function(res) {
                    if (res == 0) {
                        cont_container.innerHTML = `<div>Вы не ввели номер покупки</div>`;
                        // console.log('777');
                    } else {
                        let response = JSON.parse(res)
                        // console.log(res);
                        exit_data_sale(response);
                    }

                }
            });

            function exit_data_sale(arr) {

                let sales_level = '';

                if (arr['data'][0]['isBank'] == false) {
                    sales_level = 'Наличкой';
                } else {
                    sales_level = 'Картой';

                }


                cont_container.innerHTML = `
            <div>Продано в : ${arr['data'][0]['shop']['name']}</div>
            <div>Квитанция номер : ${arr['data'][0]['number']}</div>
            <div>Продано за : ${arr['data'][0]['summ']['soldPrice']}</div>
            <div>Оплачено : ${sales_level}</div>
            `;

                sales_number_input.value = '';
                shop_id = '';
            }

        })
    </script>

</body>

</html>