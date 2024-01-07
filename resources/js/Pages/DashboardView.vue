<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body d-flex">
                        <div class="flex-fill col-md-4">
                            <div class="card">
                                <canvas
                                    ref="pieChart"

                                ></canvas>
                            </div>
                        </div>
                        <div class="flex-fill col-md-6">
                            <div class="card">
                                <canvas
                                    ref="barChart"
                                    height="520px"

                                ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fill col-md-12">
                            <div class="card">
                                <h5 class="card-header">Top 10 Keluhan Umur Lama</h5>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>created_at</th>
                                                <th>Umur Keluhan</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(keluhan, index) in top10Keluhan" :key="index">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ keluhan.nama }}</td>
                                                <td>{{ keluhan.email }}</td>
                                                <td>{{ keluhan.created_at }}</td>
                                                <td>{{ keluhan.umur_keluhan }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
export default {
    data() {
        return {
            top10Keluhan: [],
        };
    },
    mounted() {
        this.fetchChartData();
        this.fetchTop10Keluhan();
    },
    methods: {
        fetchChartData() {
            axios.get("api/keluhan-chart/status").then((response) => {
                this.createPieChart(response.data);
            });
            axios.get("api/keluhan-chart/bar").then((response) => {
                this.createBarChart(response.data);
            });
        },
        fetchTop10Keluhan() {
            axios.get("api/top-10-keluhan").then((response) => {
                this.top10Keluhan = response.data.map(keluhan => {
                    keluhan.umur_keluhan = moment(keluhan.created_at).fromNow(true);
                    return keluhan;
                });
            });
        },
        createPieChart(data) {
            const ctx = this.$refs.pieChart.getContext("2d");
            new window.Chart(ctx, {
                type: "pie",
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            data: data.data,
                            backgroundColor: data.colors,
                        },
                    ],
                },
            });
        },
        createBarChart(data) {
            const ctx = this.$refs.barChart.getContext("2d");
            new window.Chart(ctx, {
                type: "bar",
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: "Received",
                            data: data.datasets[0].data,
                            backgroundColor: data.datasets[0].backgroundColor,
                        },
                        {
                            label: "In Process",
                            data: data.datasets[1].data,
                            backgroundColor: data.datasets[1].backgroundColor,
                        },
                        {
                            label: "Done",
                            data: data.datasets[2].data,
                            backgroundColor: data.datasets[2].backgroundColor,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        },
    },
};
</script>
