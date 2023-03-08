<template>
    <div>
        <form class="grid grid-cols-12 gap-4 items-center m-4" @submit.prevent="onSearch(1)">
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full col-span-4"
                v-model="query"
                required
                autofocus
                placeholder="Enter keywords to search"
            />
            <select v-model="selectedColor"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm col-span-4">
                <option value="">Select Color</option>
                <option v-for="color in props.colors" :value="color" v-text="color"></option>
            </select>
            <div class="col-span-2">
                <PrimaryButton>Search</PrimaryButton>
            </div>
        </form>


        <div v-if="selectedImage" class="flex items-center uppercase m-4">
            <div class="tracking-widest text-sm title-font font-medium text-indigo-500">
                selected Image (LOGO)
            </div>
            <div class="border border-indigo-600 flex p-2  w-[150px] h-[100px] ml-4">
                <img :src="selectedImage.urls.regular" class="inset-0 w-full h-full object-cover object-center">
            </div>
            <div>
                <PrimaryButton type="button" class="mx-2" @click="selectedImage = null">Clear</PrimaryButton>
                <PrimaryButton type="button" @click="download">Download</PrimaryButton>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mx-4">
            <div class="flex relative" v-for="(image,index) in props.data.results" :key="index">
                <img :src="image.urls.regular" :alt="image.alt_description"
                     class="absolute inset-0 w-full h-full object-cover object-center">
                <div
                    class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                    <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">
                        {{ image.alt_description }}</h2>

                    <div>
                        <font-awesome-icon icon="fa-solid fa-user" class="h-4 w-4 mr-4 cursor-pointer"
                                           @click="onLinkClick(image.user.social, 'portfolio')"/>
                        <a :href="image.user.links.html" target="_blank"
                           class="text-blue-700 hover:underline">{{ userFullName(image.user) }}</a>
                    </div>

                    <Tooltip :text="processLink(image.user.social, 'portfolio')">
                        <font-awesome-icon icon="fa-solid fa-link" class="h-8 w-8 mr-4 cursor-pointer"
                                           @click="onLinkClick(image.user.social, 'portfolio')"/>
                    </Tooltip>

                    <Tooltip :text="processLink(image.user.social, 'instagram')">
                        <font-awesome-icon icon="fa-brands fa-instagram" class="h-8 w-8 mr-4 cursor-pointer"
                                           @click="onLinkClick(image.user.social,'instagram')"/>
                    </Tooltip>

                    <Tooltip :text="processLink(image.user.social, 'twitter')">
                        <font-awesome-icon icon="fa-brands fa-twitter" class="h-8 w-8 cursor-pointer"
                                           @click="onLinkClick(image.user.social,  'twitter')"/>
                    </Tooltip>


                    <div>
                        <PrimaryButton type="button" class="m-2 block" @click="selectImageAsLogo(image)">Select As
                            Logo
                        </PrimaryButton>
                        <PrimaryButton type="button" class="m-2 block" @click="viewOriginal(image.links.html)">View
                            Original
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2 p-4">
            <button
                v-show="page > 1"
                @click="onPrev"
                type="button"
                class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-gray-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Prev
            </button>

            <button
                @click="onNext"
                type="button"
                class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-gray-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Next
            </button>
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
        default: () => {
        }
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
    },

    page: {
        type: Number,
        default: 1,
    }
});

const query = ref(props.title);
const selectedImage = ref(null);

const selectedColor = ref(props.color);


const onSearch = (page = 1) => {
    router.reload({
        data: {
            title: query.value,
            color: selectedColor.value,
            page: page
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
    const socialLinks = {
        'portfolio': links['portfolio_url'],
        'instagram': links['instagram_username'] != null ? `https://www.instagram.com/${links['instagram_username']}` : null,
        'twitter': links['twitter_username'] != null ? `https://twitter.com/${links['twitter_username']}` : null,
    }

    return socialLinks[type];
}

const selectImageAsLogo = (image) => {
    selectedImage.value = image
}

const download = () => {
    const url = `/download?url=${selectedImage.value.links.download}`;
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            let blobUrl = window.URL.createObjectURL(blob);
            let link = document.createElement('a');
            link.download = url.replace(/^.*[\\\/]/, '');
            link.href = blobUrl;
            document.body.appendChild(link);
            link.click();
            link.remove();
        })
}

const onPrev = () => {
    onSearch(props.page - 1);
}

const onNext = () => {
    const page = 1 + Number(props.page);
    onSearch(page);
}

</script>

<style scoped>

</style>
