<template>
    <div class="m-5">
        <div class="lg:flex p-2 bg-white">
            <div class="lg:w-1/3">                 
                <div class="h-32 m-2">
                    <h1 class="text-gray-600">残高</h1>
                    <h1 class="text-6xl m-2 font-bold" :class="{'text-green-400': (balance > 0), 'text-red-400': (balance < 0)}">￥{{balance}}</h1>
                </div>
                <hr>
                <div class="m-3 h-64 overflow-y-auto overflow-x-hidden">
                    <table class="table-auto w-full m-2 h-full">
                        <thead>
                            <tr>
                                <th class="ox-4 py-2 font-bold">年月</th>
                                <th class="ox-4 py-2 font-bold">収益</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(val,index) in balance_list">
                                <td class="px-4 py-2 border">{{date_list[index]}}</td>
                                <td class="px-4 py-2 border"  :class="{'text-green-400': (val > 0), 'text-red-400': (val < 0)}">{{val}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex m-3">
                    <h1 class="flex-1 text-xl">平均月間収益</h1>
                    <h1 class="flex-1 text-xl font-bold" :class="{'text-green-400': (ave_balance > 0), 'text-red-400': (ave_balance < 0)}">￥{{ave_balance}}</h1>
                </div>
            </div>
            <div class="lg:w-2/3 lg:block hidden">
                <canvas id="balance_chart"></canvas>
            </div>
        </div>
        <div class="inline-block relative w-40 mt-3">
            <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" @change="range_change" v-model="range">
                <option v-for="option in month_list" :value="`${option[0]}-${option[1]}`">{{option[0]}}年{{option[1]}}月</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
        <div class="lg:flex">
            <div class="flex-1 p-2 bg-white mr-3 mt-3">
                <h1 class="w-12 text-xl text-gray-600">収入</h1>
                <div class="lg:flex">
                    <div class="lg:w-1/3 w-full">
                        <h1 class="lg:text-6xl text-3xl m-2 text-green-400 font-bold">￥{{total_income}}</h1>
                        <div class="m-3">
                            <h1 class="text-base m-2" v-for="type in income_type">{{type.name}} : ￥{{type.val}} ({{Math.round((type.val / total_income) * 100)}} %)</h1>
                        </div>
                    </div>
                    <div class="lg:w-2/3 w-full">
                        <canvas id="income_chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="flex-1 p-2 bg-white lg:ml-3 mt-3">
                <h1 class="w-12 text-xl text-gray-600">支出</h1>
                <div class="lg:flex">
                    <div class="lg:w-1/3 w-full">
                        <h1 class="lg:text-6xl text-3xl m-2 text-red-400 font-bold">￥{{total_spending}}</h1>
                        <div class="m-3">
                            <h1 class="text-base m-2" v-for="type in spending_type">{{type.name}} : ￥{{type.val}} ({{Math.round((type.val / total_spending) * 100)}} %)</h1>
                        </div>
                    </div>
                    <div class="lg:w-2/3 w-full">
                        <canvas id="spending_chart"></canvas>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: {
            "date_id":{},
            "payment":{},
            "latest_id": {}
        },

        data(){
            return{
                month_list: [],
                balance_list: [],
                date_list: [],

                income_chart: null,
                spending_chart: null,
                balance_chart: null,

                total_income: null,
                income_type: null,
                total_spending: null,
                spending_type: null,

                balance: 0,
                ave_balance: 0,
                range: "---"
            }
        },

        mounted: function(){
            //Create month selecter
            month_list = []

            let month_from = new Date(2021, 7, 1)
            let month_to = new Date()
            month_to.setMonth(month_to.getMonth())

            month_list.push(["-", "-"])
            month_list.push([month_from.getFullYear(), "-"])

            //Set year
            while(month_from.getFullYear() <= month_to.getFullYear()){
                month_list.push([month_from.getFullYear(), "-"])

                year = month_from.getFullYear() + 1
                month_from.setYear(year)
            }

            //Set month
            Object.keys(this.date_id).forEach(key => {
                let key_list =  key.split("-")
                month_list.push([key_list[0], key_list[1]])

                let format_date = `${key_list[0]}-${key_list[1]}`

                this.date_list.push(format_date)
                this.balance_list.push(this.get_total(format_date, "+")[0] - this.get_total(format_date, "-")[0])
            })

            //Convert to option
            this.month_list = month_list

            //Set chart
            this.income_chart = new Chart(document.getElementById("income_chart"),{
                type: "pie",
                data: {
                    datasets: [{
                    data: []
                    }],
                    labels: []
                }
            })

            this.spending_chart = new Chart(document.getElementById("spending_chart"),{
                type: "pie",
                data: {
                    datasets: [{
                    data: []
                    }],
                    labels: []
                }
            })

            this.range_change();
            
            //Blance of each month
            this.balance = this.payment[this.latest_id]["balance"]

            balance_each_month = 0
            
            this.balance_list.forEach(val =>{
                balance_each_month += val
            })

            this.ave_balance = Math.round(balance_each_month / this.balance_list.length)
            
            this.balance_list.reverse()
            this.date_list.reverse()

            this.balance_chart = new Chart(document.getElementById("balance_chart"),{
            type: "line",
            data: {
                labels: this.date_list,
                datasets: [{
                label: "残高",
                data: this.balance_list,
                lineTension: 0,
                fill: false,
                },
                {
                label: "0",
                data: Array(this.date_list.length).fill(0),
                lineTension: 0,
                borderColor: "#68d391",
                backgroundColor: "#68d391",
                pointRadius: 0,
                borderWidth: 0.75,
                fill: false,
                },
                {
                label: "月間平均値",
                data: Array(this.date_list.length).fill(this.ave_balance),
                lineTension: 0,
                borderColor: "#fc8181",
                backgroundColor: "#fc8181",
                pointRadius: 0,
                borderWidth: 0.75,
                fill: false,
                }],
            },
            options:{
                scales:{
                yAxes: [{
                    ticks:{
                    min: -50000
                    }
                }]
                },
            }
            })
        },

        methods: {
            range_change: function(){
                get_income_tatal = this.get_total(this.range, "+")

                this.total_income  = get_income_tatal[0]
                this.income_type = get_income_tatal[1]

                let income_data = []
                let income_label = []

                this.income_type.forEach(data =>{
                    income_data.push(data.val)
                    income_label.push(data.name)
                })

                //Update income hart
                this.income_chart.data.datasets[0].data = income_data
                this.income_chart.data.labels = income_label
                this.income_chart.update()

                get_spending_tatal = this.get_total(this.range, "-")

                this.total_spending  = get_spending_tatal[0]
                this.spending_type = get_spending_tatal[1]

                let spending_data = []
                let spending_label = []

                this.spending_type.forEach(data =>{
                    spending_data.push(data.val)
                    spending_label.push(data.name)
                })

                //Update income hart
                this.spending_chart.data.datasets[0].data = spending_data
                this.spending_chart.data.labels = spending_label
                this.spending_chart.update()

            },

            //Get balance total of month
            get_total: function(value, positive){
                value_list = value.split("-")

                total = 0
                request_month_list = []
                action_type = []
                
                //All range
                if(value == "---"){
                    Object.keys(this.date_id).forEach(key => {
                        request_month_list.push(this.date_id[key])
                    })

                    request_month_list = request_month_list.flat()
                //Year
                }else if(value_list[1] == ""){
                    Object.keys(this.date_id).forEach(key => {
                        if(key.substr(0,4) == value_list[0]){
                            request_month_list.push(this.date_id[key])
                        }
                    })

                    request_month_list = request_month_list.flat()
                //Month
                }else{
                    request_month_list = this.date_id[value]
                }

                request_month_list.forEach(id => {
                    action = this.payment[id]["action"]
                    if(action[0] == positive){
                        total += parseInt(action.substr(1))
                        if(action_type[this.payment[id]["name"]]){
                            action_type[this.payment[id]["name"]] += parseInt(action.substr(1))
                        }else{
                            action_type[this.payment[id]["name"]] = parseInt(action.substr(1))
                        }
                    }
                })

                output = []

                Object.keys(action_type).forEach(key => {
                    output.push({"name":key, "val": action_type[key]})
                })

                return [total, output]
            },

            
        }
    }
</script>
