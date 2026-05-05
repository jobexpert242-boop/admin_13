<script setup>
import Footer from "../Footer/Footer.vue";
import Header from "../Header/Header.vue";
import Menu from "../Menu/Menu.vue";
</script>

<template>
    <div>
        <Header />
        <div class="flex gap-3">
            <Menu />
            <div class="p-5">
                <slot></slot>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped></style>
