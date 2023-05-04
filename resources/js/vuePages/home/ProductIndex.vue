<script setup>

import axios from 'axios'
import { ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();

const mainLoading = ref(true);
const products =ref([]);
function getProducts(){
    axios.get('/api/v1/products')
    .then(function (response) {
        // handle success
        products.value=response.data;
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

function goToProfile(){
    router.push({name:'profile'})
}
</script>

<template>

    <h1 class="mb-5">لیست محصولات</h1>

    <div v-if="mainLoading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div v-else v-for="product in products" :key="product.id" class="col-md-3">
        <div class="card">
        <img src="/img/default.png" class="card-img-top" style="width:80%">
            <div class="card-body">
                <p class="card-text">{{product.name}}</p>
                <router-link :to="{ name: 'home.product.show' , params:{ id: product.id } }" class="btn btn-dark">دیدن محصول</router-link>
            </div>
        </div>

    </div>

    <button @click="goToProfile" class="btn btn-primary">مشاهده محصولات خریداری شده</button>
</template>

<style scoped>

</style>
