<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Keluhan</div>

                    <div class="card-body">
                        <form @submit.prevent="editKeluhan">
                            <div class="form-group">
                                <label for="nama">Nama:</label>
                                <input
                                    v-model="keluhan.nama"
                                    class="form-control"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input
                                    v-model="keluhan.email"
                                    class="form-control"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="nomor_hp">Nomor HP:</label>
                                <input
                                    v-model="keluhan.nomor_hp"
                                    class="form-control"
                                    pattern="[0-9]*"
                                />
                            </div>

                            <div class="form-group">
                                <label for="status_keluhan"
                                    >Status Keluhan:</label
                                >
                                <select
                                    v-model="keluhan.status_keluhan"
                                    class="form-control"
                                    required
                                >
                                    <option value="0">Received</option>
                                    <option value="1">In Process</option>
                                    <option value="2">Done</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="keluhan">Keluhan:</label>
                                <textarea
                                    v-model="keluhan.keluhan"
                                    class="form-control"
                                    required
                                ></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
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
    data() {
        return {
            keluhan: {
                id: 0,
                nama: "",
                email: "",
                nomor_hp: "",
                status_keluhan: "0",
                keluhan: "",
            },
        };
    },
    mounted() {
        const keluhanId = this.$route.params.id;

        axios
            .get(`/api/keluhan-pelanggan/${keluhanId}`)
            .then((response) => {
                this.keluhan = response.data;
            })
            .catch((error) => {
                console.error("Error fetching keluhan data:", error);
            });
    },
    methods: {
        editKeluhan() {
            axios
                .put(`/api/keluhan-pelanggan/${this.keluhan.id}`, this.keluhan)
                .then((response) => {
                    console.log("Keluhan updated successfully:", response.data);
                    this.$router.push({ name: "keluhan_pelanggan" });
                })
                .catch((error) => {
                    console.error("Error updating keluhan:", error);
                });
        },
    },
};
</script>

<style scoped>
</style>
