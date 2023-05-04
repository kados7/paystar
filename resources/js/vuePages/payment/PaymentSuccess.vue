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
        console.log(response.data);
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


        <h1 class="text-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
            </svg>
            پرداخت موفق
        </h1>
        <div class="my-2">
            <table class="table table-primary table-striped">
                <thead>
                  <tr>
                    <th scope="col">شماره تراکنش </th>
                    <th scope="col">شماره فاکتور </th>
                    <th scope="col">شماره کارت انتخابی شما </th>
                    <th scope="col">شماره کارتی که با آن تراکنش را انجام دادید </th>
                    <th scope="col">شماره پیگیری بانکی </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{transaction.transaction_id}}</td>
                    <td>{{transaction.receipt}}</td>
                    <td>{{ cart.number }}</td>
                    <td>{{ transaction.card_number }}</td>
                    <td>{{ transaction.tracking_code }}</td>
                  </tr>
                </tbody>
              </table>

        </div>
        <button @click="goToHome" class="btn btn-sm btn-primary my-5">بازگشت به خانه</button>



</template>

<style scoped>

</style>
