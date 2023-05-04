<script setup>

import axios from 'axios'
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../../store.js'

const authStore = useAuthStore();

const mainLoading = ref(true);
const product =ref([]);
const route= useRoute();
const router=useRouter();
function getProducts(){
    axios.get(`/api/v1/products/${route.params.id}`)
    .then(function (response) {
        // handle success
        // console.log(response.data.product);
        product.value=response.data.product;
        mainLoading.value=false;
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

const isPurchased = ref(false);
function isProductPurchased(){

    axios.post(`/api/v1/products/${route.params.id}/isPurchased`,
    { yes : 'yeees'}, //data
    {
    headers: {
      'Authorization': 'Bearer ' + authStore.token,
    }
    })
    .then(function (response) {
        console.log(response.data);
        if(response.data){
            isPurchased.value = true;
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
isProductPurchased()

function backToProductIndex(){
    router.push({name:'home.product.index'})
}
function goToCheckout(){
    router.push({name:'payment.checkout', params: {id: route.params.id}})
}

</script>

<template>
<div class="row">
    <div class="d-flex flex-row justify-content-start my-4">
        <button @click="backToProductIndex" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            </svg>
            برگشت به لیست محصولات

        </button>
    </div>
    <div class="col-md-4 justify-contents-center">
        <h5>نام : <span class="text-danger">{{product.name}}</span></h5>
        <h5>قیمت : <span class="text-success">{{product.price}} ریال </span></h5>
        <button @click="goToCheckout" v-if="!isPurchased" class="btn btn-lg btn-primary mx-auto my-3">خرید محصول</button>
        <div v-else class="my-5 bg-success rounded p-4">
            <h5 class="text-white bg-black rounded p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                </svg>
                شما محصول را خریداری کرده اید
            </h5>
            <button class="btn btn-lg btn-primary mx-auto my-3">
                مثلا دانلود محصول
            </button>
        </div>

    </div>
    <div class="col-md-8">
        <p>توضیح کوتاه : {{product.excerpt}}</p>
    </div>
</div>



</template>

<style scoped>

</style>
