<template>
    <div class="p-4 max-w-md mx-auto">
        <h2 class="text-xl font-bold mb-4">Login</h2>
        <form @submit.prevent="handleLogin">
            <input v-model="email" type="email" placeholder="Email" class="input" />
            <input v-model="password" type="password" placeholder="Password" class="input" />
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../store/auth'
import api from '../../api/axios'

const email = ref('')
const password = ref('')
const router = useRouter()
const auth = useAuthStore()

const handleLogin = async () => {
    try {
        await api.post('/auth/login', {
            email: email.value,
            password: password.value,
        }).then((response) => {
            if (response.status === 200) {
                auth.setUser(response?.data?.data?.user)
                auth.setToken(response?.data?.data[0]?.plainTextToken)
                auth.setRole(response?.data?.data?.user?.roles[0]?.name)
            }
        }).then(() => {
            if (auth.role === 'User') {
                router.push('/user')
            } else {
                router.push('/admin')
            }
        }).catch((error) => {
            console.log(error)
            // console.log(error.response.data.message)
            // alert(error.response.data.message)
        })

    } catch (err) {
        console.log(err)
        alert('Login failed.')
    }
}


</script>


<style scoped>
.input {
    display: block;
    margin: 8px 0;
    padding: 8px;
    width: 100%;
    border: 1px solid #ccc;
}

.btn {
    padding: 10px 16px;
    background: #333;
    color: #fff;
    border: none;
    cursor: pointer;
}
</style>
