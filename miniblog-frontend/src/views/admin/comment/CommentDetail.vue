<template>
    <div class="p-4 max-w-3xl mx-auto">
        <div v-if="details" :key="details.id">
            <h2 class="text-3xl font-bold">{{ details.title }}</h2>
            <p class="text-gray-500 mb-4">By {{ details.author.name }}</p>
            <p class="mb-8">{{ details.content }}</p>
            <span v-if="details?.status == '1'"
                class="green-badge">Publish</span>
            <span v-else class="red-badge">Unpublish</span>
        </div>
        <div v-else>Loading...</div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../../api/axios'
import { useAuthStore } from '../../../store/auth'
import { useCommentStore } from '../../../store/comment'

const route = useRoute()
const commentText = ref('')
const auth = useAuthStore()
const comment = useCommentStore()
const details = reactive({
    id: null,
    title: '',
    content: '',
    status: '',
    author: {}
})
onMounted(async () => {
    await comment.loadComment(route.params.id);
    if (comment.comment) {
        details.id = comment.comment.id;
        details.status = comment.comment.status;
        details.title = comment.comment.post.title;
        details.content = comment.comment.content;
        details.author = comment.comment.author;
    } else {
        console.error('Comment not found or failed to load.');
    }
})

</script>

<style scoped>
.btn {
    background-color: #4f46e5;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
}
.btn:hover {
    background-color: #4338ca;
}
.commentForm {
    display: flex;
    flex-direction: column;
}
.comments {
    border-radius: 6px;
    background-color: #f9f9f9;
}
.comment {
    padding-bottom: 8px;
    border-radius: 6px;
    background-color: #f1f1f1;
    border: 1px solid #ddd;
}
.commentForm textarea {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 8px;
}
.commentForm button {
    background-color: #4f46e5;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    margin-top: 8px;
}
.commentForm button:hover {
    background-color: #4338ca;
}
.btn {
    background-color: #4f46e5;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
}
.btn:hover {
    background-color: #4338ca;
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
</style>
