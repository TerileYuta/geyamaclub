<template>
    <div class="bg-white m-3 p-2 rounded-lg">    
        <h1 class="text-gray-400">決済履歴</h1>
        <table class="table-fixed w-full text-center">
            <thead>
                <tr>
                <th class="px-4 py-2 ">ID</th>
                <th class="px-4 py-2 ">名前</th>
                <th class="px-4 py-2 lg:table-cell hidden">種類</th>
                <th class="px-4 py-2 cursor-pointer">決済</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">収益</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">メモ</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">練習ID</th>
                <th class="px-4 py-2 cursor-pointer lg:table-cell hidden">決済日時</th>
                </tr>
            </thead>
            <tbody>                    
                <tr v-for="data  in payment_list.reverse()">
                    <td class="border border-gray-400 px-4 py-2">{{data.id}}</td>
                    <td class="border border-gray-400 px-4 py-2">{{data.name}}</td>
                    <td class="border border-gray-400 px-4 py-2 lg:table-cell hidden">{{data.type}}</td>
                    <td class="border border-gray-400 px-4 py-2" :class="{'text-red-700': (data.balance < 0), 'text-green-700': (data.balance > 0)}">{{data.balance}}</td>
                    <td class="border border-gray-400 px-4 py-2 lg:table-cell hidden" :class="{'text-red-700': (data.action < 0), 'text-green-700': (data.action > 0)}">{{data.action}}</td>
                    <td class="border border-gray-400 px-4 py-2 lg:table-cell hidden">{{data.memo}}</td>
                    <td class="border border-gray-400 px-4 py-2 lg:table-cell hidden">{{data.practice_id}}</td>
                    <td class="border border-gray-400 px-4 py-2 lg:table-cell hidden">{{data.insert_at}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    module.exports = {
        props: ['payment'],
        data(){
            return{
                payment_list: [],
            }
        },

        mounted: function(){
            Object.keys(this.payment).forEach(key =>{
                this.payment_list.push(this.payment[key])
            })
        }
    }
</script>
