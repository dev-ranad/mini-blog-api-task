<template>
    <div class="p-4 max-w-3xl mx-auto">
        <div v-if="details">
            <h2 class="text-3xl font-bold">{{ details.title }}</h2>
            <p class="text-gray-500 mb-4">By {{ details.author.name }}</p>
            <img v-if="details?.image" :src="details?.image" class="rounded max-h-96 mb-4" />
            <p class="mb-8">{{ details.content }}</p>

            <h3 class="text-xl font-semibold mb-2">Comments ({{ details.comments.length }})</h3>
            <div v-for="comment in details.comments" :key="comment.id" class="comments mb-3 border-b pb-2">
                <p class="font-semibold">{{ comment?.author?.name }}</p>
                <p class="comment">{{ comment.content }}</p>
            </div>

            <div v-if="auth.user" class="mt-6">
                <h4 class="text-lg font-semibold mb-2">Add a comment</h4>
                <form @submit.prevent="submitComment" class="commentForm">
                    <textarea v-model="commentText" rows="3" class="w-full border rounded p-2 mb-2"></textarea>
                    <button class="btn">Submit</button>
                </form>
            </div>

            <div v-else class="mt-6">
                <p class="text-gray-600 italic">You must <router-link to="/login"
                        class="text-blue-600">login</router-link> to comment.</p>
            </div>
        </div>
        <div v-else>Loading...</div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../../api/axios'
import { useAuthStore } from '../../../store/auth'
import { usePostStore } from '../../../store/post'

const route = useRoute()
const commentText = ref('')
const auth = useAuthStore()
const post = usePostStore()
const details = reactive({
    title: '',
    content: '',
    image: '',
    categories: [],
    comments: [],
    author: {}
})
onMounted(async () => {
    await post.loadPost(route.params.id);

    if (post.post) {
        console.log(post.post)
        details.title = post.post.title;
        details.content = post.post.content;
        details.categories = post.post.categories;
        details.image = post.post.attachments[0]?.url || null;
        details.comments = post.post.comments;
        details.author = post.post.author;
    } else {
        console.error('Post not found or failed to load.');
    }
})

const submitComment = async () => {
    try {
        const payload = {
            post_id: route.params.id,
            body: commentText.value
        }
        await api.post(`/comments`, payload)
            .then((res) => {
                alert(res?.data?.message || 'Comment submitted successfully');
                post.loadPost(route.params.id);
            })
            .catch((err) => {
                
                    console.error(err);
                    alert(err?.response?.data?.message || "Something went wrong");
                alert(err?.response?.data?.message || 'Failed to submit comment');
            })
    } catch (err) {
        alert('Failed to submit comment.')
    }
}

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

</style>
