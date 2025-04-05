// Select all canvas elements and buttons
const ctx1 = document.getElementById('Chart1').getContext('2d');
const ctx2 = document.getElementById('Chart2').getContext('2d');
const ctx3 = document.getElementById('Chart3').getContext('2d');
const ctx4 = document.getElementById('Chart4').getContext('2d');

const reloadButton1 = document.getElementById('reloadButton1');
const reloadButton2 = document.getElementById('reloadButton2');
const reloadButton3 = document.getElementById('reloadButton3');
const reloadButton4 = document.getElementById('reloadButton4');

let barChart1 = null;
let lineChart2 = null;
let pieChart3 = null;
let barChart2 = null;

async function getOrgIdFromPHP() {
  try {
    const response = await fetch('../actions/get_org_id.php');
    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }

    return data.Organizationid; // ✅ Return the org ID
  } catch (error) {
    console.error("Error retrieving Org ID:", error);
    return null;
  }
}

async function fetchData_1() {
  try {
    const orgId = await getOrgIdFromPHP(); // ✅ Fetch Org ID from PHP session
    if (!orgId) {
      console.error("Organization ID not found!");
      return { labels: [], values: [] };
    }

    const response = await fetch('http://13.53.41.87/get_scores_1', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Organizationid': orgId // ✅ Send Org ID dynamically
      }
    });

    const data = await response.json();

    if (data.error) {
      console.error("Error fetching role scores:", data.error);
      return { labels: [], values: [] };
    }

    return {
      labels: data.map(entry => entry.role_name),
      values: data.map(entry => entry.relevance_score)
    };

  } catch (error) {
    console.error("Error fetching role scores:", error);
    return { labels: [], values: [] };
  }
}


// Separate data fetching from chart rendering
async function fetchData_2() {
  try {
    const orgId = await getOrgIdFromPHP(); // ✅ Fetch Org ID from PHP session
    if (!orgId) {
      console.error("Organization ID not found!");
      return { labels: [], values: [] };
    }

    const response = await fetch('http://13.53.41.87/get_scores_2', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Organizationid': orgId // ✅ Send Org ID dynamically
      }
    });

    const data = await response.json();

    if (data.error) {
      console.error("Error fetching role scores:", data.error);
      return { labels: [], values: [] };
    }

    return {
      labels: data.map(entry => entry.role_name),
      values: data.map(entry => entry.relevance_score)
    };

  } catch (error) {
    console.error("Error fetching role scores:", error);
    return { labels: [], values: [] };
  }
}


// Separate data fetching from chart rendering
async function fetchData_3() {
  try {
    const orgId = await getOrgIdFromPHP(); // ✅ Fetch Org ID from PHP session
    if (!orgId) {
      console.error("Organization ID not found!");
      return { labels: [], values: [] };
    }

    const response = await fetch('http://13.53.41.87/get_scores_3', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Organizationid': orgId // ✅ Send Org ID dynamically
      }
    });

    const data = await response.json();

    if (data.error) {
      console.error("Error fetching role scores:", data.error);
      return { labels: [], values: [] };
    }

    return {
      labels: data.map(entry => entry.role_name),
      values: data.map(entry => entry.relevance_score)
    };

  } catch (error) {
    console.error("Error fetching role scores:", error);
    return { labels: [], values: [] };
  }
}


async function fetchData_4() {
  try {
    const orgId = await getOrgIdFromPHP(); // ✅ Fetch Org ID from PHP session
    if (!orgId) {
      console.error("Organization ID not found!");
      return { labels: [], values: [] };
    }

    const response = await fetch('http://13.53.41.87/get_scores_1', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Organizationid': orgId // ✅ Send Org ID dynamically
      }
    });

    const data = await response.json();

    if (data.error) {
      console.error("Error fetching role scores:", data.error);
      return { labels: [], values: [] };
    }

    return {
      labels: data.map(entry => entry.role_name),
      values: data.map(entry => entry.relevance_score)
    };

  } catch (error) {
    console.error("Error fetching role scores:", error);
    return { labels: [], values: [] };
  }
}

