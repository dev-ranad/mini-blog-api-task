<template>
    <form @submit.prevent="submit" enctype="multipart/form-data">
        <img v-if="attachment_url" :src="attachment_url" width="200" alt="Preview" class="mb-2" />
        <multiselect v-model="form.categories" :options="category?.categories" :multiple="true" :close-on-select="false"
            :clear-on-select="false" :preserve-search="true" placeholder="Select categories" label="name"
            track-by="id" />
        <div v-if="errors?.categories">
            <span v-for="error in errors?.categories" :key="error" class="error">{{ error }}</span>
        </div>

        <input type="text" v-model="form.title" class="input" placeholder="Title" />
        <div v-if="errors?.title">
            <span v-for="error in errors?.title" :key="error" class="error">{{ error }}</span>
        </div>

        <textarea v-model="form.content" class="input" rows="5" placeholder="Content"></textarea>
        <div v-if="errors?.content">
            <span v-for="error in errors?.content" :key="error" class="error">{{ error }}</span>
        </div>

        <label>Image (optional)</label>
        <input type="file" class="input" @change="handleFile" />
        <div v-if="errors?.image">
            <span v-for="error in errors?.image" :key="error" class="error">{{ error }}</span>
        </div>

        <button class="btn">
            {{ isEdit ? 'Update' : 'Create' }} Post
        </button>
    </form>
</template>

<script setup>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import { ref, onMounted, watch } from 'vue'
import { useCategoryStore } from '../store/category'

const props = defineProps({
    form: Object,
    errors: Object,
    isEdit: Boolean,
    submit: Function
})

const attachment_url = ref(null)

const category = useCategoryStore()

onMounted(() => {
    category.loadCategories();
})

watch(
    () => props.form.image,
    (newVal) => {
        if (newVal && typeof newVal === 'string') {
            attachment_url.value = newVal
        }
    },
    { immediate: true }
)

function handleFile(event) {
    const file = event.target.files[0];
    if (file) {
        props.form.image = file;
        const reader = new FileReader()
        reader.onload = (e) => {
            attachment_url.value = e.target.result
        }
        reader.readAsDataURL(file)
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
    margin-top: 4px;
    display: block;
}
.multiselect {
    margin: 8px 0;
}
</style>
