<template>
    <div class="p-2 bg-white m-5 rounded-lg">
        <h1 class="text-gray-400">Member</h1>
        <table class="table-fixed w-full">
            <thead>
                <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">氏名</th>
                <th class="px-4 py-2 lg:table-cell hidden"></th>
                <th class="px-4 py-2 lg:table-cell hidden">開催数</th>
                <th class="px-4 py-2 lg:table-cell hidden">参加率</th>
                <th class="px-4 py-2 lg:table-cell hidden">最終参加日</th>
                <th class="px-4 py-2 lg:table-cell hidden">加入日</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(member, index) in member_list">
                    <td class="border px-4 py-2">{{member.id}}</td>
                    <td class="border px-4 py-2">{{member.name}}</td>
                    <td class="border px-4 py-2 lg:table-cell hidden">{{member.join_number}}</td>
                    <td class="border px-4 py-2 lg:table-cell hidden">{{member.total_practice}}</td>
                    <td class="border px-4 py-2 lg:table-cell hidden">{{member.join_parcent}}%</td>
                    <td class="border px-4 py-2 lg:table-cell hidden">{{member.last_join}}</td>
                    <td class="border px-4 py-2 lg:table-cell hidden">{{member.join_at}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    module.exports = {
        data(){
            return{
                member_list: null,
                load: false
            }
        },

        mounted: async function(){
            this.print_log("メンバー取得中", "info", true)

            const res = await axios.post("./member/index");

            if(res.data == "error"){
                this.print_log("メンバー取得失敗", "error", false)
            }else{
                this.print_log("メンバー取得完了", "success", false)
                this.member_list = res.data;
            }

            //this.load = true;
        },

        methods:{
            print_log:function(msg, type, option){
                this.$emit("log", msg, type, option)
            }
        }
    }
</script>
