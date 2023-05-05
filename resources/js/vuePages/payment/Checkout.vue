<script setup>

import axios from 'axios'
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../../store.js'
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const route= useRoute();
const router=useRouter();

function backToProductIndex(){
    router.push({name:'home.product.show', params: {id: route.params.id}})
}

const carts = ref();
function getUserCart(){
    axios.post('/api/v1/getUserCart',
    { yes : 'yeees'}, //data
    {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + authStore.token
    }
  })
    .then(function (response) {
        console.log(response.data)
        if(response.data.code == 200){
            carts.value = response.data.carts
        }
        // localStorage.removeItem('access_token')
        // Swal.fire('با موفقیت خارج شدید')

    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .finally(function () {
        // always executed
    });
}
getUserCart()

const selectedCart= reactive({info : 0})

const formData={
    name:'',
    number:'',
    bank:'',
}

function submitNewCart(){
    axios.post('/api/v1/addUserCart',{
        name : formData.name,
        number : formData.number,
        bank : formData.bank,
    },
    {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + authStore.token
        }
    }
    )
    .then(function (response) {
        // handle success
        // console.log(response.data)
        if(response.data.code == 200){
            Swal.fire('کارت افزوده شد')
            getUserCart()
            selectedCart.info=response.data.cart

        }
        if(response.data.code == 401){
            Swal.fire(response.data.message)
        }


    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .finally(function () {
        // always executed
    });
}

const product = reactive({info:0})

function getProducts(){
    axios.get(`/api/v1/products/${route.params.id}`)
    .then(function (response) {
        // handle success
        product.info=response.data.product;
        console.log(product.info)
    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .finally(function () {
        // always executed
    });
}

getProducts()

const bankToken = ref()
const payment_amount = ref()
const paymentConfirm = ref(false)

function payment(){
    if(selectedCart.info == 0){
        Swal.fire('ابتدا باید یک کارت را انتخاب کنید')
    }

    else{
        axios.post('/api/v1/goToPayment',{
            product_id : product.info.id,
            cart_id : selectedCart.info.id,
            price : product.info.price,
        }, {
            headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + authStore.token
            }
        })
        .then(function (response) {
            // handle success
            if(response.data.code == 200){
                console.log(response.data)
                bankToken.value= response.data.response.data.token;
                payment_amount.value= response.data.response.data.payment_amount;
                paymentConfirm.value = true;


            }
            if(response.data.code == 401){
                Swal.fire(response.data.data.message)
            }

        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
    }
}
</script>

<template>

<div class="row p-5">
    <div class="d-flex flex-row justify-content-start mb-2">
        <button @click="backToProductIndex" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            </svg>
            برگشت به صفحه محصول
        </button>
    </div>
    <h5 class="text-black mb-3">پیش فاکتور خرید : </h5>



    <div class="col-md-6 my-2">
        <h5 class="text-black mb-3">اطلاعات پرداخت</h5>
        <p style="font-size : 18px">نام محصول : <span class="text-danger">{{product.info.name}}</span></p>
        <p style="font-size : 18px"> قیمت : <span class="text-danger">{{product.info.price}} ریال </span></p>

        <div class="alert alert-danger" role="alert">
            <strong> شماره کارت انتخابی</strong> شما باید با کارتی که توسط آن در درگاه بانک، پرداخت انجام میشود یکی باشد.
        </div>
        <p class="my-2">شماره کارت انتخابی شما : {{ selectedCart.info.number }}</p>
        <button v-if="!paymentConfirm" @click="payment" class="btn btn-danger">پرداخت</button>
        <form class="border p-2" v-else id="gotoBankForm" action="https://core.paystar.ir/api/pardakht/payment" method="post">
            <input name="token" type="hidden" :value="bankToken" >
            <p class="m-2 text-danger">
                مبلغ پرداختی نهایی : {{ payment_amount }} ریال
            </p>
            <button class="btn btn-success" type="submit">تایید و هدایت به صفحه پرداخت</button>
        </form>
    </div>



    <div class="col-md-6 my-2">
        <h5 class="text-black">انتخاب شماره کارت</h5>
        <table class="table table-info table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">شماره کارت</th>
                <th scope="col">بانک</th>
                </tr>
            </thead>
            <tbody>
                <tr class="my-3" v-for="cart in carts" :key="cart.id">
                    <td>
                        <div class="form-check form-check-inline">
                            <input v-model="selectedCart.info" :value="cart" class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1">
                        </div>
                    </td>
                    <td>
                        {{cart.number}}
                    </td>
                    <td>
                        {{cart.bank}}
                    </td>
                </tr>
            </tbody>
        </table>

        <hr>

        <p>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
              افزودن کارت جدید
            </button>
          </p>
          <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <div class="input-group my-2">
                    <span class="input-group-text">نام کارت</span>
                    <input v-model.lazy="formData.name" name="name" type="text" aria-label="email" class="form-control">
                </div>
                <div class="input-group my-2">
                    <span class="input-group-text">نام بانک</span>
                    <input v-model.lazy="formData.bank" name="bank" type="text" aria-label="email" class="form-control">
                </div>
                <div class="input-group my-2">
                    <span class="input-group-text">شماره کارت (16 رقم)</span>
                    <input v-model.lazy="formData.number" name="number" type="number" aria-label="email" class="form-control">
                </div>
                <button @click="submitNewCart" class="btn my-4 btn-primary">ذخیره</button>
            </div>
          </div>

    </div>



</div>



</template>

<style scoped>

</style>
