<template>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-xl font-bold mb-6">{{ auth.user.name }}'s Panel</h2>
            <div v-if="auth.role === 'User'">
                <nav class="space-y-2">
                    <router-link to="/user" class="nav-link mr-2">Dashboard</router-link>
                    <router-link to="/user/post" class="nav-link mr-2">Posts</router-link>
                    <button @click="logout" class="nav-link logout mr-2">Logout</button>
                </nav>
            </div>
            <div v-else>
                <nav class="space-y-2">
                    <router-link to="/admin" class="nav-link mr-2">Dashboard</router-link>
                    <router-link to="/admin/post" class="nav-link mr-2">Posts</router-link>
                    <router-link to="/admin/category" class="nav-link mr-2">Categories</router-link>
                    <router-link to="/admin/comment" class="nav-link mr-2">Moderate Comments</router-link>
                    <router-link to="/admin/user" class="nav-link mr-2">Users</router-link>
                    <button @click="logout" class="nav-link logout">Logout</button>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-100 p-6">
            <router-view />
        </main>
    </div>
</template>

<script setup>
import api from '../api/axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../store/auth'

const router = useRouter()
const auth = useAuthStore()

const logout = async () => {
    await auth.logout(router)
}
</script>

<style scoped>
.nav-link {
    padding: 10px;
    color: rgb(1, 57, 102);
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.nav-link:hover {
    background-color: #4f46e5;
}
.logout {
    background-color: #dc2626;
    color: white;
}
.logout:hover {
    background-color: #b91c1c;
}
.mr-2 {
    margin-right: 8px;
}
</style>
