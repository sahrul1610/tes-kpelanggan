<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Keluhan Pelanggan</div>

                    <div class="card-body">
                        <button @click="showAddForm" class="btn btn-primary">
                            + Tambah Keluhan
                        </button>
                        <!-- <button
                            @click="exportTo('xls')"
                            class="btn btn-success"
                        >
                            Export to Excel
                        </button> -->
                        <div class="export-dropdown">
                            <label for="exportFormat"
                                >Pilih export format:</label
                            >
                            <select v-model="selectedFormat" id="exportFormat">
                                <option value="xlsx">XLSX</option>
                                <option value="csv">CSV</option>
                                <option value="txt">TXT</option>
                                <option value="pdf">PDF</option>
                            </select>
                            <button
                                @click="exportTo(selectedFormat)"
                                class="btn btn-success"
                            >
                                Export
                            </button>
                        </div>
                        <div v-if="isLoading" class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                        <table
                            id="keluhan-table"
                            class="table"
                            v-if="!isLoading"
                        >
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Nomor HP</th>
                                    <th>Status Keluhan</th>
                                    <th>Keluhan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(keluhan, index) in keluhanList"
                                    :key="keluhan.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ keluhan.nama }}</td>
                                    <td>{{ keluhan.email }}</td>
                                    <td>{{ keluhan.nomor_hp }}</td>
                                    <td>{{ keluhan.status_keluhan_text }}</td>
                                    <td>{{ keluhan.keluhan }}</td>
                                    <td>
                                        <button
                                            @click="editKeluhan(keluhan.id)"
                                            class="btn btn-warning"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="confirmDelete(keluhan.id)"
                                            class="btn btn-danger"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
            keluhanList: [],
            keluhanHistory: [],
            isLoading: true,
            selectedFormat: 'xlsx',
            // Data-formulir di sini
        };
    },
    mounted() {
        setTimeout(() => {
            this.getKeluhanList();
            this.getKeluhanHistory();
            this.isLoading = false;
        }, 2000);
    },
    methods: {
        // ...
        showAddForm() {
            this.$router.push({ name: "formTambahKeluhan" });
        },
        editKeluhan(keluhanId) {
            this.$router.push({
                name: "formEditKeluhan",
                params: { id: keluhanId },
            });
        },
        confirmDelete(keluhanId) {
            if (
                window.confirm("Apakah Anda yakin ingin menghapus keluhan ini?")
            ) {
                this.deleteKeluhan(keluhanId);
            }
        },
        deleteKeluhan(keluhanId) {
            this.isLoading = true;
            axios
                .delete(`/api/keluhan-pelanggan/delete/${keluhanId}`)
                .then((response) => {
                    this.keluhanList = this.keluhanList.filter(
                        (keluhan) => keluhan.id !== keluhanId
                    );
                    console.log("Keluhan berhasil dihapus:", response.data);
                })
                .catch((error) => {
                    console.error("Error deleting keluhan:", error);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        getKeluhanList() {
            axios
                .get("/api/keluhan-pelanggan")
                .then((response) => {
                    this.keluhanList = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching keluhan list:", error);
                });
        },
        exportTo(format) {
            this.isLoading = true;

            axios
                .get(`/api/keluhan-pelanggan/export/${format}`, {
                    responseType: "arraybuffer",
                })
                .then((response) => {
                    const blob = new Blob([response.data], {
                        type: response.headers["content-type"],
                    });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `keluhan-export.${format}`);
                    document.body.appendChild(link);
                    link.click();

                    // Setelah ekspor selesai, hentikan loading
                    this.isLoading = false;
                })
                .catch((error) => {
                    console.error("Error exporting data:", error);
                    this.isLoading = false;
                });
        },
        

        getKeluhanHistory() {
            const keluhanId = 1;

            axios
                .get(`/api/keluhan-pelanggan/${keluhanId}/history`)
                .then((response) => {
                    this.keluhanHistory = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching keluhan history:", error);
                });
        },
    },
};
</script>
<style scoped>

.loading-spinner {
    margin-top: 20px;
    text-align: center;
    font-size: 24px;
    color: #3498db;
}
.export-dropdown {
  margin-bottom: 20px;
}

.dropdown {
  display: inline-block;
  margin-right: 10px;
}

.dropdown-toggle {
  cursor: pointer;
}

.dropdown-menu {
  min-width: 0;
  box-shadow: none;
}

.dropdown-item {
  cursor: pointer;
}
</style>
