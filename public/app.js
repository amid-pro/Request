Vue.component('key-value', {
    template: `
        <div class="key_value row g-3">
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm param" placeholder="param">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm value" placeholder="value">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-danger" type="button" v-on:click="remove_param_value(block_id, item_id)">&minus;</button>
            </div>
        </div>
        `,
    props: ['item_id', 'block_id'],
    methods: {
        remove_param_value: function (block_id, item_id) {
            params.blocks[block_id].params_values.forEach((element, index) => {
                if (item_id === element.id) {
                    params.blocks[block_id].params_values.splice(index, 1);
                }
            });
        }
    }
});


let blocks = [
    {
        title: 'Query Params',
        curl_option: 'CURLOPT_URL'
    },
    {
        title: 'Headers',
        curl_option: 'CURLOPT_HTTPHEADER'
    },
    {
        title: 'Body form-data',
        curl_option: 'CURLOPT_POSTFIELDS'
    }
].map((data, index) => {
    return {
        block_id: index,
        title: data.title,
        curl: data.curl_option,
        params_values: []
    }
});


let params = new Vue({
    el: "#params",
    data: {
        blocks: blocks
    },
    methods: {
        add_param_value: function (block_id) {
            this.blocks.forEach((element, index) => {
                if (element.block_id === block_id) {
                    this.blocks[index].params_values.push({ id: this.blocks[index].params_values.length });
                }
            });
        }
    }
});


let form = new Vue({
    el: "#form",
    data: {
        button_disabled: false,
        url: "",
        method: "GET"
    },
    methods: {
        send_form: async function () {

            output.url = output.info = output.result = output.error = "";

            this.button_disabled = true;

            let request_params = [];

            let blocks = document.getElementsByClassName('request_params');
            for (let block_i = 0; block_i < blocks.length; block_i++) {

                let name = blocks[block_i].getAttribute('name');
                let params = blocks[block_i].getElementsByClassName('param');
                let values = blocks[block_i].getElementsByClassName('value');

                let request_block = {
                    name: name,
                    params: {}
                };

                for (let param_i = 0; param_i < params.length; param_i++) {

                    let param = params[param_i].value;
                    let value = values[param_i].value;

                    if (param !== '' && value !== '') {
                        request_block.params[param.toString().trim()] = value.trim();
                    }

                }

                if (Object.keys(request_block.params).length > 0) {
                    request_params.push(request_block);
                }

            }

            let response = await fetch('/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    url: this.url,
                    method: this.method,
                    request_params: request_params
                })
            });

            if (response.ok) {
                let result = await response.text();

                try {
                    result = JSON.parse(result);

                    output.url = result.url;
                    output.info = result.info;

                    if (result.error && result.error !== '') {
                        output.error = result.error;
                    }

                    if (result.result && result.result !== '') {
                        output.result = result.result;
                    }
                }
                catch (error) {
                    output.error = error + result;
                }


            } else {
                output.error = response.status;
            }

            this.button_disabled = false;
        }
    }
});


let output = new Vue({
    el: "#output",
    data: {
        url: "",
        error: "",
        info: "",
        result: ""
    }
});
