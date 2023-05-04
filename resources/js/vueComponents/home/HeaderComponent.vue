<script setup>
import axios from "axios";
import {ref} from "vue";
import Swal from 'sweetalert2';
import { onMounted } from 'vue';
import { useRouter } from "vue-router";
import { useAuthStore } from '../../store.js'

const authStore = useAuthStore();

const router = useRouter()

if(localStorage.getItem('access_token')){

    axios.post('/api/check',
    { yes : 'yeees'}, //data
    {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + localStorage.getItem('access_token')
    }
  })
    .then(function (response) {

        console.log(response.data)
        authStore.token = localStorage.getItem('access_token')
        authStore.login();
    })
    .catch(function (error) {
        // handle error
        console.log('user is not logged in');
        authStore.logout()
        authStore.token = null
    })
    .finally(function () {
        // always executed
    });
}
// console.log()

function logout(){
    axios.post('/api/logout',
    { yes : 'yeees'}, //data
    {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + authStore.token
    }
  })
    .then(function (response) {
        // handle success
        localStorage.removeItem('access_token')
        authStore.logout()
        Swal.fire('با موفقیت خارج شدید')
        router.push({name:'home.product.index'})
    })
    .catch(function (error) {
        // handle error
        console.log(error);
    })
    .finally(function () {
        // always executed
    });
}
</script>

<template>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">PayStar Test</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item mx-2">
                <router-link :to="{name:'home.product.index'}" class="nav-link">صفحه نخست</router-link>
                </li>

                <li v-if="authStore.userLoggedIn" class="nav-item mx-2">
                <router-link  :to="{name:'profile'}" class="nav-link bg-success rounded text-white">پروفایل</router-link>
                </li>

                <li v-if="!authStore.userLoggedIn" class="nav-item mx-2">
                    <router-link :to="{name:'register'}" class="nav-link">ثبت نام</router-link>
                </li>

                <li v-if="!authStore.userLoggedIn" class="nav-item mx-2">
                    <router-link :to="{name:'login'}" class="nav-link">ورود</router-link>
                </li>


                <li v-else class="nav-item mx-2">
                    <button @click="logout" class="nav-link">خروج از حساب کاربری</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<router-view></router-view>

</template>

<style scoped>
.router-link-active{
    color:black !important;
    border-bottom: 2px solid black !important;
}
</style>
