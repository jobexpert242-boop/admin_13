<script setup>
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Components/BackEnd/Layout/Layout.vue";
import FileInput from "@/Shared/FileInput.vue";

const page = usePage();
const form = useForm({
    logo: null,
    favaicon: null,
    // tax: page.props.site?.tax || "",
});

function submit() {
    form.post(route("admin.sitesetting.update"), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ["site"] });
        },
    });
}
</script>

<template>
    <div>
        <Head title="Site Setting Update" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
        <Breadcrumb/>
            <div class="p-5 shadow border border-gray-300 rounded">
                <div class="border-b border-b-gray-300 pb-2">
                    <h2 class="text-xl">
                        <i class="fa-regular fa-hand-point-right"></i> Site
                        Related all Important Text.
                    </h2>
                </div>

                <div class="mt-3">
                    <form
                        @submit.prevent="submit"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        <div class="w-xl">
                            <div class="h-fit">
                                <div class="flex gap-3 mb-3">
                                    <div class="h-fit w-1/2">
                                        <FileInput
                                            v-model="form.logo"
                                            label="Site Logo"
                                            accept="image/*"
                                            :multiple="false"
                                            :error="form.errors.logo"
                                        />
                                        <img
                                            :src="`/storage/${$page.props.site?.logo}`"
                                            class="w-20 h-20 mt-2 border border-slate-300"
                                            alt=""
                                        />
                                    </div>

                                    <div class="h-fit w-1/2">
                                        <FileInput
                                            v-model="form.favaicon"
                                            label="Site Favaicon"
                                            accept="image/*"
                                            :multiple="false"
                                            :error="form.errors.favaicon"
                                        />
                                        <img
                                            :src="`/storage/${$page.props.site?.favaicon}`"
                                            class="w-20 h-20 mt-2 border border-slate-300"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="border-t border-slate-400 w-full flex justify-end pt-5"
                        >
                            <button
                                type="submit"
                                class="btn rounded-sm fw-normal flex justify-center items-center gap-4"
                                :class="{ 'btn-spinner': form.processing }"
                                :disabled="form.processing"
                            >
                                <span>{{
                                    form.processing ? "Processing..." : "Update"
                                }}</span>
                                <span
                                    v-if="form.processing"
                                    class="spinner"
                                ></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Layout>
    </div>
</template>
