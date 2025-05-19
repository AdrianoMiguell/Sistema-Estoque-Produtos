const sectionProdByCat = document.getElementById(
  "section-products-by-category"
);

console.log(sectionProdByCat);

document.addEventListener("DOMContentLoaded", () => {
  fetch(`http://localhost/sistema_produtos/dashboard/categories/countProducts`)
    .then((response) => response.json())
    .then((categories) => {
      if (categories.length > 0) {
        sectionProdByCat.innerHTML = `
            <div class="container-chart">
                <canvas id="chart-categories"></canvas>
            </div>
        `;

        let labels = [];
        let values = [];

        categories.forEach((category) => {
          labels.push(category.cat_name);
          values.push(category.total_products);
        });

        constructChart(labels, values);
      } else {
        // sectionProdByCat.innerHtml += `
        //     <span class='span-prodcat'> Sem dados de Categoria </span>
        // `;
      }
    })
    .catch((e) => {
      console.error(e);
    });
});

function constructChart(labels, values) {
  const sectionProdByCat = document.getElementById("chart-categories");

  console.log(labels);
  console.log(values);
  console.log(sectionProdByCat);

  const config = {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Categorias de Produtos",
          data: values,
          backgroundColor: [
            "rgba(255, 99, 132, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(75, 192, 192, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)",
          ],
          borderColor: [
            "rgb(255, 99, 132)",
            "rgb(255, 159, 64)",
            "rgb(255, 205, 86)",
            "rgb(75, 192, 192)",
            "rgb(54, 162, 235)",
            "rgb(153, 102, 255)",
            "rgb(201, 203, 207)",
          ],

          borderWidth: 1,
          barPercentage: 1,
          maxBarThickness: 60,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            precision: 0, // força valores inteiros
            callback: function (value) {
              return Number.isInteger(value) ? value : null;
            },
          },
        },
      },
    },
  };

  new Chart(sectionProdByCat, config);
}
