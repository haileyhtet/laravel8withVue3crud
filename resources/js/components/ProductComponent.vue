<template>
  <div class="container my-5">
    <div class="row justify-content-end mb-3">
      <div class="col-4">
        <button class="btn btn-primary" @click="create()">
          <i class="fas fa-plus-circle"></i> Create
        </button>
      </div>
      <div class="col-4">
        <form @submit.prevent="view">
          <div class="input-group">
            <input
              v-model="search"
              type="text"
              placeholder="Search"
              class="form-control"
            />
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <div class="card">
          <h4 class="card-header">
            {{ isEditMode ? "Edit" : "Create" }}
          </h4>
          <div class="card-body">
            <alert-error :form="product"></alert-error>
            <form @submit.prevent="isEditMode ? update() : store()">
              <div class="form-group">
                <label>Name:</label>
                <input
                  v-model="product.name"
                  type="text"
                  class="form-control"
                />
                <div
                  v-if="product.errors.has('name')"
                  v-html="product.errors.get('name')"
                  class="text-danger"
                />
              </div>
              <div class="form-group">
                <label>Price:</label>
                <input
                  v-model="product.price"
                  type="number"
                  class="form-control"
                />
                <div
                  v-if="product.errors.has('price')"
                  v-html="product.errors.get('price')"
                  class="text-danger"
                />
              </div>
              <br />
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-save mr-1"></i> Save
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-8">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.data" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.price }}</td>
              <td>
                <button class="btn btn-success btn-sm" @click="edit(product)">
                  <i class="fas fa-edit mr-1"></i> Edit
                </button>
                <button
                  class="btn btn-danger btn-sm"
                  @click="destroy(product.id)"
                >
                  <i class="fas fa-trash-alt mr-1"></i> Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <pagination
          :data="products"
          @pagination-change-page="view"
        ></pagination>
      </div>
    </div>
   
  </div>
</template>

<script>
import Form from "vform";
export default {
  name: "ProductComponent",
 
  data() {
    return {
    fullPage:true,
      isEditMode: false,
      search: "",
      products: {},
      product: new Form({
        id: "",
        name: "",
        price: "",
      }),
    };
  },
  methods: {
    view(page = 1) {
       
      axios.get("/api/product?page=" + page);
      axios
        .get(`api/product?page=${page}&search=${this.search}`)
        .then((response) => {
        this.products = response.data;
        setTimeout(()=>{
                    this.$Progress.finish();
        },3000);

        })
       
        .catch(error => {
            this.$Progress.fail();
        });
    },
    create() {
      this.product.clear();
      this.isEditMode = false;
      this.product.reset();
    },
    store() {
      this.product.post("/api/product").then((response) => {
        this.view();
        this.product.id = "";
        this.product.name = "";
        this.product.price = "";
         Toast.fire({
            icon: 'success',
            title: 'Created successfully'
            });
      });
    },
    edit(product) {
      this.product.clear();
      this.isEditMode = true;
      this.product.fill(product);
    },
    update() {
      axios
        .put(`/api/product/${this.product.id}`, this.product)

        .then((response) => {
          this.view();
          this.product.reset();
           Toast.fire({
            icon: 'success',
            title: 'Updated successfully'
            });
        });
    },
    destroy(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/api/product/${id}`).then((response) => {
            this.view();
            Swal.fire({ title: "Deleted", icon: "success" });
            Toast.fire({
            icon: 'success',
            title: 'Deleted successfully'
            });
          });
        }
      });
    },
  },
  created() {
    this.view();
  },
};
</script>

