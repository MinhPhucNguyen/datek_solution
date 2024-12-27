<template>
  <div class="container-fluid p-1">
    <div class="d-flex">
      <i class="mdi mdi-home text-muted hover-cursor"></i>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h4 class="mb-0 text-gray-800 fw-bold">Tổng quan</h4>
    </div>
    <div class="row pl-3 mt-2">
      <div
        class="mr-3 bg-success bg-gradient rounded-4"
        style="height: 180px; width: 378px"
      >
        <router-link
          class="dashboard-card h-100 d-block text-decoration-none"
          :to="{ name: 'admin.orders' }"
        >
          <div
            class="h-100 w-100 d-flex justify-content-center align-items-center text-white flex-column fw-bolder"
            style="font-size: 60px"
          >
            <i class="fa-solid fa-file-invoice"></i>
            <h5 class="mt-3 fw-bold">Đơn hàng</h5>
          </div>
        </router-link>
      </div>
      <div
        class="mr-3 bg-success bg-gradient rounded-4"
        style="height: 180px; width: 378px"
      >
        <router-link
          class="dashboard-card h-100 d-block text-decoration-none"
          :to="{ name: 'admin.users' }"
        >
          <div
            class="h-100 w-100 d-flex justify-content-center align-items-center text-white flex-column fw-bolder"
            style="font-size: 60px"
          >
            <i class="fa-solid fa-users"></i>
            <h5 class="mt-3 fw-bold">Khách hàng</h5>
          </div>
        </router-link>
      </div>
      <div
        class="mr-3 bg-success bg-gradient rounded-4"
        style="height: 180px; width: 378px"
      >
        <router-link
          :to="{ name: 'admin.products' }"
          class="dashboard-card h-100 d-block text-decoration-none"
        >
          <div
            class="h-100 w-100 d-flex justify-content-center align-items-center text-white flex-column"
            style="font-size: 60px"
          >
            <i class="fa-solid fa-box mr-2"></i>
            <h5 class="mt-3 fw-bold">Sản phẩm</h5>
          </div>
        </router-link>
      </div>
      <div
        class="mr-3 bg-success bg-gradient rounded-4"
        style="height: 180px; width: 378px"
      >
        <router-link
          :to="{ name: 'admin.categories' }"
          class="dashboard-card h-100 d-block text-decoration-none"
        >
          <div
            class="h-100 w-100 d-flex justify-content-center align-items-center text-white flex-column"
            style="font-size: 60px"
          >
            <i class="fa-solid fa-list"></i>
            <h5 class="mt-3 fw-bold">Danh mục sản phẩm</h5>
          </div>
        </router-link>
      </div>
    </div>
    <div class="mt-4 income-container">
      <div class="income-form">
        <h5 class="text-dark">Thống kê</h5>
        <div class="d-flex date-form">
          <div class="date-from d-flex flex-column">
            <label for="date-from">Từ</label>
            <input name="form-date" type="date" v-model="startDate" />
          </div>
          <div class="date-to d-flex flex-column">
            <label for="date-to">Đến</label>
            <input name="to-date" type="date" v-model="endDate" />
          </div>
          <button class="statistic-btn" @click="fetchStats">
            <i class="fa-solid fa-chart-simple pe-1"></i> Thống kê
          </button>
          <button class="csv-export-btn" @click="exportCSV">
            <i class="fa-solid fa-file-export pe-1"></i>Xuất CSV
          </button>
        </div>
      </div>

      <div class="chart d-flex">
        <div class="chart-container">
          <canvas id="incomeChart"></canvas>
        </div>

        <div class="chart-container">
          <canvas id="orderStatsChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import axios from "axios";

