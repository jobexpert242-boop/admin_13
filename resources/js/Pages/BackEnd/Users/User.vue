<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Components/BackEnd/Layout/Layout.vue";
import Pagination from "@/Shared/Pagination.vue";
import TextInput from "@/Shared/TextInput.vue";

const props = defineProps({
    roles: Array,
    users: Object,
});

const form = useForm({
    id: null,
    name: "",
    email: "",
    password: "",
    role: "",
});

const isEdit = () => form.id !== null;

// SUBMIT
function submit() {
    if (isEdit()) {
        form.put(route("users.update", form.id), {
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(route("users.store"), {
            onSuccess: () => resetForm(),
        });
    }
}

// EDIT
function editUser(user) {
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.password = "";
    form.role = user.roles[0]?.name || "";
}

// DELETE
function deleteUser(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        form.delete(route("users.destroy", id));
    }
}

// RESET
function resetForm() {
    form.reset();
    form.id = null;
}
</script>

<template>
    <div>
        <Head title="Users Management" />

        <Layout>
            <Breadcrumb />

            <FlashMessage
                v-if="$page.props.flash?.status"
                :message="$page.props.flash.status"
                type="success"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- USERS TABLE -->
                <div
                    class="bg-white p-6 rounded shadow border border-slate-300 h-fit"
                >
                    <h2 class="text-xl font-bold mb-4">Users List</h2>

                    <table class="w-full border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">#</th>
                                <th class="border p-2">Name</th>
                                <th class="border p-2">Email</th>
                                <th class="border p-2">Role</th>
                                <th class="border p-2 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(user, index) in users.data"
                                :key="user.id"
                            >
                                <td class="border p-2">{{ index + 1 }}</td>
                                <td class="border p-2">{{ user.name }}</td>
                                <td class="border p-2">{{ user.email }}</td>
                                <td class="border p-2">
                                    {{ user.roles[0]?.name || "N/A" }}
                                </td>

                                <!-- ACTION -->
                                <td class="border p-2 text-center space-x-2">
                                    <button
                                        @click="editUser(user)"
                                        class="bg-yellow-400 px-2 py-1 text-white rounded"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button
                                        @click="deleteUser(user.id)"
                                        class="bg-red-500 px-2 py-1 text-white rounded"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <Pagination :links="users.links" />
                    </div>
                </div>
                <!-- FORM -->
                <div
                    class="bg-white p-6 rounded shadow border border-slate-300 h-fit"
                >
                    <h2 class="text-xl font-bold mb-4">
                        {{ isEdit() ? "Edit User" : "Create User" }}
                    </h2>

                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <TextInput
                                label="Name"
                                v-model="form.name"
                                :error="form.errors.name"
                                placeholder="User Name...."
                            />
                        </div>

                        <div class="mb-4">
                            <TextInput
                                label="Email"
                                type="email"
                                v-model="form.email"
                                :error="form.errors.email"
                                placeholder="User Email.."
                            />
                        </div>

                        <div class="mb-4">
                            <TextInput
                                label="Password"
                                type="password"
                                v-model="form.password"
                                :error="form.errors.password"
                                placeholder="User Password...."
                            />
                        </div>
                        <!-- ROLE -->
                        <div class="mb-4">
                            <label class="block mb-1">Role</label>
                            <select
                                v-model="form.role"
                                class="border p-2 w-full rounded border-slate-400 shadow"
                            >
                                <option value="">Select Role</option>
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.name"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
                            <div class="text-red-500 text-sm">
                                {{ form.errors.role }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button
                                type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded"
                            >
                                {{ isEdit() ? "Update" : "Create" }}
                            </button>

                            <button
                                v-if="isEdit()"
                                type="button"
                                @click="resetForm"
                                class="bg-gray-400 text-white px-4 py-2 rounded"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Layout>
    </div>
</template>
