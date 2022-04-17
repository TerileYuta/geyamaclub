<template>
    <div class="p-3 lg:w-1/2 bg-white m-5 mx-auto rounded-lg w-full">
        <h1 class="text-gray-400">記録</h1>
        <div class="my-2">
            <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                種類
            </label>
            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" v-model="type" value="臨時" type="text" disabled>
        </div>
        <div class="my-2">
            <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                名前
            </label>
            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" v-model="name" type="text">
        </div>
        <div class="my-2">
            <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                収益
            </label>
            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" v-model="action" type="number">
        </div>
        <input type="button" @click="decision" class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" value="決定">
    </div>
</template>

<script>
    module.exports = {
        data(){
            return {
                type: "臨時",
                name: "",
                action: "",
            }
        },

        methods: {
            decision:async function(){
                const params = new URLSearchParams()
                params.append("type", this.type)
                params.append("name", this.name)
                params.append("action", this.action)

                const res = await axios.post("./payment/settlement", params)

                console.log(res.data)

                if(res.data = "200"){
                    alert("OK")
                }
            }
        }
    }
</script>
