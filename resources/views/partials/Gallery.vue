<template>
  <div class="w-11/12 mx-auto my-4">
    <div class="my-4 flex flex-row justify-between">
      <form action="" @submit.prevent="fetchImages()" class="w-1/3">
        <input
          type="search"
          list="images"
          class="
            border border-gray-200
            focus:shadow-sm
            rounded
            px-2
            py-1
            w-full
            dark:bg-gray-700
            dark:border-gray-600
            dark:text-white
          "
          placeholder="Search. i.e Car, Rabbit..."
          v-model="search"
          @input="fetchImages()"
        />
      </form>
      <!-- Upload new Image Button -->
      <div>
        <router-link
          class="bg-blue-500 rounded px-2 py-1.5 text-white focus:shadow"
          to="/upload"
        >
          Upload
        </router-link>
      </div>
    </div>
    <ul
      class="relative col-count-1 sm:col-count-2 md:col-count-3 xl:col-count-4"
    >
      <li
        v-for="image in images"
        :key="image.id"
        v-show="images"
        class="
          inline-block
          mb-1
          rounded-sm
          relative
          overflow-hidden
          group
          cursor-pointer
        "
        @click="showSlider(image)"
      >
        <img :src="image.url" :alt="image.label" loading="lazy" />
        <div class="absolute inset-0 p-2 bg-black/25 hidden group-hover:block">
          <div class="flex flex-col justify-between h-full text-white">
            <h3 class="text-md font-semibold">
              {{ image.label }}
            </h3>
            <a
              :href="image.url"
              download=""
              class="
                bg-green-500
                hover:bg-green-600
                inline-block
                self-end
                max-w-min
                px-3
                py-1
                rounded
              "
              >&downarrow;</a
            >
          </div>
        </div>
      </li>
    </ul>
    <div
      class="fixed inset-0 h-auto overflow-auto p-8 bg-gray-800/25"
      v-show="openSlider"
      @click="openSlider = false"
    >
      <img
        :src="slide.url"
        class="w-10/12 mx-auto my-4"
        alt=""
        loading="lazy"
        @click.stop=""
      />
      <div class="absolute inset-0 z-[-1]">
        <span
          class="float-right m-4 cursor-pointer text-white hover:text-red-400"
          >X</span
        >
        <div class="fixed left-8 top-8 z-20 m-1 leading-8">
          <span class="text-white text-md font-bold leading-relaxed cursor-pointer bg-red-400" @click.stop="openDropdown = !openDropdown">...</span>
          <div class="bg-white rounded p-2 font-medium" v-show="openDropdown">
            <ul>
              <li class="m-1"><a href="" class="text-gray-600 hover:text-gray-800">Share</a></li>
              <li class="m-1"><a href="" class="text-gray-600 hover:text-gray-800">Edit</a></li>
              <li class="m-1"><a href="" class="text-red-600 hover:text-red-800">Delete</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      images: "",
      search: "",
      openSlider: false,
      slide: "",
      openDropdown:false
    };
  },
  methods: {
    fetchImages() {
      axios.get("api/images?search=" + this.search).then((res) => {
        this.images = res.data;
      });
    },
    showSlider(val) {
      this.openSlider = true;
      this.slide = val;
    },
  },
  created() {
    this.fetchImages();
  },
};
</script>
