document.addEventListener("DOMContentLoaded", () => {
  const categorySelect = document.getElementById("category_id");
  const subcategorySelect = document.getElementById("subcategory_id");

  categorySelect.addEventListener("change", function () {
    const categoryId = this.value;

    if (!categoryId) {
      subcategorySelect.innerHTML =
        '<option value="">Selecione a categoria primeiro</option>';
      return;
    }

    fetch(
      `http://localhost/sistema_produtos/dashboard/products/get-subcategories?category_id=${categoryId}`
    )
      .then((response) => response.json())
      .then((data) => {
        subcategorySelect.innerHTML = "";
        if (data.length > 0) {
          subcategorySelect.innerHTML += '<option value="">Selecione</option>';
          data.forEach((sub) => {
            subcategorySelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
          });
        } else {
          subcategorySelect.innerHTML =
            '<option value="">Nenhuma subcategoria encontrada</option>';
        }
      })
      .catch(() => {
        subcategorySelect.innerHTML =
          '<option value="">Erro ao carregar</option>';
      });
  });
});
