import { defineStore } from "pinia";
import api from "../api/axios";

export const useUserStore = defineStore("user", {
    state: () => ({
        users: [],
        user: null,
    }),
    actions: {
        async loadUsers() {
            this.users = [];
            await api
                .get("/users")
                .then((res) => {
                    this.users = res.data.results.user;
                    console.log(this.users);
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async loadUser(id) {
            this.user = null;
            await api
                .get(`/users/${id}`)
                .then((res) => {
                    this.user = res.data.results.user;
                    console.log(this.user);
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        async deleteUser(id) {
            await api
                .delete(`/users/${id}`)
                .then((res) => {
                    this.users = this.users.filter((user) => user.id !== id);
                    console.log(this.users);
                })
                .catch((err) => {
                    alert(
                        err?.response?.data?.message || "Something went wrong"
                    );
                });
        },
        reset() {
            this.users = [];
            this.user = null;
        },
    },
});
