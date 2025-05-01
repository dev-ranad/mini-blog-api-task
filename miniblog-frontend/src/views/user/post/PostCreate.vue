<template>
    <div class="p-4 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Create Post</h2>
        <PostForm :form="form" :errors="errors" :submit="submitPost" />
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import PostForm from '../../../components/PostForm.vue'
import api from '../../../api/axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = reactive({
    title: '',
    content: '',
    image: '',
    categories: []
})
const errors = reactive({})

const submitPost = async () => {
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('content', form.content);
    form.categories.forEach(category => {
        formData.append('categories[]', category.id);
    });
    if (form.image) {
        formData.append('image', form.image);
    }
    await api.post('/posts', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then((res) => {
            alert(res?.data?.message || 'Test');
            router.push('/user/post')
        })
        .catch((err) => {
            console.error(typeof err.response?.data?.errors)
            Object.assign(errors, err?.response?.data?.errors);
            alert(err?.response?.data?.message || 'Something went wrong');
        })
}
</script>
