<template>
    <nav class="bg-gray-900 text-white p-4 flex justify-between items-center">
        <router-link to="/" class="font-bold text-lg">MiniBlog</router-link>
        <hr>
        <div>
            <template v-if="!auth.token">
                <router-link to="/login" class="btn ">Login</router-link>
                <router-link to="/register" class="btn ml-2">Register</router-link>
            </template>
            <template v-else>
                <div v-if="route.path == '/'">
                    <button @click="goProfile" class="btn ">Profile</button>
                    <button @click="logout" class="btn ml-2">Logout</button>
                </div>
                <div v-else>
                    <router-link to="/" class="btn ">Home</router-link>
                </div>
            </template>
        </div>
    </nav>
</template>

<script setup>
import { useAuthStore } from '../store/auth'
import { useRoute, useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute();

const logout = async () => {
    await auth.logout(router)
}
const goProfile = () => {
    if (auth.token) {
        if (auth.role == 'User') {
            router.push('user')
        } else {
            router.push('admin')
        }
    }
}
</script>

<style scoped>
.btn {
    background: #4f46e5;
    padding: 6px 12px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}
.btn:hover {
    background: #4338ca;
}
.btn.ml-2 {
    margin-left: 8px;
}
</style>
