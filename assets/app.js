import "./stimulus_bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("ajax-form");

    const colorRow = document.getElementById("color-selector");
    const colorSelector = document.getElementById("data_color");

    const dialog = document.getElementById("dialog");
    const showFormBtn = document.getElementById("show-form");
    const closeFormBtn = document.getElementById("close-form");
    showFormBtn &&
        showFormBtn.addEventListener("click", function (e) {
            dialog.showModal();
        });
    closeFormBtn &&
        closeFormBtn.addEventListener("click", function () {
            dialog.close();
        });

    const productSelector = document.getElementById("data_product");

    productSelector &&
        productSelector.addEventListener("change", function () {
            if (this.value == "pen") {
                colorRow.style.display = "block";
                colorSelector.required = true;
            } else {
                colorRow.style.display = "none";
                colorSelector.required = false;
                colorSelector.value = "";
            }
        });

    const tableBody = document.querySelector("#data-table tbody");

    form &&
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch(form.action || window.location.href, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        const newRow = `
                    <tr>
                        <td>${data.newData.id}</td>
                        <td>${data.newData.user}</td>
                        <td>${data.newData.product}</td>
                        <td>${data.newData.date}</td>
                        <td>${data.newData.color ?? ""}</td>
                        <td>${data.newData.amount}</td>
                    </tr>
                `;
                        tableBody.insertAdjacentHTML("beforeend", newRow);
                        form.reset();
                        dialog.close();
                    } else {
                        alert(
                            "Coś poszło nie tak: " +
                                (data.errors || "Niezidentyfikowany błąd.")
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Wystąpił błąd podczas zapisywania danych.");
                });
        });
});