// Individual chart rendering functions
async function renderBarChart_1() {
  try {
    const { labels, values } = await fetchData_1();

    const gradient1 = ctx1.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgb(146, 61, 65)');
    gradient1.addColorStop(1, 'rgb(146, 61, 65)');

    const barChartData = {
      labels: labels,
      datasets: [{
        label: 'Role Relevance Score (%)',
        data: values,
        backgroundColor: gradient1,
        barThickness: 70,
        borderWidth: 0,
        hoverBackgroundColor: gradient1,
        hoverBorderWidth: 0,
      }],
    };

    const barChartOptions = {
      responsive: true,
      animation: {
        duration: 2000,
        easing: 'easeOutBounce',
        delay: function (context) {
          return context.dataIndex * 200;
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          enabled: true,
          backgroundColor: '#333',
          titleColor: 'white',
          bodyColor: 'white',
          titleFont: { family: 'Times New Roman', size: 16 },
          bodyFont: { family: 'Times New Roman', size: 16 },
          animation: { duration: 200 },
        },
      },
      scales: {
        x: {
          ticks: {
            color: 'black',
            font: { family: 'Times New Roman', size: 16 },
            callback: function (value, index, values) {
              let label = this.getLabelForValue(value);
              return label.split(' '); // Splitting the label at spaces
            },
          },
          grid: { display: false },
        },
        y: {
          ticks: {
            color: 'black',
            font: { family: 'Times New Roman', size: 20 },
            stepSize: 10,
          },
          grid: { display: false },
        },
      },
      elements: {
        bar: {
          borderRadius: 8,
          borderSkipped: false,
        },
      },
    };

    if (barChart1) {
      barChart1.data = barChartData;
      barChart1.update();
    } else {
      barChart1 = new Chart(ctx1, { type: 'bar', data: barChartData, options: barChartOptions });
    }
  } catch (error) {
    console.error('Error loading bar chart:', error);
  }
}

async function renderLineChart_1() {
  try {
    const { labels, values } = await fetchData_2();

    const lineChartData = {
      labels: labels,
      datasets: [{
        label: 'Role Relevance Score (%)',
        data: values,
        borderColor: 'rgb(0, 123, 255)',
        backgroundColor: 'rgba(0, 123, 255, 0.2)',
        borderWidth: 2,
        pointBackgroundColor: 'rgb(0, 123, 255)',
        pointRadius: 5,
        fill: true,
        tension: 0.4,
      }],
    };

    const lineChartOptions = {
      responsive: true,
      animation: {
        duration: 2000,
        easing: 'easeOutBounce',
        delay: function (context) {
          return context.dataIndex * 200;
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          enabled: true,
          backgroundColor: '#333',
          titleColor: 'white',
          bodyColor: 'white',
          titleFont: { family: 'Times New Roman', size: 16 },
          bodyFont: { family: 'Times New Roman', size: 16 },
          animation: { duration: 200 },
        },
      },
      scales: {
        x: {
          ticks: {
            color: 'black',
            font: { family: 'Times New Roman', size: 16 },
            callback: function (value, index, values) {
              let label = this.getLabelForValue(value);
              return label.split(' '); // Splitting the label at spaces
            },
          },
          grid: { display: false },
        },
        y: { ticks: { color: 'black', font: { family: 'Times New Roman', size: 20 }, stepSize: 10 }, grid: { display: false } },
      },
      elements: { line: { borderWidth: 3 } },
    };

    if (lineChart2) {
      lineChart2.data = lineChartData;
      lineChart2.update();
    } else {
      lineChart2 = new Chart(ctx2, { type: 'line', data: lineChartData, options: lineChartOptions });
    }
  } catch (error) {
    console.error('Error loading line chart:', error);
  }
}

