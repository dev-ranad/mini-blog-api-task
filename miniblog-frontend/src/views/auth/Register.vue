<template>
    <div class="p-4 max-w-md mx-auto">
        <h2 class="text-xl font-bold mb-4">Register</h2>
        <form @submit.prevent="register">
            <input v-model="name" type="text" placeholder="Name" class="input" />
            <input v-model="email" type="email" placeholder="Email" class="input" />
            <input v-model="password" type="password" placeholder="Password" class="input" />
            <input v-model="password_confirmation" type="password" placeholder="Confirm Password" class="input" />
            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '../../api/axios'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

const router = useRouter()

const register = async () => {
    try {
        await api.post('/auth/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        }).then((response) => {
            if (response.status === 200) {
                alert('Registration successful. Please login.')
            }
            router.push('/login')
        }).catch((error) => {
            console.error(error)
            alert('Registration failed. Please try again.')
        })
    } catch (err) {
        console.error(err.response?.data)
        alert('Registration failed.')
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
