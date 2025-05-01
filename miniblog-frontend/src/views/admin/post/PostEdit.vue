<template>
    <div class="p-4 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Edit Post</h2>
        <PostForm :form="form" :errors="errors" :submit="updatePost" :isEdit="true" />
    </div>
</template>

<script setup>
import { reactive, onMounted, computed } from 'vue'
import PostForm from '../../../components/PostForm.vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../api/axios'
import { usePostStore } from '../../../store/post'

const post = usePostStore()
const route = useRoute()
const router = useRouter()
const form = reactive({
    title: '',
    content: '',
    image: '',
    categories: []
})
const errors = reactive({})

onMounted(async () => {
    await post.loadPost(route.params.id);

    if (post.post) {
        form.title = post.post.title;
        form.content = post.post.content;
        form.categories = post.post.categories;
        form.image = post.post?.attachments[0]?.url || null;
    } else {
        console.error('Post not found or failed to load.');
    }
});

const updatePost = async () => {
    const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('title', form.title);
        formData.append('content', form.content);
        form.categories.forEach(category => {
            formData.append('categories[]', category.id);
        });
        if (form.image && typeof form.image !== 'string') {
            formData.append('image', form.image);
            formData.append('deleteAttachmentIds[]', post.post.attachments[0]?.id);
        }
        await api.post(`/posts/${route.params.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then((res) => {
            alert(res?.data?.message || 'Test');
            router.push('/admin/post')
        })
        .catch((err) => {
            console.error(typeof err.response?.data?.errors)
            Object.assign(errors, err?.response?.data?.errors);
            alert(err?.response?.data?.message || 'Something went wrong');
        })
}
</script>
