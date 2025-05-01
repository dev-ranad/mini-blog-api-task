<template>
    <div class="p-4 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Posts</h2>
            <router-link to="/admin/post/create" class="btn">+ New Post</router-link>
        </div>

        <div>
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Content</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="(post, index) in post?.posts" :key="post.id" class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-600">{{ index + 1 }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ post?.title }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            {{ post?.content.slice(0, 60) }}...
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <router-link :to="`/admin/post/${post.id}`" class="btn">Details</router-link>
                            <router-link :to="`/admin/post/${post.id}/edit`" class="btn">Edit</router-link>
                            <button @click="deletePost(post.id)" class="btn bg-red-600 text-white hover:bg-red-700">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="post?.posts.length === 0">No posts yet.</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../../api/axios'
import { useAuthStore } from '../../../store/auth'
import { usePostStore } from '../../../store/post'

const auth = useAuthStore()
const post = usePostStore()
const posts = ref([])
const loading = ref(true)


const deletePost = async (id) => {
    if (confirm('Are you sure you want to delete this post?')) {
        post.deletePost(id);
    }
}

onMounted(
    post.loadPosts
)
</script>

<style scoped>
.btn {
    background-color: #4f46e5;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s;
    margin-right: 8px;
}
.btn:hover {
    background-color: #4338ca;
}
.btn.bg-red-600 {
    background-color: #dc2626;
}
.btn.bg-red-600:hover {
    background-color: #b91c1c;
}
.table {
    width: 100%;
    border-collapse: collapse;
    align-content: center;
}
.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}
.table th {
    background-color: #f3f4f6;
    font-weight: 600;
}
.table tr:hover {
    background-color: #f9fafb;
}
.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}
.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}
.text-sm {
    font-size: 0.875rem;
}
.text-gray-600 {
    color: #4b5563;
}
.text-gray-700 {
    color: #374151;
}
.text-gray-900 {
    color: #111827;
}
</style>
