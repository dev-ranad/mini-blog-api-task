<template>
    <div class="p-4 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Create Category</h2>
        <CategoryForm :form="form" :errors="errors" :submit="submitCategory" />
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import CategoryForm from '../../../components/CategoryForm.vue'
import api from '../../../api/axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = reactive({
    name: '',
})
const errors = reactive({})

const submitCategory = async () => {
    const formData = new FormData();
    formData.append('name', form.name);

    await api.post('/categories', formData).then((res) => {
        alert(res?.data?.message || 'Test');
        router.push('/admin/category')
    })
        .catch((err) => {
            console.error(typeof err.response?.data?.errors)
            Object.assign(errors, err?.response?.data?.errors);
            alert(err?.response?.data?.message || 'Something went wrong');
        })
}
</script>