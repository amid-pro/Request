<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .key_value {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .badge_add {
            cursor: pointer;
        }

        .output_div {
            margin-top: 20px;
            font-size: 90%;
            padding: 20px;
            width: 100%;
            overflow: auto;
            border-radius: 4px;
            background-color: #222222;
            opacity: 0.9;
            white-space: pre;
        }

        .output_div.url {
            background-color: #23272c;
        }

        .output_div.error {
            background-color: #580606;
        }

        .op_null {
            opacity: 0;
        }
    </style>
</head>

<body class="bg-body-tertiary">
    <div class="container">
        <main>
            <div class="py-5 text-left text-small">
                <?= $uname ?> / PHP <?= $version ?>
            </div>

            <div class="row g-5">

                <div class="col-md-5 col-lg-4 order-md-last" id="params">
                    <ul class="list-group mb-3">

                        <li class="list-group-item d-flex justify-content-between lh-sm" v-for="block in blocks">
                            <div class="request_params" v-bind:name="block.curl">
                                <p class="my-0">{{ block.title }} <span v-on:click="add_param_value(block.block_id)" class="badge_add badge bg-success">&plus;</span></p>

                                <key-value v-for="item in block.params_values" v-bind:item_id="item.id" v-bind:block_id="block.block_id" v-bind:key="block.block_id + item.id"></key-value>

                            </div>
                        </li>

                    </ul>
                </div>


                <div class="col-md-7 col-lg-8">
                    <div class="row g-3" id="form">

                        <div class="col-md-8">
                            <label for="url" class="form-label">Url</label>
                            <input type="text" v-model="url" class="form-control" id="url" placeholder="https://host.domain">
                        </div>

                        <div class="col-md-3">
                            <label for="method" class="form-label">Method</label>
                            <select class="form-select" v-model="method" id="method">
                                <option selected>GET</option>
                                <option>POST</option>
                                <option>PUT</option>
                                <option>PATCH</option>
                                <option>DELETE</option>
                                <option>HEAD</option>
                                <option>OPTIONS</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label op_null">&rarr;</label>
                            <button v-on:click="send_form" v-bind:disabled="url === '' || button_disabled" class="btn btn-primary" type="button">&rarr;</button>
                        </div>

                    </div>

                    <div id="output" v-if="error !== '' || result !== ''">
                        <hr />
                        <div v-if="url !== ''" class="output_div url">{{ url }}</div>
                        <div v-if="error !== ''" class="output_div error">{{ error }}</div>
                        <div v-if="result !== ''" class="output_div">{{ result }}</div>
                        <div v-if="info !== ''" class="output_div info">{{ info }}</div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="my-5 pt-5 text-body-secondary text-center text-small">
            <hr />
            <p class="mb-1">&copy; <?= $year ?></p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="https://github.com/amid-pro/Request">github.com/amid-pro/Request</a></li>
            </ul>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>
    <script src="/public/app.js"></script>
</body>

</html>