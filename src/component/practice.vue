<template>
    <div>
        <div class="flex w-full">
            <div class="w-16 h-16 rounded-full m-2 pt-2 pl-1 bg-white cursor-pointer" @click="back">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="M3.86 8.753l5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"></path>
                </svg>
            </div>
            <div class="flex-1 h-16 bg-white p-3 text-center rounded-full m-2">
                <h1 class="font-bold text-xs lg:text-3xl">{{title}}-{{practice_date}}({{practice_time}})</h1>
            </div>
            <div class="w-16 h-16 rounded-full m-2 pt-2 pl-1 bg-white cursor-pointer" @click="next">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path>
                </svg>
            </div>
        </div>

        <div class="w-full flex flex-wrap rounded-lg">
            <div class="flex-1 h-48 p-2 bg-white m-5 flex">
                <div class="w-1/5 lg:m-6 m-3 mt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-people w-full text-green-400" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path>
                    </svg>
                </div>
                <div class="mt-8">
                    <h1 class="text-gray-600">参加人数</h1>
                    <h1 class="text-5xl font-bold m-1">{{member_number}}</h1>
                    <h1 class="text-2xl m-1">({{member_parcent}}%)</h1>
                </div>
            </div>
            <div class="flex-1 h-48 p-2 bg-white m-5 rounded-lg flex">
                <div class="w-1/5 lg:m-6 m-3 mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-coin w-full text-purple-400" viewBox="0 0 16 16">
                        <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"></path>
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-gray-600 mt-8">練習収益</h1>
                    <div class="w-full flex">
                        <div class="flex-1 lg:block hidden">
                            <h1 class="text-2xl m-2 text-green-400">↑￥{{income}}</h1>
                            <h1 class="text-2xl m-2 text-red-400">↓￥{{spending}}</h1>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-5xl m-3 font-bold" :class="{'text-green-500': balance > 0, 'text-red-500': balance < 0}">￥{{balance}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 h-48 p-2 bg-white m-5 rounded-lg flex" :class="{'hidden': settlement_id > 0}">
                <div class="w-1/5 lg:m-6 m-0 mt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-currency-dollar w-full text-indigo-400" viewBox="0 0 16 16">
                        <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-gray-600 mt-8">全体収益 ({{total_balance}} + ({{balance}}))</h1>
                    <h1 class="text-5xl font-bold m-3" :class="{'text-green-500': (total_balance + balance) > 0, 'text-red-500': (total_balance + balance) < 0}">￥{{total_balance + balance}}</h1>
                </div>
            </div>
        </div>


        <div class="w-full lg:flex flex-wrap">
            <div class="flex-1 p-2 bg-white m-5 rounded-lg">
                <h1 class="text-gray-700 m-2">コントローラー</h1>
                <div class="flex w-full m-2">
                    <div class="flex-1 m-1">
                        <h1 class="text-xl mx-1 font-bold">募集状況</h1>
                    </div>
                    <div class="inline-block relative w-full flex-1 m-1">
                        <select class="block appearance-none w-full bg-white border hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" :disabled="(settlement_id > 0)" :class="{'border-green-400': recruitment_status, 'border-yellow-400': !recruitment_status}" :value="recruitment_status" v-model="recruitment_status" @change="status_change">
                            <option value="0">未募集</option>
                            <option value="1">募集中</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="w-full m-2">
                    <h1 class="text-xl m-1 font-bold">収入</h1>
                </div>
                <div class="lg:flex w-full m-2">
                    <div class="flex-1">
                        <h1 class="text-xl m-1">参加費</h1>
                    </div>
                    <div class="flex-1 m-1">
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj control" :readonly="(settlement_id > 0)" :value="fee" v-model.number="fee" @input="update_balance" type="number">
                        <nobr class="text-xl m-1">円</nobr>
                    </div>
                </div>
                <div class="lg:flex w-full m-2">
                    <div class="flex-1">
                        <h1 class="text-xl m-1">その他</h1>
                    </div>
                    <div class="flex-1 m-1">
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj control" :value="income_other" v-model.number="income_other" @input="update_balance" :readonly="(settlement_id > 0)" type="number">
                        <nobr class="text-xl m-1">円</nobr>
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj my-2 w-64 lg:w-full control" :value="income_other_memo" v-model="income_other_memo" type="text" :readonly="(settlement_id > 0)" placeholder="メモ">
                    </div>
                </div>
                <div class="w-full m-2">
                    <h1 class="text-xl m-1 font-bold">支出</h1>
                </div>
                <div class="lg:flex w-full m-2">
                    <div class="flex-1">
                        <h1 class="text-xl m-1">体育館代</h1>
                    </div>
                    <div class="flex-1 m-1">
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 spending_obj control" :readonly="(settlement_id > 0)" :value="gym" v-model.number="gym" @input="update_balance" type="number">
                        <nobr class="text-xl m-1">円</nobr>
                    </div>
                </div>
                <div class="lg:flex w-full m-2">
                    <div class="flex-1">
                        <h1 class="text-xl m-1">シャトル代</h1>
                    </div>
                    <div class="flex-1 m-1">
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 spending_obj control" :value="shattle" v-model.number="shattle" @input="update_balance" :readonly="(settlement_id > 0)" type="number">
                        <nobr class="text-xl m-1">円</nobr>
                    </div>
                </div>
                <div class="lg:flex w-full m-2">
                    <div class="flex-1">
                        <h1 class="text-xl m-1">その他</h1>
                    </div>
                    <div class="flex-1 m-1">
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 spending_obj control" :value="other" v-model.number="other" @input="update_balance" :readonly="(settlement_id > 0)" type="number">
                        <nobr class="text-xl m-1">円</nobr>
                        <input class="bg-gray-200 w-full lg:w-64 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj my-2 w-64 lg:w-full control" :value="other_memo" :readonly="(settlement_id > 0)" v-model="other_memo" type="text" placeholder="メモ">
                    </div>
                </div>
                <div class="lg:flex w-full m-2">
                    <div class="flex-1 lg:block hidden">
                        <h1 class="text-xl m-1 font-bold">決済</h1>
                    </div>
                    <div class="flex-1">
                        <input type="button" class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full w-full cursor-pointer" :class="{'hidden': settlement_id > 0}" @click="settlement_click" value="決定" :disabled="(settlement_id > 0)">
                        <h1 class="text-xl m-1 font-bold" :class="{'hidden': settlement_id == 0}">決済番号：{{settlement_id}}</h1>
                    </div>
                </div>
            </div>

            <div class="flex-1 h-full p-2 bg-white m-5 rounded-lg">
                <h1 class="text-gray-700">メモ</h1>
                <textarea class="h-64 w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 mt-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="text" placeholder="メモは入力されていません" :value="memo" v-model="memo"></textarea>
                <div class="text-right">
                    <input type="button" class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full cursor-pointer" @click="save_memo" value="保存">
                </div>
            </div>
        </div>
        <div class="p-2 bg-white m-5 rounded-lg">
            <h1 class="text-gray-700">参加者リスト</h1>
            <div class="m-3">
                <input class="bg-gray-200 text-gray-700 border border-gray-200 rounded-full py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 income_obj my-2 w-64 control"  type="text" list="name_list" placeholder="メンバー追加" v-model="new_member" @input="member_name" :readonly="(settlement_id > 0)">
                <datalist id="name_list">
                    <option v-for="(member, index) in new_member_list" :value="member.id">{{member.name}}</option>
                </datalist>
                <input type="button" class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full cursor-pointer" @click="add_member" value="追加" :disabled="(settlement_id > 0)">
                <input type="button" class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full cursor-pointer" @click="delete_member" value="削除" :disabled="(settlement_id > 0)">
            </div>
            <table class="table-fixed w-full text-center">
                <thead>
                    <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">氏名</th>
                    <th class="px-4 py-2 lg:table-cell hidden">参加回数</th>
                    <th class="px-4 py-2 lg:table-cell hidden">開催数</th>
                    <th class="px-4 py-2 lg:table-cell hidden">参加率</th>
                    <th class="px-4 py-2 lg:table-cell hidden">最終参加日</th>
                    <th class="px-4 py-2 lg:table-cell hidden">加入日</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(member, index) in member_list">
                        <th class="px-4 py-2">{{member.id}}</th>
                        <th class="px-4 py-2">{{member.name}}</th>
                        <th class="px-4 py-2 lg:table-cell hidden">{{member.attenance}}</th>
                        <th class="px-4 py-2 lg:table-cell hidden">{{member.total_practice}}</th>
                        <th class="px-4 py-2 lg:table-cell hidden">{{member.attendance_parcent}}</th>
                        <th class="px-4 py-2 lg:table-cell hidden">{{member.last_entry}}</th>
                        <th class="px-4 py-2 lg:table-cell hidden">{{member.join_at}}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data(){
            return {
                info: "null",
                plan_date: null,
                balance: null,
                total_balance: null,
                all_member_number: null,
                practice_date: null,
                practice_time: null,
                practice_date_pos: null,

                settlement_id: null,
                settlement_btn_obj_flag: true,

                plan: null,
                member_list: {id:"1"},
                title: "",
                practice_id: 0,
                settlement_id: 0,

                fee: 0,
                income_other: 0,
                income: 0,
                income_other_memo: "",

                gym: 0,
                shattle: 0,
                other: 0,
                spending: 0,
                other_memo: "",

                member_number: 0,
                member_parcent: 0,

                memo: "",

                new_member: "",
                new_member_list: [],

                recruitment_status: false,
            }
        },

        //Get practice plan
        mounted:async function(){
            const res = await axios.post("./practice/index")
            
            this.plan_date = res.data.plan_date
            this.total_balance = res.data.balance
            this.all_member_number = res.data.all_member_number

            this.start_up();
        },

        methods:{
            start_up:function(){
                //Get the date closest to today
                var today_time = new Date().getTime()
                var dis = 0;
                
                this.plan_date.forEach(date =>{
                    var date_time = new Date(date).getTime();

                    if(date_time > today_time){
                        if((dis >= Math.abs(today_time - date_time)) || (dis == 0)){
                            this.practice_date = date;
                            dis = Math.abs(today_time - date_time);
                        }
                    }
                });

                if(!this.practice_date){
                    this.practice_date = this.plan_date[this.plan_date.length - 1];
                }

                //Sort
                this.plan_date.sort((a, b) =>{
                    return (a > b ? 1 : -1)
                });

                //Set practice date position
                this.practice_date_pos = this.plan_date.indexOf(this.practice_date);

                this.get_data()
            },

            //Get practice data
            get_data:async function(){
                const params = new URLSearchParams()
                params.append("date", this.practice_date)

                const res = await axios.post("./practice/get_data", params)

                this.plan = res.data

                this.update_data()
            },

            //Update data
            update_data: function(){
                plan = this.plan

                this.member_list = plan.member
                this.title = plan.title
                this.settlement_id = plan.settlement_id
                this.practice_id = plan.id
                this.practice_time = plan.time

                 //Member
                this.member_number = this.member_list.length
                this.member_parcent = Math.round((this.member_number / this.all_member_number) * 100) || 0

                //Income
                this.fee = plan.fee
                this.income_other = plan.income_other
                this.income = (this.member_number * this.fee) + this.income_other
                
                //Spending
                this.gym = plan.gym
                this.shattle = plan.shattle
                this.other = plan.other
                this.spending = this.gym + this.shattle + this.other

                //Balance
                this.balance = this.income - this.spending
                
            
                //Memo
                this.memo = plan.memo

                //Recruitment
                this.recruitment_status = plan.status
            },

            back:function(){
                if(this.plan_date.length >= this.practice_date_pos - 2){
                    this.practice_date_pos -= 1;
                    this.practice_date = this.plan_date[this.practice_date_pos];
                    this.get_data()
                }else{
                    alert("エラー")
                }
            },

            next:function(){
                if(this.plan_date.length >= this.practice_date_pos + 2){
                    this.practice_date_pos += 1;
                    this.practice_date = this.plan_date[this.practice_date_pos];
                    this.get_data();
                }else{
                    alert("エラー")
                }
            },

            status_change:async function(){
                let text = "";

                if(this.recruitment_status){
                    text = `${this.practice_date}の練習の参加を募集し始めました。`
                }else{
                    text = `${this.practice_date}の練習の参加を募集を終了しました。`
                }

                const params = new URLSearchParams()
                params.append("message", text)
                params.append("status", this.recruitment_status)
                params.append("id", this.practice_id)

                const res = await axios.post("https://ss1.xrea.com/geyamaclub.s203.xrea.com/geyamaclub/push_message.php", params)

                console.log(res)
            },

            settlement_click:async function(){
                fee_income = this.fee * this.member_number

                const params = new URLSearchParams()
                params.append("title", this.title)
                params.append("fee", fee_income)
                params.append("date", this.practice_date)
                params.append("practice_id", this.practice_id)
                params.append("income_other", this.income_other)
                params.append("income_other_memo", this.income_othrt_memo)
                params.append("gym", this.gym)
                params.append("shattle", this.shattle)
                params.append("other", this.other)
                params.append("other_memo", this.other_memo)

                const res = await axios.post("./practice/settlement", params)

                this.settlement_id = res.data;
            },

            update_balance: function(){
                //Member parcent
                this.member_parcent = Math.round((this.member_number / this.all_member_number) * 100) || 0
                
                //Income
                this.income = (this.member_number * this.fee) + this.income_other
                
                //Spending
                this.spending = this.gym + this.shattle + this.other

                //Balance
                this.balance = this.income - this.spending                
            },

            save_memo:async function(){
                const params = new URLSearchParams()
                params.append("memo", this.memo)
                params.append("practice_id", this.practice_id)

                const res = await axios.post("./practice/save_memo", params)

                if(res.data == "200"){
                    alert("Success")
                }
            },

            member_name: async function(){
                const params = new URLSearchParams()
                params.append("name", this.new_member)

                const res = await axios.post("./practice/new_member", params)

                this.new_member_list = res.data
            },

            add_member: async function(){
                const params = new URLSearchParams()
                params.append("user_id", this.new_member)
                params.append("id", this.practice_id)

                const res = await axios.post("./practice/add_member", params)
                
                this.new_member = ""
                this.get_data()
            },

            delete_member: async function(){
                const params = new URLSearchParams()
                params.append("user_id", this.new_member)
                params.append("id", this.practice_id)

                const res = await axios.post("./practice/delete_member", params)
                
                this.new_member = ""
                this.get_data()
            }
        }
    }
</script>
