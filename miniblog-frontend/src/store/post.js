import { defineStore } from "pinia";
import api from "../api/axios";

export const usePostStore = defineStore("post", {
    state: () => ({
        posts: [],
        post: null,
    }),
    actions: {
        async loadPosts() {
            this.posts = [];
            await api
                .get("/posts")
                .then((res) => {
                    this.posts = res.data.results.post;
                    console.log(this.posts);
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async loadPost(id) {
            this.post = null;
            await api
                .get(`/posts/${id}`)
                .then((res) => {
                    this.post = res?.data?.data;
                    console.log(this.post);
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async deletePost(id) {
            await api
                .delete(`/posts/${id}`)
                .then((res) => {
                    this.posts = this.posts.filter((post) => post.id !== id);
                    this.loadPosts();
                    console.log(this.posts);
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        reset() {
            this.posts = [];
            this.post = null;
        },
    },
});
