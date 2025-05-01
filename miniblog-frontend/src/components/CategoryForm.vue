<template>
    <form @submit.prevent="submit" enctype="multipart/form-data">

        <input type="text" v-model="form.name" class="input" placeholder="Title" />
        <div v-if="errors?.name">
            <span v-for="error in errors?.name" :key="error" class="error">{{ error }}</span>
        </div>

        <button class="btn">
            {{ isEdit ? 'Update' : 'Create' }} Category
        </button>
    </form>
</template>

<script setup>

const props = defineProps({
    form: Object,
    errors: Object,
    isEdit: Boolean,
    submit: Function
})

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