const currentYear = new Date().getFullYear();
const startDate = ref(new Date(currentYear, 0, 1).toISOString().split("T")[0]);
const endDate = ref(new Date(currentYear, 11, 31).toISOString().split("T")[0]);
const income = ref(new Array(12).fill(0));
const orderStats = ref(new Array(12).fill(0));
const labels = ref([
  "Tháng 1",
  "Tháng 2",
  "Tháng 3",
  "Tháng 4",
  "Tháng 5",
  "Tháng 6",
  "Tháng 7",
  "Tháng 8",
  "Tháng 9",
  "Tháng 10",
  "Tháng 11",
  "Tháng 12",
]);
let incomeChart = null;
let orderStatsChart = null;

const fetchStats = async () => {
  await fetchIncome();
  await fetchOrderStats();
};

const fetchIncome = async () => {
  try {
    const response = await axios.get("/income", {
      params: {
        start_date: startDate.value,
        end_date: endDate.value,
      },
    });

    const data = response.data;
    income.value = new Array(12).fill(0);
    data.labels.forEach((month, index) => {
      const monthIndex = new Date(month).getMonth();
      income.value[monthIndex] = data.income[index];
    });

    renderIncomeChart();
  } catch (error) {
    console.error("Error fetching income:", error);
  }
};

const fetchOrderStats = async () => {
  try {
    const response = await axios.get("/order-stats", {
      params: {
        start_date: startDate.value,
        end_date: endDate.value,
      },
    });

    const data = response.data;
    orderStats.value = new Array(12).fill(0);
    data.labels.forEach((month, index) => {
      const monthIndex = new Date(month).getMonth();
      orderStats.value[monthIndex] = data.total_orders[index];
    });

    renderOrderStatsChart();
  } catch (error) {
    console.error("Error fetching order stats:", error);
  }
};

const renderIncomeChart = () => {
  const ctx = document.getElementById("incomeChart").getContext("2d");

  if (incomeChart) {
    incomeChart.destroy();
  }

  incomeChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: labels.value,
      datasets: [
        {
          label: "Doanh thu",
          data: income.value,
          borderColor: "#1cc88a",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: "Tháng",
          },
        },
        y: {
          title: {
            display: true,
            text: "Doanh thu (VND)",
          },
          beginAtZero: true,
        },
      },
    },
  });
};

const renderOrderStatsChart = () => {
  const ctx = document.getElementById("orderStatsChart").getContext("2d");

  if (orderStatsChart) {
    orderStatsChart.destroy();
  }

  orderStatsChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels.value,
      datasets: [
        {
          label: "Số lượng đơn hàng",
          data: orderStats.value,
          backgroundColor: "#4e43d8",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: "Tháng",
          },
        },
        y: {
          title: {
            display: true,
            text: "Số lượng đơn hàng",
          },
          ticks: {
            beginAtZero: true,
            callback: function (value) {
              return Math.floor(value);
            },
          },
        },
      },
    },
  });
};

onMounted(() => {
  startDate.value = `${currentYear}-01-01`;
  endDate.value = `${currentYear}-12-31`;

  fetchIncome();
  fetchOrderStats();
});

const exportCSV = async () => {
  try {
    const response = await axios.get("/export-orders", {
      params: {
        start_date: startDate.value,
        end_date: endDate.value,
      },
      responseType: "blob",
    });

    const blob = new Blob([response.data], { type: "text/csv" });

    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `orders_${startDate.value}_to_${endDate.value}.csv`;

    link.click();
  } catch (error) {
    console.error("Error exporting CSV:", error);
  }
};
</script>

<style scoped lang="scss">
.income-form {
  display: flex;
  flex-direction: column;
  width: 50%;
}

.date-form {
  gap: 20px;

  label {
    font-weight: bold;
    margin-bottom: 0px;
  }

  input {
    width: 200px;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  .csv-export-btn,
  .statistic-btn {
    padding: 5px 20px;
    border-radius: 5px;
    border: none;
    background-color: #1cc88a;
    color: white;
    cursor: pointer;
    font-weight: bold;
  }
}

.chart-container {
  width: 50%;
  gap: 30px;
  margin: 20px 0 100px;
}
</style>
