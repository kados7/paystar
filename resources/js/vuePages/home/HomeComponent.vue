<script setup>

import axios from 'axios'
import { ref } from 'vue';
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

</script>

<template>

<div class="container mt-4 p-4 border rounded-5" style="background: #f3f3f3">
    <div class="row g-4">
        <!-- <h1>HomeComponent</h1> -->
        <router-view></router-view>

    </div>


</div>

</template>

<style scoped>

</style>
