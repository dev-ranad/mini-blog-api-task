<template>
    <div class="p-4 max-w-md mx-auto">
        <h2 class="text-xl font-bold mb-4">Register</h2>
        <form @submit.prevent="register">
            <input v-model="name" type="text" placeholder="Name" class="input" />
            <div v-if="errors?.name">
                <span v-for="error in errors?.name" :key="error" class="error">{{ error }}</span>
            </div>
            <input v-model="email" type="email" placeholder="Email" class="input" />
            <div v-if="errors?.email">
                <span v-for="error in errors?.email" :key="error" class="error">{{ error }}</span>
            </div>
            <input v-model="password" type="password" placeholder="Password" class="input" />
            <div v-if="errors?.password">
                <span v-for="error in errors?.password" :key="error" class="error">{{ error }}</span>
            </div>
            <input v-model="password_confirmation" type="password" placeholder="Confirm Password" class="input" />
            <div v-if="errors?.password_confirmation">
                <span v-for="error in errors?.password_confirmation" :key="error" class="error">{{ error }}</span>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import api from '../../api/axios'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const errors = reactive({})
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
            Object.assign(errors, error?.response?.data?.errors);
            alert(error?.response?.data?.message || 'Registration failed. Please try again.');
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
.btn:hover {
    background: #555;
}
.error {
    color: red;
    font-size: 12px;
}
</style>
