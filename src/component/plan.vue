<template>
    <div class="md:flex bg-white h-full">
        <div class="flex md:w-2/3 w-full">
            <div id='calendar' class="w-full"></div>
        </div>
        <div class="lg:flex lg:w-1/3 w-full lg:m-3">
            <div class="w-full lg:m-2">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline m-1" :value="event_title" v-model="event_title" type="text" placeholder="イベントタイトル" value="通常練習">
                <h1 class="text-3xl m-1">{{event_start}}</h1>
                <div class="flex m-1 w-full">
                    <div class="flex-1">
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="start_time" type="time" value="18:30">
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl text-center">~</h1>
                    </div>
                    <div class="flex-1">
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="end_time" type="time" value="21:00">
                    </div>
                </div>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline m-1" type="text" placeholder="場所" value="中央小学校">
                <div class="flex w-full m-1">
                    <div class="flex-1 m-1">
                        <input type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer w-full" value="追加" @click="add_event">
                    </div>
                    <div class="flex-1 m-1">
                        <input type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded cursor-pointer w-full" value="削除" @click="delete_event">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    module.exports = {
        data(){
            return {
                plan_list: [],
                date_list: [],
                event_title: "",
                event_start: "",
                start_time: "",
                end_time: "",
                date: "",
                calendar: null,
                practice_id: null,
            }
        },

        mounted:async function(){
            const res = await axios.post("./plan/index")

            this.plan_list = res.data[0]
            this.date_list = res.data[1]

            var calendarEl = document.getElementById('calendar');
            this.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                selectable: true,
                nowIndicator: true,
                eventClick: info => {
                    let click_title = info.event.title;
                    let click_start = info.event.start;

                    this.date = this.format_date(click_start);
                    this.event_title = click_title;
                    this.event_start = click_start.getFullYear() + "年" + (parseInt(click_start.getMonth()) + 1) + "月" + click_start.getDate() + "日";

                    let time = this.plan_list[this.date_list[this.format_date(click_start)]]["time"].split("-")
                    console.log(time)                   
                    this.start_time = time[0]
                    this.end_time = time[1]

                    this.practice_id = this.plan_list[this.date_list[this.format_date(click_start)]]["practice_id"]
                },
                dateClick:info => {
                    let d = new Date(info.dateStr)
                    this.date = this.format_date(d)
                    this.event_title = "通常練習"
                    this.event_start = `NEW:${d.getFullYear()}年${parseInt(d.getMonth()) + 1}月${d.getDate()}日`
                    this.place = "中央小学校"
                    this.start_time = "18:30"
                    this.end_time = "21:00"
                },
                buttonText: {
                    prev:     '<',
                    next:     '>',
                    prevYear: '<<',
                    nextYear: '>>',
                    today:    '今日',
                    month:    '月',
                    week:     '週',
                    day:      '日',
                    list:     '一覧'
                },
                events: this.plan_list
            });

            this.calendar.render();
        },
        
        methods: {
            format_date: function(dt) {
                var y = dt.getFullYear()
                var m = ('00' + (dt.getMonth()+1)).slice(-2)
                var d = ('00' + dt.getDate()).slice(-2)

                return (y + '-' + m + '-' + d)
            },

            add_event: async function(){
                const params = new URLSearchParams()
                params.append("title", this.event_title)
                params.append("date", this.date)
                params.append("start_time", this.start_time)
                params.append("end_time", this.end_time);
                params.append("place", this.place);

                const res = await axios.post("./plan/add_event", params)
                
                this.calendar.addEvent({id: this.date, title : this.event_title, start: this.date});
                this.calendar.render();
            },

            delete_event : async function(){
                const params = new URLSearchParams()
                params.append("id", this.practice_id)

                const res = await axios.post("./plan/delete_event", params)

                this.calendar.getEventById(this.date).remove();
                this.calendar.render();
            }
        },
    }

</script>
