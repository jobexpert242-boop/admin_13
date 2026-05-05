<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Layout from "@/Components/BackEnd/Layout/Layout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Pagination from "@/Shared/Pagination.vue";
import TextInput from "@/Shared/TextInput.vue";

const props = defineProps({
    permissions: Object,
});

const form = useForm({
    id: null,
    name: "",
});

const isEdit = () => form.id !== null;

// SUBMIT
function submit() {
    if (isEdit()) {
        form.put(route("permissions.update", form.id), {
            onSuccess: resetForm,
        });
    } else {
        form.post(route("permissions.store"), {
            onSuccess: resetForm,
        });
    }
}

// EDIT
function editPermission(permission) {
    form.id = permission.id;
    form.name = permission.name;
}

// DELETE
function deletePermission(id) {
    if (confirm("Delete this permission?")) {
        form.delete(route("permissions.destroy", id));
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
        <Head title="Permission Management" />

        <Layout>
            <Breadcrumb />

            <FlashMessage
                v-if="$page.props.flash?.status"
                :message="$page.props.flash.status"
                type="success"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- LEFT: PERMISSION TABLE -->
                <div
                    class="bg-white p-6 rounded shadow border border-slate-300 h-fit"
                >
                    <h2 class="text-xl font-bold mb-4">Permissions</h2>

                    <table class="w-full border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">#</th>
                                <th class="border p-2">Name</th>
                                <th class="border p-2 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(permission, index) in permissions.data"
                                :key="permission.id"
                            >
                                <td class="border p-2">{{ index + 1 }}</td>
                                <td class="border p-2">
                                    {{ permission.name }}
                                </td>

                                <td class="border p-2 text-center space-x-2">
                                    <button
                                        @click="editPermission(permission)"
                                        class="bg-yellow-400 px-2 py-1 text-white rounded"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button
                                        @click="deletePermission(permission.id)"
                                        class="bg-red-500 px-2 py-1 text-white rounded"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <Pagination :links="permissions.links" />
                    </div>
                </div>

                <!-- RIGHT: FORM -->
                <div class="bg-white p-6 rounded shadow border border-slate-300 h-fit">
                    <h2 class="text-xl font-bold mb-4">
                        {{ isEdit() ? "Edit Permission" : "Create Permission" }}
                    </h2>

                    <form @submit.prevent="submit">
                        <TextInput
                            label="Permission Name"
                            v-model="form.name"
                            :error="form.errors.name"
                            placeholder="Create Permissions.."
                        />

                        <div class="flex gap-2 mt-4">
                            <button
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
