<template>
    <div class="p-4 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Categories</h2>
            <router-link to="/admin/category/create" class="btn">+ New Category</router-link>
        </div>
        <div>
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="(category, index) in category?.categories" :key="category.id" class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-600">{{ index + 1 }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ category?.name }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button @click="deleteCategory(category.id)"
                                class="btn bg-red-600 text-white hover:bg-red-700">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="category?.categories.length === 0">No categories yet.</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../../api/axios'
import { useAuthStore } from '../../../store/auth'
import { useCategoryStore } from '../../../store/category'

const auth = useAuthStore()
const category = useCategoryStore()
const categories = ref([])
const loading = ref(true)


const deleteCategory = async (id) => {
    if (confirm('Are you sure you want to delete this category?')) {
        category.deleteCategory(id);
    }
}

onMounted(
    category.loadCategories
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
