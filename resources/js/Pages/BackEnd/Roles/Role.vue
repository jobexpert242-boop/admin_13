<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Layout from "@/Components/BackEnd/Layout/Layout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Pagination from "@/Shared/Pagination.vue";
import TextInput from "@/Shared/TextInput.vue";
import { computed } from "vue";

const props = defineProps({
    roles: Object,
    permissions: Array,
});

const form = useForm({
    id: null,
    name: "",
    permissions: [],
});

const isEdit = () => form.id !== null;

// SUBMIT
function submit() {
    if (isEdit()) {
        form.put(route("roles.update", form.id), {
            onSuccess: resetForm,
        });
    } else {
        form.post(route("roles.store"), {
            onSuccess: resetForm,
        });
    }
}

// EDIT
function editRole(role) {
    form.id = role.id;
    form.name = role.name;
    form.permissions = role.permissions.map((p) => p.name);
}

// DELETE
function deleteRole(id) {
    if (confirm("Delete this role?")) {
        form.delete(route("roles.destroy", id));
    }
}

// RESET
function resetForm() {
    form.reset();
    form.id = null;
}

const groupedPermissions = computed(() => {
    const groups = {};

    props.permissions.forEach((permission) => {
        const group = permission.name.split(" ")[1] || "other";
        // example: "create users" → group = users

        if (!groups[group]) {
            groups[group] = [];
        }

        groups[group].push(permission);
    });

    return groups;
});

function toggleGroup(groupName, groupPermissions) {
    const names = groupPermissions.map((p) => p.name);

    const allSelected = names.every((name) => form.permissions.includes(name));

    if (allSelected) {
        // uncheck all
        form.permissions = form.permissions.filter((p) => !names.includes(p));
    } else {
        // check all
        form.permissions = [...new Set([...form.permissions, ...names])];
    }
}

// CHECK IF GROUP FULLY SELECTED
function isGroupChecked(groupPermissions) {
    return groupPermissions.every((p) => form.permissions.includes(p.name));
}
</script>

<template>
    <div>
        <Head title="Role Management" />

        <Layout>
            <Breadcrumb />

            <FlashMessage
                v-if="$page.props.flash?.status"
                :message="$page.props.flash.status"
                type="success"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- LEFT: ROLE TABLE -->
                <div
                    class="bg-white p-6 rounded shadow border border-slate-300 h-fit"
                >
                    <h2 class="text-xl font-bold mb-4">Roles</h2>

                    <table class="w-full border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">#</th>
                                <th class="border p-2">Name</th>
                                <th class="border p-2">Permissions</th>
                                <th class="border p-2 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(role, index) in roles.data"
                                :key="role.id"
                            >
                                <td class="border p-2">{{ index + 1 }}</td>
                                <td class="border p-2">{{ role.name }}</td>

                                <td class="border p-2">
                                    <span
                                        v-for="p in role.permissions"
                                        :key="p.id"
                                        class="bg-blue-100 text-blue-600 px-2 py-1 mr-1 rounded text-sm"
                                    >
                                        {{ p.name }}
                                    </span>
                                </td>

                                <td class="border p-2 text-center space-x-2">
                                    <button
                                        @click="editRole(role)"
                                        class="bg-yellow-400 px-2 py-1 text-white rounded"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button
                                        @click="deleteRole(role.id)"
                                        class="bg-red-500 px-2 py-1 text-white rounded"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <Pagination :links="roles.links" />
                    </div>
                </div>

                <!-- RIGHT: FORM -->
                <div
                    class="bg-white p-6 rounded shadow border border-slate-300 h-fit"
                >
                    <h2 class="text-xl font-bold mb-4">
                        {{ isEdit() ? "Edit Role" : "Create Role" }}
                    </h2>

                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <TextInput
                                label="Role Name"
                                v-model="form.name"
                                :error="form.errors.name"
                                placeholder="Create Role.."
                            />
                        </div>

                        <!-- PERMISSIONS -->
                        <div class="mb-4">
                            <div class="mb-2">
                                <label
                                    class="flex items-center gap-2 font-semibold"
                                >
                                    <input
                                        type="checkbox"
                                        @change="
                                            form.permissions = $event.target
                                                .checked
                                                ? permissions.map((p) => p.name)
                                                : []
                                        "
                                    />
                                    Select All Permissions
                                </label>
                            </div>
                            <label class="block mb-2 font-medium">
                                Permissions
                            </label>

                            <div
                                class="space-y-4 max-h-80 overflow-y-auto border p-3 rounded shadow border-slate-400"
                            >
                                <div
                                    v-for="(
                                        groupPermissions, groupName
                                    ) in groupedPermissions"
                                    :key="groupName"
                                    class="border rounded p-3 border-slate-400"
                                >
                                    <!-- GROUP HEADER -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <input
                                            type="checkbox"
                                            :checked="
                                                isGroupChecked(groupPermissions)
                                            "
                                            @change="
                                                toggleGroup(
                                                    groupName,
                                                    groupPermissions,
                                                )
                                            "
                                        />
                                        <span class="font-semibold capitalize">
                                            {{ groupName }}
                                        </span>
                                    </div>

                                    <!-- CHILD PERMISSIONS -->
                                    <div class="grid grid-cols-2 gap-2 pl-5">
                                        <label
                                            v-for="permission in groupPermissions"
                                            :key="permission.id"
                                            class="flex items-center gap-2"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="permission.name"
                                                v-model="form.permissions"
                                            />
                                            {{ permission.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-red-500 text-sm">
                                {{ form.errors.permissions }}
                            </div>
                        </div>

                        <div class="flex gap-2">
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
