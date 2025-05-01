import { defineStore } from "pinia";
import api from "../api/axios";

export const useCategoryStore = defineStore("category", {
    state: () => ({
        categories: [],
        category: null,
    }),
    actions: {
        async loadCategories() {
            this.categories = [];
            await api
                .get("/categories")
                .then((res) => {
                    this.categories = res.data.results.category;
                    console.log(this.categories);
                })
                .catch((err) => {

                    console.error(err);
                    alert(err?.response?.data?.message || "Something went wrong");
                });
        },
        async deleteCategory(id) {
            this.category = null;
            await api
                .delete(`/categories/${id}`)
                .then((res) => {
                    this.categories = this.categories.filter((category) => category.id !== id);
                    this.loadCategories();
                    console.log(this.categories);
                })
                .catch((err) => {
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        reset() {
            this.categories = [];
            this.category = null;
        },
    },
});
