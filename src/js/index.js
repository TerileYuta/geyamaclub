window.onload = () => {    
    ad_height = document.getElementById("ad").offsetHeight;
    document.getElementById("frame").style.height = (window.innerHeight - ad_height) + "px";
}

const routes = [
    {
        path: '/practice',
        component: httpVueLoader("./src/component/Practice.vue")
    },
    {
        path: '/plan',
        component: httpVueLoader("./src/component/Plan.vue")
    },
    {
        path: '/members',
        component: httpVueLoader("./src/component/Member.vue")
    },
    {
        path: '/payment',
        component: httpVueLoader("./src/component/Payment.vue")
    },
    {
        path: '/manual',
        component: httpVueLoader("./src/component/Manual.vue")
    },
];

const router = new VueRouter({
    routes
});

var app = new Vue({
    name: "practice",
    el: "#app",
    mode: 'history',
    router: router,

    data(){
        return{
            anime_active: false,
            anime_reverse_active: true,

            log_msg: null,
            log_type: null,
            log_option: false
        }
    },

    updated() {
        setTimeout(() => {
            if(this.log_option == false) {
                this.log_msg = ""
            }
            }
            ,3000
        )
    },

    methods: {
        menu:function(){
            this.anime_active = !this.anime_active;
            this.anime_reverse_active = !this.anime_reverse_active;

            document.querySelectorAll(".hide_object").forEach(e =>{
                e.classList.toggle("hidden");
            });
        },

        log:function(msg, log_type, option){
            this.log_msg = msg
            this.log_type = log_type
            this.log_option = option
            
        }
    }
});