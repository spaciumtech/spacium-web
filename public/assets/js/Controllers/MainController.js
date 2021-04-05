var master_api = 'https://spacium.co/wp-json/wp/v2/';
function onSubmit(token){
    if(!token) return false;

    ContactController.onSubmit(token);
}
var ContactController = new Vue({
    el: '#contact-form',
    delimiters: ['${', '}'],
    data: {
        error:'',
        success:'',
        processing:false,
        form_data: {
            name:'',
            phone:'',
            email:'',
            message:'',
        },
    },

    methods:{
        validateEmail:function(email) {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        },
        validate:function(){
            this.error = '';
            this.success = '';
            if(!this.form_data.name){
                this.error = 'Please enter your name!';
                return false;
            }
            if(!this.form_data.email){
                this.error = 'Please enter your email!';
                return false;
            }
            if(!this.validateEmail(this.form_data.email)){
                this.error = 'Please enter valid email address!';
                return false;
            }
            if(!this.form_data.phone){
                this.error = 'Please enter your phone!';
                return false;
            }
            if(this.form_data.phone.length > 10){
                this.error = 'Please enter your valid phone number!';
                return false;
            }
            if(!this.form_data.message){
                this.error = 'Please enter your message!';
                return false;
            }
            return true;
        },
        onSubmit:function(token){
            const self = this;
            this.form_data.token = token;
            self.error = '';
            self.success = '';
            if(!self.validate()){
                return false;
            }
            self.processing = true;
            axios.post('php/contact.php',this.form_data)
                .then((res) => {
                    self.processing = false;
                    if(res.response){
                        self.success = res.message;
                    } else {
                        self.error = res.message;
                    }
                })
                .catch((err) => console.log(err));
        }
    }
})


var BlogsController = new Vue({
    el: '#blogs',
    delimiters: ['${', '}'],
    data: {
        blogsLoading:false,
        blogs:[],
    },
    mounted: function () {
        this.$nextTick(function () {
           this.getLatestBlogs();
        })
    },
    methods:{
        getLatestBlogs:function(){
            const self = this;
            self.blogsLoading = true;
            axios.get(master_api+'posts', {
                params: {
                    per_page:3,
                    orderby:'date',
                    order:'desc',
                    _embed:true,
                }
            })
                .then((res) => {
                    console.log(res.data);
                    if(res.data.length > 0){
                        self.blogs = res.data;
                    }
                })
                .catch((err) => console.log(err))
                .finally(() => self.blogsLoading = false)
        }
    }
})
