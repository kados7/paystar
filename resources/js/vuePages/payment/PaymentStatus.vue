<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();

// const paymentStatus = ref(false)
</script>

<template>

<div class="container mt-4 p-4 border rounded-5">
    <div class="row g-4">
        <h5>وضعیت تراکنش : </h5>
    </div>
    <!-- <router-link :to="{name: 'payment.failed', params: { id:2 } }">Failed</router-link>
    <router-link :to="{name: 'payment.success', params: { id:2 } }">success</router-link> -->
    <router-view></router-view>


</div>

</template>

<style scoped>

</style>
