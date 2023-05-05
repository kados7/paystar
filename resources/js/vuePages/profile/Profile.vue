<script setup>
import axios from "axios";
import {ref} from "vue";
import Swal from 'sweetalert2';
import { onMounted } from 'vue';
import { useAuthStore } from '../../store.js'

const authStore = useAuthStore();
console.log(authStore.name);

const carts = ref()
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
        console.log(response.data)
        if(response.data.code == 200){
            Swal.fire('کارت افزوده شد')
            getUserCart()
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
const mainLoading = ref(true);
const products =ref([]);
function getUserProducts(){
    axios.post('/api/v1/getUserProducts',
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
            products.value=response.data.products;
            mainLoading.value=false
        }
        if(response.data.code == 401){
            mainLoading.value=false

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
getUserProducts();

</script>

<template>

<div class="container mt-4 p-4 border rounded-5" style="background: #f3f3f3">
    <div class="row">
        <h1 class="mb-4">پروفایل</h1>

        <div class="col-md-6 border p-3">
            <h5 class="text-danger">شماره کارت های شما</h5>
                <table class="table table-info table-striped">
                    <thead>
                        <tr>
                        <th scope="col">نام کارت</th>
                        <th scope="col">شماره کارت</th>
                        <th scope="col">بانک</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cart in carts" :key="cart.id" class="my-3">
                        <td>{{ cart.name }}</td>
                        <td>{{ cart.number }}</td>
                        <td>{{ cart.bank }}</td>
                        </tr>
                    </tbody>
                </table>

            <hr>
            <div>
                <h5 class="text-danger">افزودن کارت جدید</h5>
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

        <div class="col-md-6 border p-3">
            <h5 clas="text-success my-4">محصولات خریداری شده</h5>
            <div class="row">

                <div v-if="mainLoading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div v-else v-for="product in products" :key="product.id" class="col-md-4">
                    <div class="card">
                        <img src="/img/default.png" class="card-img-top" style="width:80%">
                        <div class="card-body">
                            <p class="card-text">{{product.name}}</p>
                            <router-link :to="{ name: 'home.product.show' , params:{ id: product.id } }" class="btn btn-dark">دیدن محصول</router-link>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</template>

<style scoped>

</style>
