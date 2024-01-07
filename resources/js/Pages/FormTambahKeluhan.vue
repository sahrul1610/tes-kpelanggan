<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Keluhan Pelanggan</div>
                    <div class="card-body">
                        <h3>Form Tambah Keluhan</h3>
                        <form @submit.prevent="addKeluhan">
                            <div class="form-group">
                                <label for="nama">Nama:</label>
                                <input
                                    type="text"
                                    v-model="formData.nama"
                                    class="form-control"
                                    required
                                />
                                <div v-if="formErrors.nama" class="text-danger">
                                    {{ formErrors.nama[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input
                                    type="email"
                                    v-model="formData.email"
                                    class="form-control"
                                    required
                                />
                                <div
                                    v-if="formErrors.email"
                                    class="text-danger"
                                >
                                    {{ formErrors.email[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nomor_hp">Nomor HP:</label>
                                <input
                                    type="text"
                                    v-model="formData.nomor_hp"
                                    class="form-control"
                                    pattern="[0-9]*"
                                />
                                <div
                                    v-if="formErrors.nomor_hp"
                                    class="text-danger"
                                >
                                    {{ formErrors.nomor_hp[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status_keluhan"
                                    >Status Keluhan:</label
                                >
                                <select
                                    v-model="formData.status_keluhan"
                                    class="form-control"
                                    required
                                >
                                    <option value="0">Received</option>
                                    <option value="1">In Process</option>
                                    <option value="2">Done</option>
                                </select>
                                <div
                                    v-if="formErrors.status_keluhan"
                                    class="text-danger"
                                >
                                    {{ formErrors.status_keluhan[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keluhan">Keluhan:</label>
                                <textarea
                                    v-model="formData.keluhan"
                                    class="form-control"
                                    required
                                ></textarea>
                                <div
                                    v-if="formErrors.keluhan"
                                    class="text-danger"
                                >
                                    {{ formErrors.keluhan[0] }}
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Tambah Keluhan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: ["showFormFlag"],
    data() {
        return {
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
            formData: {
                nama: "",
                email: "",
                nomor_hp: "",
                status_keluhan: "0",
                keluhan: "",
            },
            formErrors: {},
        };
    },
    methods: {
        addKeluhan() {
            axios
                .post("/api/keluhan-pelanggan/add", this.formData, {
                    headers: {
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                })
                .then((response) => {
                    this.$emit("keluhanAdded");
                    this.resetForm();
                    this.isLoading = true;
                    this.$router.push({ name: 'keluhan_pelanggan' });
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.formErrors = error.response.data.errors;
                    } else {
                        console.error("Error adding keluhan:", error);
                    }
                });
        },

        resetForm() {
            this.formData = {
                nama: "",
                email: "",
                nomor_hp: "",
                status_keluhan: "0",
                keluhan: "",
            };
            this.formErrors = {};
        },
    },
};
</script>

<style scoped>
/* Style komponen di sini */
</style>
