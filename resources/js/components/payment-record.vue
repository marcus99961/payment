<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="float-start">{{ title }}</h5>
                        </div>
                       <div class="col-md-4 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search payment..">
                        </div>
                        <div class="col-md-4">

                                <button @click="report" v-if="current_user.role_id==2"  class="btn-info btn-sm float-end mx-2">Report by Date</button>

                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_payment.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>PY No.</th>
                                <th>From</th>
                                <th>Supplier</th>
                                <th class="digit">Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(payment, index) in payments " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>

                                <td>{{payment.payment_date}}</td>
                                <td>{{payment.py_no}}</td>
                                <td>{{payment.name}}</td>
                                <td><div v-if="payment.supplier.length<19">{{payment.supplier}}</div>
                                    <div class="myDIV" v-else >{{ payment.supplier.substring(0,19)+".." }}</div>
                                    <span class="hide">{{ payment.supplier }}</span>
                                </td>
                                <td class="digit">{{payment.currency}} {{payment.amount}}</td>

                                <td>


                                    <button @click="form(payment)" class="btn btn-primary btn-sm mx-1">Form</button>


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
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header btn-primary">
                    <h5 class="modal-title" id="paymentModalLabel" v-show="!deleteMode && !paidMode"> {{!editMode ? 'Add New Payment': 'Update Payment' }} </h5>
                    <h5 class="modal-title" id="paymentModalLabel" v-show="deleteMode" > Delete Payment </h5>
                    <h5 class="modal-title" id="paymentModalLabel" v-show="paidMode" >Paid Payment </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !paidMode">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="title" >Date</label>
                                <input type="date" class="form-control" v-model="paymentData.payment_date" >
                                <!--                                <span class="text-danger" v-show="paymentErrors.name">Name is required</span>-->
                                <span class="text-danger" v-if="errors.payment_date"> {{ errors.payment_date[0] }} </span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="title" >Supplier</label>
                                <input type="text" class="form-control" v-model="paymentData.supplier" >
                                <span class="text-danger" v-if="errors.supplier"> {{ errors.supplier[0] }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-show="!deleteMode && !paidMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Currency</label>

                                <v-select :options="['USD', 'MMK']" v-model="paymentData.currency"></v-select>
                                <span class="text-danger" v-if="errors.currency"> {{ errors.currency[0] }} </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Amount</label>
                                <input type="text" class="form-control" v-model="paymentData.amount" >
                                <span class="text-danger" v-if="errors.amount"> {{ errors.amount[0] }} </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Commercial Tax</label>
                                <input type="text" class="form-control" v-model="paymentData.ct" >
                                <span class="text-danger" v-if="errors.ct"> {{ errors.ct[0] }} </span>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3" v-show="!deleteMode && !paidMode">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title" >Descriptions</label>
                                <input type="text" class="form-control" v-model="paymentData.description" >
                                <span class="text-danger" v-show="paymentErrors.name">Name is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-show="!deleteMode && !paidMode">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title" >Beneficiary/AC Name</label>
                                <input type="text" class="form-control" v-model="paymentData.ac_name" >
                                <span class="text-danger" v-show="paymentErrors.name">Name is required</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Settlement</label>

                                <v-select :options="['Cash', 'Cheque', 'BKTR']" v-model="paymentData.settle_by"></v-select>
                                <span class="text-danger" v-if="errors.settle_by"> {{ errors.settle_by[0] }} </span>
                            </div>
                        </div>
                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>
                    <h4 class="text-center" v-show="paidMode">Are you sure want to change status as Paid?</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !paidMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storePayment(): updatePayment()" >{{!editMode ? 'Create Payment': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deletePayment" >Delete</button>
                </div>
                <div class="modal-footer" v-show="paidMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="paidPayment" >Paid</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report by Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title" >Report From</label>
                        <input type="date" class="form-control" v-model="reportData.from_date">
                        <label for="title" >To</label>
                        <input type="date" class="form-control" v-model="reportData.to_date">




                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="reportPayment()">Generate</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'Paid Payments'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            paidMode:false,
            keyword: null,

            paymentData: {
                name: '',
                phone: '',
                shift: '',

            },

            paymentErrors: {
                name: false,
                phone: false,

            },
            reportData: {

            },
            payments: {},
            current_user: {},
            errors:{},
        }
    },
    watch: {
        keyword(after, before) {
            this.getPayments();
        }
    },
    mounted(){
        this.getPayments()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getPayments(){

            axios.get('api/getPaids',{ params: { keyword: this.keyword } }).then(response=>{
                this.payments = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removePayment(payment){
            this.deleteMode = true
            this.paymentData.id = payment.id
            $('#paymentModal').modal('show')
        },
        form(payment){
            this.paymentData.id = payment.id
            axios({
                method:'post',
                url:'/api/paymentForm/' + this.paymentData.id,
                responseType:'arraybuffer',

            })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'Report.pdf';
                    link.click();
                });

        },

        deletePayment(){
            axios.post( 'api/deletePayment/' + this.paymentData.id).then(response => {
                this.getPayments()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#paymentModal').modal('hide')
            });
        },
        paid(payment){
            this.paidMode = true
            this.deleteMode = false
            this.editMode = false

            this.paymentData= {
                id : payment.id,


            }
            $('#paymentModal').modal('show')

        },
        editPayment(payment){
            this.editMode = true
            this.deleteMode= false
            this.paymentData= {
                id : payment.id,
                payment_date :payment.payment_date,
                supplier : payment.supplier,
                currency : payment.currency,
                amount : payment.amount,
                ct : payment.ct,
                ac_name : payment.ac_name,
                description : payment.description,
                settle_by : payment.settle_by,
            }
            this.paymentErrors= {
                name: false,
                phone: false,

            }
            $('#paymentModal').modal('show')
        },
        updatePayment(){



            axios.post('api/updatePayment/' + this.paymentData.id, this.paymentData).then(response => {
                this.getPayments()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#paymentModal').modal('hide')
            });


            // axios({
            // method:'post',
            // url:'api/updatePayment/' + this.paymentData.id,
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
        paidPayment(){

            axios.post( 'api/paidPayment/' + this.paymentData.id).then(response => {
                this.getPayments()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#paymentModal').modal('hide')
            });

        },
        createPayment(){
            this.editMode = false
            this.deleteMode = false
            this.paymentData= {



            }
            this.paymentErrors= {
                name: false,


            }
            $('#paymentModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.paymentData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        report(){

            this.reportData={
                from_date:'',
                to_date: '',
            }



            $('#reportModal').modal('show')
        },
        reportPayment(){

            axios({
                method:'post',
                url:'/api/reportPayment',
                responseType:'arraybuffer',
                data: this.reportData
            })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'Reportpayment.pdf';
                    link.click();
                    $('#reportModal').modal('hide')


                });
        },
        resetInput(){
            this.report.from_date = '';
            this.report.to_date = '';
        },
        storePayment(){



            axios.post('api/storePayment', this.paymentData).then(() => {
                $('#paymentModal').modal('hide');
                this.getPayments()

            }).catch(error =>this.errors = error.response.data.errors)




        }

    }

}
</script>
<style>
.hide {
    display: none;
    font-size: 11px;
}

.myDIV:hover + .hide {
    display: block;
    color: blue;
}
.digit{
    text-align: right;
}
</style>
