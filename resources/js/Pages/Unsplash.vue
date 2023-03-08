<template>
<div>
    <form class="grid grid-cols-12 gap-4 items-center m-4" @submit.prevent="onSearch">
        <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full col-span-4"
            v-model="query"
            required
            autofocus
        />
        <select v-model="selectedColor" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm col-span-4">
            <option value="">Select Color</option>
            <option v-for="color in props.colors" :value="color" v-text="color"></option>
        </select>
        <div class="col-span-2">
            <PrimaryButton>Search</PrimaryButton>
        </div>
    </form>



    <div class="grid grid-cols-4 gap-4 mx-4">
        <div class="flex relative" v-for="(image,index) in props.data.results" :key="index">
            <img :src="image.urls.regular" :alt="image.alt_description" class="absolute inset-0 w-full h-full object-cover object-center">
            <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">{{ image.alt_description }}</h2>

                <div>
                    <font-awesome-icon icon="fa-solid fa-user" class="h-4 w-4 mr-4 cursor-pointer" @click="onLinkClick(image.user.social, 'portfolio')" />
                    <a :href="image.user.links.html" target="_blank" class="text-blue-700 hover:underline">{{ userFullName(image.user) }}</a>
                </div>

                <Tooltip :text="processLink(image.user.social, 'portfolio')">
                    <font-awesome-icon icon="fa-solid fa-link" class="h-8 w-8 mr-4 cursor-pointer" @click="onLinkClick(image.user.social, 'portfolio')" />
                </Tooltip>

                <Tooltip :text="processLink(image.user.social, 'instagram')">
                    <font-awesome-icon icon="fa-brands fa-instagram" class="h-8 w-8 mr-4 cursor-pointer" @click="onLinkClick(image.user.social,'instagram')" />
                </Tooltip>

                <Tooltip :text="processLink(image.user.social, 'twitter')">
                    <font-awesome-icon icon="fa-brands fa-twitter" class="h-8 w-8 cursor-pointer" @click="onLinkClick(image.user.social,  'twitter')" />
                </Tooltip>


                <div class="mx-auto">
                    <PrimaryButton type="button" class="mb-2">Select As Logo</PrimaryButton>
                    <PrimaryButton type="button" class="mb-2" @click="viewOriginal(image.links.html)">View Original</PrimaryButton>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script setup>

import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Tooltip from "@/Components/Tooltip.vue";

const props = defineProps({
    data: {
        type: Object,
        default: () => {}
    },

    colors: {
        type: Array,
        default: () => []
    },

    title: {
        type: String,
        default: ''
    },

    color: {
        type: String,
        default: ''
    }
});

const query = ref(props.title);

const selectedColor = ref(props.color);


const onSearch = () => {
    router.reload({
        data: {
            title: query.value,
            color: selectedColor.value,
        }
    })
}

const onLinkClick = (links, type) => {
    const link = processLink(links, type)
    if (link) {
        window.open(link, '_blank');
    }
}

const userFullName = (user) => {
    const firstName = user.first_name ?? '';
    const lastName = user.last_name ?? '';
    return `${firstName} ${lastName}`;
}

const viewOriginal = (link) => {
    window.open(link)
}
const processLink = (links, type) => {
    const  socialLinks = {
        'portfolio' : links['portfolio_url'],
        'instagram' : links['instagram_username'] != null ? `https://www.instagram.com/${links['instagram_username']}` : null,
        'twitter' : links['twitter_username'] != null ?`https://twitter.com/${links['twitter_username']}` : null,
    }

    return socialLinks[type];
}

</script>

<style scoped>

</style>
