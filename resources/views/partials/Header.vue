<template>
  <header>
    <nav
      class="
        flex flex-row flex-wrap
        justify-between
        items-center
        py-2
        px-4
        shadow-sm
      "
    >
      <!-- Logo -->
      <div class="">
        <h1>IMGS.local</h1>
      </div>
      <!-- Search form -->
      <div class="w-5/12 relative">
        <input
          type="search"
          list="names"
          class="
            border border-gray-100
            focus:shadow-sm
            rounded
            px-2
            py-1
            w-full
          "
          placeholder="Search. i.e Car, Rabbit..."
          v-model="search"
          @input="fetchCategories($event.target.value)"
        />

        <datalist id="names" v-show="names" class="absolute">
          <option
            v-for="name in names"
            :key="name.id"
            :value="name.name"
          />
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
      names: null,
      search: "",
    };
  },
  methods: {
    fetchCategories(search) {
      axios.get("api/categories?search=" + search).then((res) => {
        if (res.data.length > 1) {
          // Fill names
          this.names = res.data;
        } else {
          // empty the values of categories variable
          this.names = "";
        }
      });
    },
  },
};
</script>