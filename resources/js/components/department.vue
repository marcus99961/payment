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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search department..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createDepartment"  class="btn-info btn-sm float-end">New Department</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_department.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(department, index) in departments " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>

                                <td>{{department.name}}</td>


                                <td>
                                    <button @click="editDepartment(department)" class="btn btn-primary btn-sm mx-1">Edit</button>
                                    <button @click="removeDepartment(department)" class="btn btn-danger btn-sm mx-1">Delete</button>
                                    <!--      <button @click="renew(department)" class="btn btn-danger btn-sm mx-1">Renew</button> -->

                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="departmentModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Department': 'Update Department' }} </h5>
                    <h5 class="modal-title" id="departmentModalLabel" v-show="deleteMode" > Delete Department </h5>
                    <h5 class="modal-title" id="departmentModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Name</label>
                                <input type="text" class="form-control" v-model="departmentData.name" >
                                <span class="text-danger" v-show="departmentErrors.name">Name is required</span>
                            </div>
                        </div>


                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeDepartment(): updateDepartment()" >{{!editMode ? 'Create Department': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteDepartment" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewDepartment" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'All Departments'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            departmentData: {
                name: '',
                phone: '',
                shift: '',

            },
            departmentErrors: {
                name: false,
                phone: false,

            },
            departments: {},
            current_user: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getDepartments();
        }
    },
    mounted(){
        this.getDepartments()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getDepartments(){

            axios.get('api/getDepartments',{ params: { keyword: this.keyword } }).then(response=>{
                this.departments = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeDepartment(department){
            this.deleteMode = true
            this.departmentData.id = department.id
            $('#departmentModal').modal('show')
        },
        deleteDepartment(){
            axios.post( 'api/deleteDepartment/' + this.departmentData.id).then(response => {
                this.getDepartments()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#departmentModal').modal('hide')
            });
        },
        renew(department){
            this.renewMode = true
            this.deleteMode = false
            this.editMode = false

            this.departmentData= {
                id : department.id,
                renew: department.renew,

            }
            $('#departmentModal').modal('show')

        },
        editDepartment(department){
            this.editMode = true
            this.deleteMode= false
            this.departmentData= {
                id : department.id,
                name :department.name,
                phone : department.phone,
                shift : department.shift,
            }
            this.departmentErrors= {
                name: false,
                phone: false,

            }
            $('#departmentModal').modal('show')
        },
        updateDepartment(){



            axios.post(window.url + 'api/updateDepartment/' + this.departmentData.id, this.departmentData).then(response => {
                this.getDepartments()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#departmentModal').modal('hide')
            });


            // axios({
            // method:'post',
            // url:'api/updateDepartment/' + this.departmentData.id,
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
        renewDepartment(){

            axios.post(window.url + 'api/renewDepartment/' + this.departmentData.id, this.departmentData).then(response => {
                this.getDepartments()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#departmentModal').modal('hide')
            });

        },
        createDepartment(){
            this.editMode = false
            this.deleteMode = false
            this.departmentData= {
                id: '',
                name: '',


            }
            this.departmentErrors= {
                name: false,


            }
            $('#departmentModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.departmentData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeDepartment(){
            this.departmentData.name=='' ? this.departmentErrors.name = true: this.departmentErrors.name= false



            if(this.departmentData.name){
                axios.post('api/storeDepartment', this.departmentData).then(response=>{
                    this.getDepartments()
                }).catch(errors=>{
                    console.log(errors)
                }).finally(()=>{
                    $('#departmentModal').modal('hide')
                });


            }
        }
    }

}
</script>
