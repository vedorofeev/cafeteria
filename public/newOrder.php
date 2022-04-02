<?php
require 'database.php';
ob_start();
?>
    <html>
    <head>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" type="text/css"
              href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
        <style>
            body {
                display: flex;
                min-height: 100vh;
                flex-direction: column;
            }

            main {
                flex: 1 0 auto;
            }

            body {
                background: #fff;
            }

            .input-field input[type=date]:focus + label,
            .input-field input[type=text]:focus + label,
            .input-field input[type=email]:focus + label,
            .input-field input[type=password]:focus + label {
                color: #e91e63;
            }

            .input-field input[type=date]:focus,
            .input-field input[type=text]:focus,
            .input-field input[type=email]:focus,
            .input-field input[type=password]:focus {
                border-bottom: 2px solid #e91e63;
                box-shadow: none;
            }

            .container {
                display: flex;
                width: 400px;
                margin: 0 auto;
                justify-content: center;
                flex-direction: column;
                align-items: center;
            }

            .container-input {
                width: 200px;
                border-radius: 4px;
            }

            .container-button {
                width: fit-content;
                border-radius: 20px;
                background: #ec3e9c;
                border: 1px solid rgba(232, 56, 191, 0.85);
                padding: 8px 32px;
                color: white;
                font-size: 1.5rem;
            }

            .back-btn {
                width: fit-content;
                border-radius: 20px;
                background: #ec3e9c;
                border: 1px solid rgba(232, 56, 191, 0.85);
                padding: 8px 32px;
                color: white;
                font-size: 1.5rem;
            }

            .container-select {
                cursor: pointer;
                width: 100%;
                border-radius: 4px;
                display: block;
                margin: 16px 0;
                color: black;
                padding-left: 2rem;
                border: 1px solid #f50dd8;
                font-size: 1.2rem;
            }

            label {
                font-size: 1.5rem;
            }
        </style>
    </head>

    <body>
    <div class="section"></div>
    <main>
        <center>
            <div class="section"></div>

            <h5 class="indigo-text">Make you order</h5>
            <div class="section"></div>

            <?php
                if (isset($_POST['order_name']) && isset($_POST['cafe_name'])) {
                    $user_id = json_decode($_GET['payload'])->sub;
                    $sql = "SELECT * FROM cafes WHERE cafe_name = $1 LIMIT 1";
                    $query = pg_prepare($GLOBALS['dbConn'], "my_query", $sql);
                    $result = pg_execute($GLOBALS['dbConn'], "my_query", array($_POST['cafe_name']));
                    if (!($result))
                        throw new Exception("newOrder error: cafe not found: $query\n", 404);
                    $cafe_id = pg_fetch_row($result)['0'];

                    $sql = "INSERT INTO orders(order_name, cafe_id, user_id) VALUES ($1, $2, $3)";
                    $query = pg_prepare($GLOBALS['dbConn'], "insert_order", $sql);
                    $result = pg_execute($GLOBALS['dbConn'], "insert_order", array($_POST['order_name'], $cafe_id, $user_id));
                    if (!($result))
                        throw new Exception("newOrder error: inserting failed: $query\n", 409);
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(201);
                } else {
                    echo "<form class='container' action='newOrder.php' method='post'>
                        <label for='first'>Order Name</label>
                        <input id='first' type='text' name='order_name' class='container-input'>
                        <label for='second'>Cafe Name</label>
                        <select id='second' name='cafe_name' class='container-select'> 
                            <option value='NONE OF YOUR BUSINESS'>NONE OF YOUR BUSINESS</option>
                            <option value='BERRY - RASPBERRY'>BERRY - RASPBERRY</option>
                            <option value='PALKI'>PALKI</option>
                        </select>
                        <button type='submit' class='container-button'>Authorize</button>
                    </form>";
                }
            ?>
        </center>


        <div class=" section "></div>
        <div class="section "></div>
    </main>
    <script type="text/javascript " src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js "></script>
    <script type="text/javascript "
            src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js "></script>
    </body>

    </html>

<?php
ob_end_flush();
?>