<template>
    <div>
        <div class="lg:flex p-2 lg:m-5 m-2 rounded-lg lg:h-16 h-40">
            <div class="flex-1 text-center p-1 bg-white rounded-lg m-1 cursor-pointer flex lg:bg-gradient-to-r lg:from-green-400 lg:to-white bg-green-400" @click="currentView = 'info'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-bar-chart-line w-6 m-2 text-white" viewBox="0 0 16 16">
                    <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
                </svg>
                <h1 class="text-2xl font-bold text-white">統計</h1>
            </div>
            <div class="flex-1 text-center p-1 bg-white rounded-lg m-1 cursor-pointer flex lg:bg-gradient-to-r lg:from-blue-400 lg:to-white bg-blue-400" @click="currentView = 'log'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-list w-6 m-2 text-white" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <h1 class="text-2xl font-bold text-white">決済履歴</h1>
            </div>
            <div class="flex-1 text-center p-1 bg-white rounded-lg m-1 cursor-pointer flex lg:bg-gradient-to-r lg:from-pink-400 lg:to-white bg-pink-400" @click="currentView = 'record'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-pencil w-6 m-2 text-white" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                </svg>
                <h1 class="text-2xl font-bold text-white">記録</h1>
            </div>
        </div>
        <component :date_id="this.date_id" :payment="this.payment" :latest_id="this.latest_id" :is="currentView "></component>
    </div>
</template>


<script>
module.exports = {
        data(){
            return {
                currentView: null,
                payment: null,
                latest_id: null,
                date_id: null,

            }
        },

        components:{
            log: httpVueLoader("./Payment/Log.vue"),
            record: httpVueLoader("./Payment/Record.vue"),
            info: httpVueLoader("./Payment/Info.vue")
        },

        mounted:async function(){
            const res = await axios.post("./payment/index");

            this.payment = res.data[0]
            this.latest_id = res.data[1]
            this.date_id = res.data[2]

            this.currentView = "info"
        }
    }
</script>
