import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../store/auth";

import Login from "../views/auth/Login.vue";
import Register from "../views/auth/Register.vue";

import Home from "../views/web/Home.vue";
// import PostDetail from "../views/web/PostDetail.vue";
import AdminDashboard from "../views/admin/Dashboard.vue";
import AdminPostList from "../views/admin/post/PostList.vue";
import AdminPostDetail from "../views/admin/post/PostDetail.vue";
import AdminPostCreate from "../views/admin/post/PostCreate.vue";
import AdminPostEdit from "../views/admin/post/PostEdit.vue";
import AdminCategoryList from "../views/admin/category/CategoryList.vue";
import AdminCategoryCreate from "../views/admin/category/CategoryCreate.vue";
import AdminCommentList from "../views/admin/comment/CommentList.vue";
import AdminCommentDetail from "../views/admin/comment/CommentDetail.vue";
import AdminUserList from "../views/admin/user/UserList.vue";
import UserDashboard from "../views/user/Dashboard.vue";
import UserPostList from "../views/user/post/PostList.vue";
import UserPostCreate from "../views/user/post/PostCreate.vue";
import UserPostDetail from "../views/user/post/PostDetail.vue";
import Layout from "../components/Layout.vue";

const routes = [
    {
        path: "/",
        component: Home,
    },
    {
        path: "/login",
        component: Login,
        meta: { guestOnly: true },
    },
    {
        path: "/register",
        component: Register,
        meta: { guestOnly: true },
    },
    {
        path: "/admin",
        component: Layout,
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                component: AdminDashboard,
            },
            {
                path: "post",
                component: AdminPostList,
            },
            {
                path: "post/create",
                component: AdminPostCreate,
            },
            {
                path: "post/:id",
                component: AdminPostDetail,
            },
            {
                path: "post/:id/edit",
                component: AdminPostEdit,
            },
            {
                path: "category",
                component: AdminCategoryList,
            },
            {
                path: "category/create",
                component: AdminCategoryCreate,
            },
            {
                path: "user",
                component: AdminUserList,
            },
            {
                path: "comment",
                component: AdminCommentList,
            },
            {
                path: "comment/:id",
                component: AdminCommentDetail,
            },
        ],
    },
    {
        path: "/user",
        component: Layout,
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                component: UserDashboard,
            },
            {
                path: "post",
                component: UserPostList,
            },
            {
                path: "post/create",
                component: UserPostCreate,
            },
            {
                path: "post/:id",
                component: UserPostDetail,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();
    if (to.meta.requiresAuth && !auth.token) {
        next("/login");
    } else if (to.meta.guestOnly && auth.token) {
        if (auth.role === "User") {
            next("/user");
        } else {
            next("/admin");
        }
    } else {
        next();
    }
});

export default router;
