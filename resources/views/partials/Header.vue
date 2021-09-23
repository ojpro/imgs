<template>
  <header class="sticky top-0 z-10">
    <nav
      class="
        flex flex-row flex-wrap
        justify-between
        items-center
        p-4
        shadow
        bg-white
        dark:bg-gray-900
      "
    >
      <!-- Logo -->
      <div class="">
        <h1 class="dark:text-white">IMGS.local</h1>
      </div>
      <!-- Search form -->
      <div class="w-5/12 relative">
        <form action="" method="get">
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
            @input="searchforImages($event.target.value)"
          />
        </form>

        <datalist id="images" v-show="images" class="absolute">
          <option v-for="image in images" :key="image.id" :value="image.label" />
        </datalist>
      </div>
      <!-- Upload new Image Button -->
      <div>
        <router-link
          class="bg-blue-500 rounded px-2 py-1.5 text-white focus:shadow"
          to="/upload"
        >
          Upload
        </router-link>
      </div>
    </nav>
  </header>
</template>
<script>
import axios from "axios";
export default {
  name: "Header",
  data() {
    return {
      images: '',
      search: "",
    };
  },
  methods: {
    searchforImages(query) {
      axios.get("api/images?search=" + query).then((res) => {
        if (res.data.length > 1) {
          // Fill names
          this.images = res.data;
        } else {
          // empty the values of categories variable
          this.images = "";
        }
      });
    },
  },
};
</script>