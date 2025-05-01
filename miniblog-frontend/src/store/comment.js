import { defineStore } from "pinia";
import api from "../api/axios";

export const useCommentStore = defineStore("comment", {
    state: () => ({
        comments: [],
        comment: null,
    }),
    actions: {
        async loadComments() {
            this.comments = [];
            await api
                .get("/comments")
                .then((res) => {
                    this.comments = res.data.results.comment;
                    console.log(this.comments);
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async loadComment(id) {
            this.comment = null;
            await api
                .get(`/comments/${id}`)
                .then((res) => {
                    this.comment = res.data.data;
                    console.log(this.comment);
                })
                .catch((err) => {
                    console.log(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async deleteComment(id) {
            await api
                .delete(`/comments/${id}`)
                .then((res) => {
                    this.comments = this.comments.filter(
                        (comment) => comment.id !== id
                    );
                    this.loadComments();
                    console.log(this.comments);
                })
                .catch((err) => {
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async statusComment(id, path) {
            await api
                .get(`/comment/status/${id}`)
                .then((res) => {
                    this.loadComments();
                    console.log(this.comments);
                })
                .catch((err) => {
                    console.log(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
            },
            reset() {
                this.comments = [];
                this.comment = null;
            },
    },
});
