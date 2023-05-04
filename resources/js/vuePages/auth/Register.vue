<script setup>
import axios from "axios";
import Swal from 'sweetalert2';
import { useRouter } from "vue-router";
import { useAuthStore } from '../../store.js'

const authStore = useAuthStore();

const router = useRouter()
const formData={
    name:'',
    email:'',
    password:'',
    password_c:'',
}
function submitRegister(){
    axios.post('/api/register',{
        name : formData.name,
        email : formData.email,
        password : formData.password,
        password_c : formData.password_c,
    })
    .then(function (response) {
        // handle success
        console.log(response.data);
        if(response.data.code == 200){
            authStore.token=response.data.token;
            console.log(authStore.token)
            localStorage.setItem('access_token', authStore.token)
            authStore.login();

            Swal.fire({
            title: 'کاربر جدید ساخته شد',
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
    <h1>ثبت نام</h1>
    <div class="col-md-5">
        <div class="input-group my-2">
            <span class="input-group-text">نام</span>
            <input v-model.lazy="formData.name" name="name" type="text" aria-label="First name" class="form-control">
        </div>
        <div class="input-group my-2">
            <span class="input-group-text">ایمیل</span>
            <input v-model.lazy="formData.email" name="email" type="email" aria-label="email" class="form-control">
        </div>
        <div class="input-group my-2">
            <span class="input-group-text">رمز عبور</span>
            <input v-model.lazy="formData.password" name="password" type="password" aria-label="password" class="form-control">
        </div>
        <div class="input-group my-2">
            <span class="input-group-text">تکرار رمز عبور</span>
            <input v-model.lazy="formData.password_c" name="password_c" type="password" aria-label="password" class="form-control">
        </div>

        <button @click.prevent="submitRegister" class="btn my-4 btn-primary">ثبت نام</button>
    </div>

</div>

</template>

<style scoped>

</style>
