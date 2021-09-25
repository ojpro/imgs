<template>
  <div class="min-h-screen">
    <nav>
      <div class="py-2 px-3">
        <router-link to="/" class="text-blue-500 text-md font-medium">
          &leftarrow; Go back
        </router-link>
      </div>
      <!-- Edit Image Form -->
     <div class="container mx-auto">
        <div
          class="shadow mx-auto rounded w-96 mt-16 dark:border-gray-600 border"
        >
          <h1 class="text-center font-bold text-lg dark:text-white">
            Update Image Details.
          </h1>
          <form
            action=""
            method="POST"
            class="flex flex-col justify-between gap-3 rounded"
            @submit.prevent="update()"
          >
            <div class="mx-3">
              <div
                class="
                  h-auto
                  mx-auto
                  my-2
                  rounded
                  relative
                  border-2 border-dashed border-gray-100
                  dark:border-gray-600
                  bg-gray-50/40
                  dark:bg-gray-700/50
                "
              >
                <img :src="form.url" :alt="form.label" />
              </div>

              <span
                v-for="error in errors.image"
                :key="error.image"
                v-show="errors.image"
                class="text-red-500 text-sm m-1 font-light"
                >{{ error }}</span
              >
            </div>
            <div class="mx-3">
              <label for="label" class="text-gray-500 text-sm">Label</label>
              <input
                id="label"
                type="text"
                name="label"
                class="
                  border-2
                  dark:border-gray-600
                  border-gray-100
                  rounded
                  w-full
                  py-1
                  px-2
                  dark:bg-gray-700
                  dark:text-white
                "
                placeholder="Image label"
                v-model="form.label"
                :class="errors.label ? 'border-red-300' : ''"
              />
              <span
                v-for="error in errors.label"
                :key="error.label"
                v-show="errors.label"
                class="text-red-500 text-sm m-1 font-light"
                >{{ error }}</span
              >
            </div>
            <div class="mx-3">
              <label for="category" class="text-gray-500 text-sm"
                >Category</label
              >
              <input
                type="search"
                name="category"
                list="categories"
                id="category"
                class="
                  border-2
                  dark:border-gray-600
                  border-gray-100
                  rounded
                  w-full
                  py-1
                  px-2
                  dark:bg-gray-700
                  dark:text-white
                "
                :class="errors.category_name ? 'border-red-300' : ''"
                placeholder="Select image category"
                v-model="form.category_name"
                @input="fetchCategories($event.target.value)"
              />
              <datalist id="categories" v-show="categories">
                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.name"
                />
              </datalist>
              <span
                v-for="error in errors.category_name"
                :key="error.category_name"
                v-show="errors.category_name"
                class="text-red-500 text-sm m-1 font-light"
                >{{ error }}</span
              >
            </div>
            <div
              class="
                flex flex-row
                justify-end
                items-center
                dark:bg-gray-700
                bg-gray-50/80
                p-2
              "
            >
              <button
                type="reset"
                class="text-red-500 hover:text-red-400"
                id="formResetButton"
              >
                Cancel
              </button>
              <button
                type="submit"
                href=""
                class="
                  text-white
                  bg-blue-500
                  rounded
                  px-2
                  py-1
                  ml-4
                  focus:shadow
                "
              >
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </nav>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      categories: "",
      form: {
        image: "",
        label: "",
        category: "",
      },
      errors: "",
    };
  },
  methods: {
    fetchCategories(search) {
      // Request categories with search if is exists
      axios.get("api/categories?search=" + search).then((res) => {
        /**
         * Check for result length
         * To avoid show option if is it the same as the input value
         */
        if (res.data.length > 1) {
          // Fill categories
          this.categories = res.data;
        } else {
          // empty the values of categories variable
          this.categories = "";
        }
      });
    },
    update() {
      let headersList = {
        Accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
      };

      let reqOptions = {
        url: "/api/images/" + this.$route.params.id,
        method: "PATCH",
        headers: headersList,
        data: `label=${this.form.label}&category_name=${this.form.category_name}`,
      };

      axios
        .request(reqOptions)
        .then((response) => {
          if (response.data.errors) {
            this.errors = response.data.errors;
          } else if (response.data.success) {
            // Clean form inputs
            this.form.image = "";
            this.form.label = "";
            this.form.category = "";

            // Remove errors
            this.errors = "";
            //  Show success alert
            const Toast = this.$swal.mixin({
                toast:true,
                position:'top-end',
                showConfirmButton:false,
                timer:2500,
                timerProgressBar:true,
                didOpen:(toast)=>{
                  toast.addEventListener('mouseenter',this.$swal.stopTimer)
                  toast.addEventListener('mouseleave',this.$swal.resumeTimer)
                }
            })
            Toast.fire({
                title:response.data.success,
                icon: "success"
            });
            // Redirect to the home page
            this.$router.push("/");
          } else {
            this.$swal.fire({
              title: "Something wrong!. Please try again",
              icon: "question",
            });
          }
        })

        .catch((res) => {
          this.$swal.fire({
            icon: "error",
            title: "Error!.",
            text: res.message,
          });
        });
      // Send post request
    },
    fetchImages() {
      axios.get("/api/images/" + this.$route.params.id).then((res) => {
        this.form = res.data;
      });
    },
  },
  created() {
    this.fetchImages();
  },
};
</script>
