<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="float-start">{{ title }}</h5>
                        </div>
                        <!-- <div class="col-md-4 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search user..">
                        </div> -->
                        <!-- <div class="col-md-6">
                            <button @click="createUser"  class="btn-info btn-sm float-end">New User</button>
                        </div> -->
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">General Setting</h3>
                        </div>

                        <form @submit.prevent="updateSettings()">
                            <div class="card-body">
                                <div class="form-group my-3">
                                    <label for="appName">Approval Name 1</label>
                                    <input v-model="settings.approval1" type="text" class="form-control" id="appName"
                                        placeholder="Enter approval name">
                                    <span class="text-danger text-sm" v-if="errors && errors.approval1">{{ errors.approval1[0] }}</span>
                                </div>
                                <div class="form-group my-3">
                                    <label for="appName">Approval Name 2</label>
                                    <input v-model="settings.approval2" type="text" class="form-control" id="appName"
                                        placeholder="Enter approval name">
                                    <span class="text-danger text-sm" v-if="errors && errors.approval2">{{ errors.approval2[0] }}</span>
                                </div>
                                <div class="form-group my-3">
                                    <label for="appName">Approval Name 3</label>
                                    <input v-model="settings.approval3" type="text" class="form-control" id="appName"
                                        placeholder="Enter approval name">
                                    <span class="text-danger text-sm" v-if="errors && errors.approval3">{{ errors.approval3[0] }}</span>
                                </div>
                               
                               
                                <!-- <div class="form-group">
                                    <label for="paginationLimit">To</label>
                                    <input v-model="settings.to" type="text" class="form-control" id="paginationLimit"
                                        placeholder="Enter To">
                                    <span class="text-danger text-sm" v-if="errors && errors.to">{{ errors.to[0] }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="paginationLimit">Daily Email List (CC)</label>
                                    <textarea rows="6" v-model="settings.dailyemail" type="text" class="form-control" id="dailyemail"
                                        placeholder="Email List"/>
                                    <span class="text-danger text-sm" v-if="errors && errors.dailyemail">{{ errors.dailyemail[0] }}</span>
                                </div> -->
                            </div>

                            <div class="card-footer my-2">
                                <button type="submit" class="btn btn-primary">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <h5 class="modal-title" id="userModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Camera': 'Update User' }} </h5>
                    <h5 class="modal-title" id="userModalLabel" v-show="deleteMode" > Delete User </h5>
                    <h5 class="modal-title" id="userModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Name</label>
                                <input type="text" class="form-control" v-model="userData.name" disabled>
                                <span class="text-danger" v-show="userErrors.name">Name is required</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color" >Department</label>
                                <input type="text" class="form-control" v-model="userData.department_id">
                                <span class="text-danger" v-show="userErrors.role">Role is required</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color" >Role</label>
                                <input type="text" class="form-control" v-model="userData.role_id">
                                <span class="text-danger" v-show="userErrors.role">Role is required</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color" >Password</label>
                                <input type="password" class="form-control" v-model="userData.password">
                                <span class="text-danger" v-show="userErrors.password">Pass is required</span>
                            </div>
                        </div>
                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeUser(): updateUser()" >{{!editMode ? 'Create User': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteUser" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewUser" >Renew</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="attachModal"  tabindex="-1" aria-labelledby="attachModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="attachModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Attach photo': 'Update Room' }} </h5>
               
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" >Attachment</label>
                                <input type="hidden" v-model="userData.id">
                                <input type="file" class="form-control" @change="selectedImage" >               
                               

                                
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group image">
                                    <img :src="'/'+userData.oldphoto" v-if="userData.oldphoto && !userData.photo"/>
                                    <img :src="userData.photo" v-if="userData.photo" class="image"/>
                                </div>
                            
                        </div>

                    </div>
                  







                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storePhoto(): updateComplaint()" >{{!editMode ? 'Save': 'Save Changes' }}</button>
                </div>
              
            </div>
        </div>
    </div>

</template>

<script>
import Department from "./department.vue";

export default {
    components: {Department},
    setup: () => ({
        title: 'Settings'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            userData: {
            department_id:'',
            role_id: '',
            password: '',
            },
            userErrors: {
                reg_no: false,
                img: false,
                color: false,
            },
            users: {},
            current_user: {},
            settings: {
              
            },
            errors: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getUsers();
        }
    },
    mounted(){
        this.getUsers()
        this.getSettings()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getSettings(){
            axios.get('/api/getSettings')
            .then((response) => {
                this.settings = response.data;
            });
        },
        updateSettings(){
            axios.post('/api/settings', this.settings)
                .then((response) => {
                    toastr.success('Settings updated successfully!');
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        errors.value = error.response.data.errors;
                    }
                });
        },
        getUsers(){

            axios.get('api/getUsers',{ params: { keyword: this.keyword } }).then(response=>{
                this.users = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeUser(user){
            this.deleteMode = true
            this.userData.id = user.id
            $('#userModal').modal('show')
        },
        deleteUser(){
            axios.post(window.url + 'api/deleteUser/' + this.userData.id).then(response => {
                this.getUsers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#userModal').modal('hide')
            });
        },
        renew(user){
            this.renewMode = true
            this.deleteMode = false
            this.editMode = false

            this.userData= {
                id : user.id,


            }
            $('#userModal').modal('show')

        },
        editUser(user){
            this.editMode = true
            this.deleteMode= false
            this.userData= {
                id : user.id,
                name :user.name,
                department_id: user.department_id,
                role_id : user.role_id,
                password : user.password,
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


            // axios({
            // method:'post',
            // url:'api/updateUser/' + this.userData.id,
            // responseType:'arraybuffer',
            // data: 'test'
            // })
            // .then(function(response) {
            //     let blob = new Blob([response.data], { type:   'application/pdf' } );
            //     let link = document.createElement('a');
            //     link.href = window.URL.createObjectURL(blob);
            //     link.download = 'Report.pdf';
            //     link.click();
            // });

        },
        attached(user){
         
         
            this.userData= {
                id : user.id,
                name :user.name,
               
            }
       
         $('#attachModal').modal('show')
         
        
     },
    
     storePhoto(){

         axios.post(window.url + 'api/storePhoto' ,this.userData).then(response=>{
                     $('#attachModal').modal('hide');
           
       
                     this.getUsers()
        
             }).catch(error =>this.errors = error.response.data.errors)


     },
        renewUser(){

            axios.post(window.url + 'api/renewUser/' + this.userData.id, this.userData).then(response => {
                this.getUsers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#userModal').modal('hide')
            });

        },
        createUser(){
            this.editMode = false
            this.deleteMode = false
            this.userData= {
                id: '',
                reg_no: '',
                img: '',
                color: '',
                type: '',
                brand: '',
                model: '',

            }
            this.userErrors= {
                reg_no: false,
                img: false,
                color: false,
            }
            $('#userModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.userData.photo = reader.result;
            }
            reader.readAsDataURL(file);
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
<style>
.tooltip-text {
  visibility: hidden;
  position: absolute;
  top: 0;
  right: 105%;
  z-index: 1;

  
  color: white;
  font-size: 11px;  
  background-color: #192733;
  opacity: 70%;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
}

.hover-text:hover .tooltip-text {
   
  visibility: visible;
  
}
.owner {
    font-weight: bolder;
    
}
.pending {
    color: red;
}
.done {
    color: green;
}
.inProgress {
    color: orange;
}
.project {
    color: blue;

}
.assign {
    color: violet;

}
.closed {
    color:dimgrey;
}
.fullWidthImage {
 /* width: 800px !important;
  height: 600px !important; */
  object-fit: cover !important;
width: 40% !important;
height: auto !important;
  display: flex;
  position: absolute;
}

.image {
    float: left;
    padding: 2px;
  height: 60px;
  width: 65px;
  position: relative;

 
}
.image .cross {
    color: black;
    position: absolute;
    top: 0;
    right: 0;
}


</style>
