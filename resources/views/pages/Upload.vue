<template>
  <div class="dark:bg-gray-800 min-h-screen">
    <nav>
      <div class="py-2 px-3">
        <router-link to="/" class="text-blue-500 text-md font-medium">
          &leftarrow; Go back
        </router-link>
      </div>
      <!-- Upload Image Form -->
      <div class="container">
        <div
          class="shadow rounded mx-auto w-96 mt-16 dark:border-gray-600 border"
        >
          <h1 class="text-center font-bold text-lg dark:text-white">
            Upload an Image.
          </h1>
          <form
            action=""
            method="POST"
            class="flex flex-col justify-between gap-3 rounded"
            @submit.prevent="upload()"
          >
            <div class="mx-3">
              <div
                class="
                  h-48
                  mx-auto
                  my-2
                  rounded
                  relative
                  border-2 border-dashed border-gray-100
                  dark:border-gray-600
                  bg-gray-50/40
                  dark:bg-gray-700/50
                "
                :class="errors.image ? 'border-red-300' : ''"
              >
                <input
                  type="file"
                  name="image"
                  class="absolute inset-0 z-10 cursor-pointer opacity-0 w-full"
                  @change="onImageChange($event)"
                />
                <div
                  class="
                    absolute
                    top-1/2
                    -translate-y-1/2
                    w-full
                    text-center
                    z-0
                  "
                >
                  <i class="block">&uparrow;</i>
                  <span class="text-gray-400 text-sm"
                    >Drag & drop or click to upload from your device</span
                  >
                </div>
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
                :class="errors.category ? 'border-red-300' : ''"
                placeholder="Select image category"
                v-model="form.category"
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
                v-for="error in errors.category"
                :key="error.category"
                v-show="errors.category"
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
                Upload
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
    upload() {
      // Create object of form inputs
      let formData = new FormData();
      formData.append("image", this.form.image);
      formData.append("label", this.form.label);
      formData.append("category", this.form.category);

      // Headers needed to upload an image
      let headers = {
        "Content-Type": "multipart/form-data",
      };

      // Send post request
      axios
        .post("api/images", formData, headers)
        .then((res) => {
          if (res.data.errors) {
            this.errors = res.data.errors;
          } else if (res.data.success) {
            // Clean form inputs
            this.form.image = "";
            this.form.label = "";
            this.form.category = "";

            // Remove errors
            this.errors = "";
            this.$swal.fire("Success", res.data.success, "success");
          } else {
            this.$swal.fire({
              title: "Something wrong!. Please try again",
              icon: "question",
            });
          }
        })
        .catch(() => {
          this.$swal.fire({
            icon: "error",
            title: "Error!.",
            text: "Please try again",
          });
        });
    },

    onImageChange(e) {
      // image object depends on browser compatibility
      let image = e.target.files || e.dataTransfer.files;

      //   If has a file store it in form object
      if (image.length) {
        this.form.image = image[0];
      }
    },
  },
};
</script>