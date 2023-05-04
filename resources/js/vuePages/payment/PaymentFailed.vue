<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
const route= useRoute();
const router = useRouter();

import { useAuthStore } from '../../store.js'
const authStore = useAuthStore();

const transaction =ref([]);
const cart =ref([]);

import axios from 'axios'
function getTransaction(){
    axios.get(`/api/v1/transaction/${route.params.TransactionId}`)
    .then(function (response) {
        // handle success
        // console.log(response.data);
        if(response.data.code == 200){
            transaction.value= response.data.transaction
            cart.value= response.data.cart
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

getTransaction()

function goToHome(){
    router.push({name:'home.product.index'})
}
</script>

<template>


        <h1 class="text-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
            </svg>
            پرداخت ناموفق
        </h1>
        <div class="my-2">
            <table class="table table-danger table-striped">
                <thead>
                  <tr>
                    <th scope="col">شماره تراکنش </th>
                    <th scope="col">شماره فاکتور </th>
                    <th scope="col">شماره کارت انتخابی شما </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{transaction.transaction_id}}</td>
                    <td>{{transaction.receipt}}</td>
                    <td>{{ cart.number }}</td>
                  </tr>
                </tbody>
              </table>
        </div>
        <button @click="goToHome" class="btn btn-sm btn-primary my-5">بازگشت به خانه</button>



</template>

<style scoped>

</style>
