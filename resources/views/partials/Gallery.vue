<template>
  <div class="w-11/12 mx-auto my-4">
    <div class="my-4 flex flex-row justify-between">
      <form
        action=""
        @submit.prevent="fetchImages()"
        class="w-1/2 md:w-1/3 xl:1/4"
      >
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
    <!-- Images Gallery -->
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
    <!-- Slider -->
    <div
      class="fixed inset-0 h-screen overflow-auto p-8 bg-gray-800/25"
      v-show="openSlider"
      @click="openSlider = false"
    >
      <img
        :src="slide.url"
        class="
          w-11/12
          md:w-8/12
          absolute
          left-1/2
          top-1/2
          -translate-x-1/2 -translate-y-1/2
        "
        alt=""
        loading="lazy"
        @click.stop=""
      />
      <!-- Slider dropdown -->
      <div class="absolute left-0 top-0 right-0 z-30 p-4">
        <div class="flex flex-wrap flex-row justify-between">
          <div class="block">
            <span
              class="
                text-white text-2xl
                font-black
                leading-relaxed
                cursor-pointer
              "
              @click.stop="openDropdown = !openDropdown"
              >...</span
            >
            <div
              class="bg-white rounded p-2 font-medium shadow"
              v-show="openDropdown"
            >
              <ul>
                <li class="m-1">
                  <a
                    :href="slide.url"
                    target="_blank"
                    class="text-gray-600 hover:text-gray-800"
                  >
                    Open in new tab
                  </a>
                </li>
                <li class="m-1">
                  <a
                    :href="slide.url"
                    class="text-gray-600 hover:text-gray-800"
                    @click.stop.prevent="copyURL(slide.url)"
                  >
                    Copy link
                  </a>
                </li>
                <li class="m-1" v-show="slide">
                  <router-link
                    :to="{ name: 'Edit', params: { id: slide.id } }"
                    class="text-gray-600 hover:text-gray-800"
                  >
                    Edit Image
                  </router-link>
                </li>
                <li class="m-1">
                  <a
                    href="#"
                    class="text-red-600 hover:text-red-800"
                    @click.prevent="deleteImage(slide.id)"
                    >Delete</a
                  >
                </li>
              </ul>
            </div>
          </div>
          <span class="cursor-pointer text-white hover:text-red-400">X</span>
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
      openDropdown: false,
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
      this.openDropdown = false;
    },
    deleteImage(id) {
      this.$swal
        .fire({
          icon: "warning",
          title: "Delete this image?",
          text: "This action can't be undo.",
          confirmButtonText: "Delete it",
          confirmButtonColor: "red",
          confirmButtonBorder: "red",
          showCancelButton: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            axios.delete("api/images/" + id).then((response) => {
              if (response.data.success) {
                // Show success alert
                const Toast = this.$swal.mixin({
                  toast: true,
                  position: "top-end",
                  showConfirmButton: false,
                  timer: 2500,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                    toast.addEventListener("mouseenter", this.$swal.stopTimer);
                    toast.addEventListener(
                      "mouseleave",
                      this.$swal.resumeTimer
                    );
                  },
                });
                Toast.fire({
                  title: response.data.success,
                  icon: "success",
                });

                this.fetchImages();
              } else {
                this.$swal.fire({
                  icon: "error",
                  title: response.data.error,
                });
              }
            });
          }
        });
    },
    copyURL(url) {
      this.$copyText(url).then((_) => {
        // Show success alert
        const Toast = this.$swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", this.$swal.stopTimer);
            toast.addEventListener("mouseleave", this.$swal.resumeTimer);
          },
        });
        Toast.fire({
          title: "Image's url copied!.",
          icon: "success",
        });
        this.openDropdown = false;
      });
    },
  },
  created() {
    this.fetchImages();
  },
};
</script>
