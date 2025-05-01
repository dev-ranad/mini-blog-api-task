<template>
    <div class="p-4 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Comments</h2>
        </div>
        <div>
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Post Author</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Post Title</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Comment</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" :key="comment?.comments">
                    <tr v-for="(comment, index) in comment?.comments" :key="comment.id" class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-600">{{ index + 1 }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ comment?.author?.name }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ comment?.post?.title }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            {{ comment?.content.slice(0, 60) }}...
                        </td>
                        <td>
                            <button v-if="comment?.status == '1'" @click="statusComment(comment.id)"
                                class="green-badge">Publish</button>
                            <button v-else @click="statusComment(comment.id)" class="red-badge">Unpublish</button>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <router-link :to="`/admin/comment/${comment.id}`" class="btn">Details</router-link>
                            <button @click="deleteComment(comment.id)"
                                class="btn bg-red-600 text-white hover:bg-red-700">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="comment?.comments.length === 0">No comments yet.</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../../api/axios'
import { useAuthStore } from '../../../store/auth'
import { useCommentStore } from '../../../store/comment'
import { useRoute } from "vue-router";

const route = useRoute();
const auth = useAuthStore()
const comment = useCommentStore()
const loading = ref(true)

const deleteComment = async (id) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        comment.deleteComment(id);
    }
}

const statusComment = async (id) => {
    if (confirm('Are you sure you want to change this comment?')) {
        comment.statusComment(id, route.path);
    }
}

onMounted(
    comment.loadComments
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
.green-badge {
    background-color: #05af43;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
}

.red-badge {
    background-color: #f74a4a;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    align-content: center;
}

.table th,
.table td {
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