async function renderPieChart_1() {
  try {
    const { labels, values } = await fetchData_3();

    const pieChartData = {
      labels: labels,
      datasets: [{
        label: 'Role Relevance Score (%)',
        data: values,
        backgroundColor: values.map(value => {
          return `rgb(16, 85, 0, ${value / 100})`;
        }),
        borderWidth: 4,
      }],
    };

    const pieChartOptions = {
      responsive: true,
      animation: {
        duration: 2000,
        easing: 'easeOutBounce',
        delay: function (context) {
          return context.dataIndex * 200;
        },
      },
      plugins: {
        tooltip: {
          enabled: true,
          backgroundColor: '#333',
          titleColor: 'white',
          bodyColor: 'white',
          titleFont: { family: 'Times New Roman', size: 16 },
          bodyFont: { family: 'Times New Roman', size: 16 },
          animation: { duration: 200 },
        },
        legend: {
          position: 'bottom', // Stack labels vertically on the right
          labels: {
            font: { family: 'Times New Roman', size: 16 },
            color: 'black',
          },
        },
      },
    };    

    if (pieChart3) {
      pieChart3.data = pieChartData;
      pieChart3.update();
    } else {
      pieChart3 = new Chart(ctx3, { type: 'pie', data: pieChartData, options: pieChartOptions });
    }
  } catch (error) {
    console.error('Error loading pie chart:', error);
  }
}

// Individual chart rendering functions
async function renderBarChart_2() {
  try {
    const { labels, values } = await fetchData_4();

    const gradient1 = ctx4.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgb(146, 61, 65)');
    gradient1.addColorStop(1, 'rgb(146, 61, 65)');

    const barChartData = {
      labels: labels,
      datasets: [{
        label: 'Role Relevance Score (%)',
        data: values,
        backgroundColor: gradient1,
        barThickness: 70,
        borderWidth: 0,
        hoverBackgroundColor: gradient1,
        hoverBorderWidth: 0,
      }],
    };

    const barChartOptions = {
      responsive: true,
      animation: {
        duration: 2000,
        easing: 'easeOutBounce',
        delay: function (context) {
          return context.dataIndex * 200;
        },
      },
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          enabled: true,
          backgroundColor: '#333',
          titleColor: 'white',
          bodyColor: 'white',
          titleFont: { family: 'Times New Roman', size: 16 },
          bodyFont: { family: 'Times New Roman', size: 16 },
          animation: { duration: 200 },
        },
      },
      scales: {
        x: {
          ticks: {
            color: 'black',
            font: { family: 'Times New Roman', size: 16 },
            callback: function (value, index, values) {
              let label = this.getLabelForValue(value);
              return label.split(' '); // Splitting the label at spaces
            },
          },
          grid: { display: false },
        },
        y: {
          ticks: {
            color: 'black',
            font: { family: 'Times New Roman', size: 20 },
            stepSize: 10,
          },
          grid: { display: false },
        },
      },
      elements: {
        bar: {
          borderRadius: 8,
          borderSkipped: false,
        },
      },
    };

    if (barChart2) {
      barChart2.data = barChartData;
      barChart2.update();
    } else {
      barChart2 = new Chart(ctx4, { type: 'bar', data: barChartData, options: barChartOptions });
    }
  } catch (error) {
    console.error('Error loading bar chart:', error);
  }
}

// Initial load of all charts
async function initializeCharts() {
  await renderBarChart_1();
  await renderLineChart_1();
  await renderPieChart_1();
  await renderBarChart_2();
}

// Initialize on page load
initializeCharts();

// Add event listeners for reload buttons - now each button only reloads its specific chart
reloadButton1.addEventListener('click', renderBarChart_1);
reloadButton2.addEventListener('click', renderLineChart_1);
reloadButton3.addEventListener('click', renderPieChart_1);
reloadButton4.addEventListener('click', renderBarChart_2);