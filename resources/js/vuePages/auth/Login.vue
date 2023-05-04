<script setup>
import axios from "axios";
import Swal from 'sweetalert2';
import { ref , onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from '../../store.js'

const authStore = useAuthStore();

const router = useRouter()


const formData={
    email:'',
    password:'',
}
function submitLogin(){
    axios.post('/api/login',{
        email : formData.email,
        password : formData.password,
    })
    .then(function (response) {
        // handle success
        // console.log(response.data.token)
        if(response.data.code == 200){

            authStore.token=response.data.token;
            console.log(authStore.token)
            localStorage.setItem('access_token', authStore.token)
            authStore.login();

            Swal.fire({
            title: 'با موفقیت وارد شدید',
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: 'حله',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    router.push({name:'home.product.index'})
                }
            })
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
</script>

<template>

<div class="container mt-4 p-4 border rounded-5">
    <h1>ورود</h1>
    <!-- <p>token : {{token}}</p> -->
    <div class="col-md-5">
        <div class="input-group my-2">
            <span class="input-group-text">ایمیل</span>
            <input v-model.lazy="formData.email" name="email" type="email" aria-label="email" class="form-control">
        </div>
        <div class="input-group my-2">
            <span class="input-group-text">رمز عبور</span>
            <input v-model.lazy="formData.password" name="password" type="password" aria-label="password" class="form-control">
        </div>


        <button @click.prevent="submitLogin" class="btn my-4 btn-primary">ورود</button>
    </div>

</div>

</template>

<style scoped>

</style>
