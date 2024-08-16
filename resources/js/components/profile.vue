<template>
   <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">Change Display Name: {{ current_user.name }}</div>

                <div class="card-body">
                    <form method="POST" >
                     

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right mt-3">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control mt-3" name="name" required autofocus v-model="userData.name">
                                <span class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </span>
                            </div>
                        </div>

                        <div class="mb-0 form-group row">
                            <div class="col-md-6 offset-md-4 mt-3">
                                <button type="button" @click="storeName()" class="btn btn-primary">
                                   Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Change Password</div>

                <div class="card-body">
                    <form method="POST" >
                     

                        <div class="form-group row">
                            <span class="text-success" v-if="success"> Successfully updated! </span>
                            <label for="current_password" class="col-md-4 col-form-label text-md-right mt-3">Current Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control mt-3" name="current_password" required autofocus v-model="userData.current_password">
                                <span class="text-danger" v-if="errors.current_password"> {{ errors.current_password[0] }} </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right mt-3">New Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control mt-3" name="password" required autocomplete="new-password" v-model="userData.password">
                                <span class="text-danger" v-if="errors.password"> {{ errors.password[0] }} </span>
                             
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right mt-3">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control mt-3" name="password_confirmation" required autocomplete="new-password" v-model="userData.password_confirmation">
                            </div>
                        </div>

                        <div class="mb-0 form-group row">
                            <div class="col-md-6 offset-md-4 mt-3">
                                <button type="button" @click="storePassword()" class="btn btn-primary">
                                   Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    setup: () => ({
        title: 'All Users'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
            success: false,

            userData: {
            name: '',
            current_password: '',
            new_password: '',
            password_confirmation: '',
            },
            userErrors: {
                reg_no: false,
                img: false,
                color: false,
            },
            users: {},
            errors:{},
            current_user: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getUsers();
        }
    },
    mounted(){
        this.getUsers()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getUsers(){

            axios.get('api/getUsers',{ params: { keyword: this.keyword } }).then(response=>{
                this.users = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        
       
        editUser(user){
            this.editMode = true
            this.deleteMode= false
            this.userData= {
                id : user.id,
                name :user.name,
                role_id : user.role_id,
            }
            this.userErrors= {
                name: false,
                model: false,
                color: false,
            }
            $('#userModal').modal('show')
        },
        updateUser(){



            axios.post(window.url + 'api/updateUser/' + this.userData.id, this.userData).then(response => {
                this.getUsers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#userModal').modal('hide')
            });



        },
        storePassword(){
        axios.post('api/updatepassword', this.userData)
       .then(() => {
       
         this.resetInput()
        this.success = true;
       })
       .catch(error =>this.errors = error.response.data.errors)

     },
     storeName(){
        axios.post('api/updatename', this.userData)
       .then(() => {
       
         this.resetInput()
         window.location.reload()
        // Notification.success()
       })
       .catch(error =>this.errors = error.response.data.errors)

     },
     resetInput(){
        this.userData.current_password= '';
        this.userData.password= '';
        this.userData.password_confirmation= '';
        this.userData.name = '';   




     },



       
       
        storeUser(){
            this.userData.reg_no=='' ? this.userErrors.reg_no = true: this.userErrors.reg_no = false
            this.userData.model=='' ? this.userErrors.model = true: this.userErrors.model = false
            this.userData.brand=='' ? this.userErrors.brand = true: this.userErrors.brand = false

            if(this.userData.reg_no && this.userData.model && this.userData.brand){
                axios.post('api/storeUser', this.userData).then(response=>{
                    this.getUsers()
                }).catch(errors=>{
                    console.log(errors)
                }).finally(()=>{
                    $('#userModal').modal('hide')
                });


            }
        }
    }

}
</script>
